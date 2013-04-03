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

namespace WindowsAzure\TaskDemoBundle\Repository;

use WindowsAzure\TaskDemoBundle\Entity\User;

class TaskRepository extends EntityRepository
{
    public function findByDueDate(User $user)
    {
        return $this->findBy(
            array('user' => $user->getId()),
            array('dueDate' => 'ASC')
        );
    }
}

