<?php

namespace App\Security;

use App\AccessPropertyTrait;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User implements from oauth.
 */
class User implements UserInterface
{
    use AccessPropertyTrait;
    
    /**
     * @var string
     */
    public $id;
    
    /**
     * @var array
     */
    public $roles;
    
    /**
     * @var string
     */
    public $email;
    
    /**
     * @var string
     */
    public $token;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * Static Creator.
     */
    public static function create(array $data): User
    {
        $user = new User();
        foreach ($data as $attr => $value) {
            $user->{$attr} = $value;
        }

        return $user;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return '';
    }
    
    public function getSalt(): ?string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    
    /**
     * Object to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->email,
            'enabled' => $this->isEnabled(),
        ];
    }
}
