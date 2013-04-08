<?php
/**
 * WindowsAzure TaskDemoBundle
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace WindowsAzure\TaskDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use WindowsAzure\TaskDemoBundle\Form\Type\TaskType;
use WindowsAzure\TaskDemoBundle\Entity\Task;
use WindowsAzure\TaskDemoBundle\Entity\TaskEvent;

class TasksController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     * @Method("GET")
     * @Template()
     */
    public function getTasksAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->container->get('windows_azure_taskdemo.repository.task');
        $tasks      = $repository->findByDueDate($user);

        try {
            $tblManager = $this->container->get('windows_azure_distribution.key_value_store.entity_manager');
            $rangeQuery = $tblManager->createRangeQuery('WindowsAzure\TaskDemoBundle\Entity\TaskEvent', $user->getId());
            $rangeQuery->setLimit(20);
            $events     = $rangeQuery->execute();
        } catch(\Exception $e) {
            $events     = array();
        }

        return array('tasks' => $tasks, 'events' => $events);
    }

    /**
     * @Route("/tasks/new.html", name="task_new")
     * @Method("GET")
     * @Template()
     */
    public function newTaskAction()
    {
        $form = $this->createForm(new TaskType());

        return array('form' => $form->createView());
    }

    /**
     * @Route("/tasks", name="task_create")
     * @Method("POST")
     * @Template("WindowsAzureTaskDemoBundle:Tasks:newTask.html.twig")
     */
    public function postTaskAction()
    {
        $idGenerator = $this->container->get('windows_azure_task_demo.model.id_generator');
        $user        = $this->container->get('security.context')->getToken()->getUser();
        $task        = new Task($idGenerator->generateId('task'), $user);
        $form        = $this->createForm(new TaskType(), $task);

        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $repository = $this->container->get('windows_azure_taskdemo.repository.task');
            $repository->add($task);

            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->flush($task);

            $event = new TaskEvent($user, $task, TaskEvent::EVENT_CREATED, "Created Task '" . $task->getSubject() . "'");

            $tblManager = $this->container->get('windows_azure_distribution.key_value_store.entity_manager');
            $tblManager->persist($event);
            $tblManager->flush();

            return $this->redirect($this->generateUrl('task_list'));
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/tasks/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteTaskAction(Task $task)
    {
        $user       = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->container->get('windows_azure_taskdemo.repository.task');
        $repository->remove($task);

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $em->detach($user);
        $em->flush();

        $event = new TaskEvent($user, $task, TaskEvent::EVENT_DELETED, "Deleted Task '" . $task->getSubject() . "'");

        $tblManager = $this->container->get('windows_azure_distribution.key_value_store.entity_manager');
        $tblManager->persist($event);
        $tblManager->flush();

        return $this->redirect($this->generateUrl('task_list'));
    }
}


