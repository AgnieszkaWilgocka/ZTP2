<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table('ztp_users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Role user
     *
     * @var string
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * Role admin
     *
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';


    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    #[Assert\NotBlank]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $password;

    #[ORM\OneToOne(targetEntity: UserData::class, cascade: ['persist', 'remove'])]
    private $userData;

    /**
     * Getter for id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for roles
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Setter for roles
     *
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;

    }

    /**
     * Getter for password
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setter for password
     *
     * @param string $password
     *
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Getter for User Data
     *
     * @return UserData|null
     */
    public function getUserData(): ?UserData
    {
        return $this->userData;
    }

    /**
     * Setter for User Data
     *
     * @param UserData|null $userData
     */
    public function setUserData(?UserData $userData): void
    {
        $this->userData = $userData;
    }
}
