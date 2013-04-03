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

namespace WindowsAzure\TaskDemoBundle\Model;

/**
 * Generate an ID for a given object.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
interface IdGenerator
{
    function generateId($object);
}

