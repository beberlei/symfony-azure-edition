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
use Symfony\Component\Security\Core\User\UserInterface;
use WindowsAzure\TaskDemoBundle\Model\BaseObject;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="WindowsAzure\TaskDemoBundle\Repository\EntityRepository")
 * @UniqueEntity(fields={"username"})
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id @ORM\Column(type="string")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $username;
    /**
     * @ORM\Column(type="string")
     */
    protected $password;
    /**
     * @ORM\Column(type="string")
     */
    protected $salt;
    /**
     * @ORM\Column(type="string")
     */
    protected $roles;

    public function __construct($id)
    {
        $this->id = $id;
        $this->salt = md5(microtime(true));
        $this->roles = "ROLE_USER";
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
        return explode(",", $this->roles);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function equals(UserInterface $user)
    {
        return $user->getId() === $this->getId();
    }
}

