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

use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\DBAL\Sharding\SQLAzure\SQLAzureShardManager;
use WindowsAzure\TaskDemoBundle\Entity\User;

/**
 * Runs after security, picks the user from the session and then changes the
 * federation based on the user-id.
 *
 * If you are building a multi-tenant app you will need do such a thing based on the
 * "tenant_id". In our case its the user-id, but it might be a customer-name,
 * company-id, blog-id, website-id or something simliar of "global" scope to
 * your application.
 */
class UserFederationListener
{
    /**
     * @var SecurityContextInterface
     */
    private $security;

    /**
     * @var ShardManager
     */
    private $shardManager;

    public function __construct(SecurityContextInterface $security, SQLAzureShardManager $shardManager = null)
    {
        $this->security = $security;
        $this->shardManager = $shardManager;
    }

    /**
     * Kernel Request Event
     */
    public function onKernelRequest($event)
    {
        if ( ! $this->shardManager) {
            // stop for the non-sharded application
            return;
        }

        $token = $this->security->getToken();
        if ( ! $token) {
            return;
        }

        if ( ! $token->isAuthenticated()) {
            return;
        }

        $user = $token->getUser();

        if ( ! ($user instanceof User)) {
            return;
        }

        $this->shardManager->selectShard($user->getId());
        // TODO: Detach user from EntityManager
    }
}

