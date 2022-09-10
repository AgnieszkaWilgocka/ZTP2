<?php
/**
 * UserData entity.
 */

namespace App\Entity;

use App\Repository\UserrDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserData.
 */
#[ORM\Entity(repositoryClass: UserrDataRepository::class)]
#[ORM\Table(name: 'ztp_users_data')]
class UserData
{
    /**
     * Primary key.
     *
     * @var int|null Id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * Nick.
     *
     * @var string|null Nick
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $nick;

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for nick.
     *
     * @return string|null Nick
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Setter for nick.
     *
     * @param string|null $nick Nick
     */
    public function setNick(?string $nick): void
    {
        $this->nick = $nick;
    }
}
