<?php
namespace TerraGana\AppBundle\Security;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface, ContainerAwareInterface
{

    /** @var  ManagerRegistry */
    protected $dm;

    public function getUsernameForApiKey($apiKey)
    {
        /* @var User */
        $user = $this->dm->getRepository('TerraGanaAppBundle:User')->findOneBy(['apiToken.token'=>$apiKey]);

        $username = $user->getUsername();

        return $username;
    }

    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            null,
            array('ROLE_API')
        );
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->dm = $container->get('doctrine_mongodb')->getManager();
    }
}