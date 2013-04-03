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

use Doctrine\KeyValueStore\Mapping\Annotations as KeyValueStore;

/**
 * @KeyValueStore\Entity(storageName="taskevents")
 */
class TaskEvent
{
    const EVENT_CREATED = 'created';
    const EVENT_DELETED = 'close';
    const EVENT_CHANGE  = 'change';

    /** @KeyValueStore\Id */
    private $user;
    /** @KeyValueStore\Id */
    private $task;

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTime
     */
    private $eventDate;

    public function __construct(User $user, Task $task, $event, $message)
    {
        $this->user      = $user->getId();
        $this->task      = $task->getId();
        $this->event     = $event;
        $this->message   = $message;
        $this->eventDate = new \DateTime();
    }

    /**
     * Get event.
     *
     * @return event.
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get eventDate.
     *
     * @return eventDate.
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Get message.
     *
     * @return message.
     */
    public function getMessage()
    {
        return $this->message;
    }
}

