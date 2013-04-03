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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WindowsAzure\TaskDemoBundle\Entity\Message;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        var_dump($this->container->getParameter('kernel.cache_dir').'/appProdProjectContainer.php');
        var_dump(file_exists($this->container->getParameter('kernel.cache_dir').'/appProdProjectContainer.php'));
        var_dump(unserialize(file_get_contents($this->container->getParameter('kernel.cache_dir').'/appProdProjectContainer.php')));

        var_dump($this->container->get('doctrine.dbal.default_connection')->getParams());

        $env = array();
        foreach ($_SERVER as $k => $v) {
            if (strpos($k, "SYMFONY__")) {
                $env[$k] = $v;
            }
        }
        var_dump($env);

        return array();
    }

    /**
     * @Route("/hello", name="hello")
     * @Template()
     */
    public function helloAction()
    {
        $session = $this->get('session');
        $names = (array)$session->get('names');

        if ($name = $this->getRequest()->get('name')) {
            $sessionNames = $names;
            $sessionNames[] = strip_tags($name);
            $session->set('names', $sessionNames);
        }

        return array('names' => $names, 'name' => $name);
    }
}

