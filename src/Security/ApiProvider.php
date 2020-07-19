<?php

namespace App\Security;

use App\Security\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

/**
 * API Provider.
 */
class ApiProvider implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->token);
    }

    /**
     * Load user.
     */
    public function loadUserByUsername($token)
    {
        $credentials = Yaml::parseFile(__DIR__.'/credentials.yaml');

        if (isset($credentials[$token])) {
            $data = $credentials[$token];
            $data['token'] = $token;
            
            return User::create($data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
