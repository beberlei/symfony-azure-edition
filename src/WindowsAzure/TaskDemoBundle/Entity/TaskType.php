<?php
/**
 * WindowsAzure TaskDemoBundle
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace WindowsAzure\TaskDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WindowsAzure\TaskDemoBundle\Model\BaseObject;

/**
 * @ORM\Entity(repositoryClass="WindowsAzure\TaskDemoBundle\Repository\EntityRepository")
 * @ORM\Table(name="task_types")
 */
class TaskType
{
    /**
     * @ORM\Id @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $label;

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }
}

