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

use Doctrine\DBAL\Connection;

/**
 * UUID Generator abstraction layer.
 */
class UUIDGenerator implements IdGenerator
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function generateId($object)
    {
        $platform = $this->conn->getDatabasePlatform();
        if ($platform->getName() == 'mssql') {
            return $this->conn->fetchColumn('SELECT NEWID()');
        }
        return $this->conn->fetchColumn('SELECT ' . $platform->getGuidExpression());
    }
}
