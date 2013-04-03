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
use Symfony\Component\Security\Core\SecurityContext;

use WindowsAzure\TaskDemoBundle\Entity\User;
use WindowsAzure\TaskDemoBundle\Form\Type\UserType;

class LoginController extends Controller
{
    /**
     * @Route("/tasks/login", name="azure_login")
     * @Method("GET")
     * @Template()
     */
    public function getLoginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/tasks/register", name="azure_register")
     * @Template()
     */
    public function registerAction()
    {
        $idGenerator = $this->container->get('windows_azure_task_demo.model.id_generator');
        $user = new User($idGenerator->generateId(null));
        $form = $this->createForm(new UserType(), $user);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));

                $em = $this->container->get('doctrine.orm.default_entity_manager');
                $em->persist($user);

                $em->flush();

                $session = $request->getSession();
                $session->set(SecurityContext::LAST_USERNAME, $user->getUsername());

                return $this->redirect($this->generateUrl('azure_login'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/tasks/login_check", name="azure_login_check")
     */
    public function checkAction()
    {

    }

    /**
     * @Route("/tasks/logout", name="azure_logout")
     */
    public function logoutAction()
    {

    }
}
