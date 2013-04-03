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

namespace WindowsAzure\TaskDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WindowsAzure\TaskDemoBundle\Model\BaseObject;

/**
 * @ORM\Entity(repositoryClass="WindowsAzure\TaskDemoBundle\Repository\TaskRepository")
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * UUID of task
     *
     * @ORM\Id @ORM\Column(type="string")
     * @var string
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $subject;

    /**
     * @ORM\ManyToOne(targetEntity="TaskType")
     * @var TaskType
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $dueDate;

    public function __construct($id = null, User $user = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->dueDate = new \DateTime("now");
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user.
     *
     * @return user.
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get subject.
     *
     * @return subject.
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set subject.
     *
     * @param subject the value to set.
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get type.
     *
     * @return type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param type the value to set.
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get dueDate.
     *
     * @return dueDate.
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set dueDate.
     *
     * @param dueDate the value to set.
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    }
}

