<?php
/**
 * Favourite entity
 */
namespace App\Entity;

use App\Repository\FavouriteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Favourite
 */
#[ORM\Entity(repositoryClass: FavouriteRepository::class)]
#[ORM\Table(name: 'favourites')]
class Favourite
{
    /**
     * Primary key
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * User entity
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author;

    /**
     * Book entity
     *
     * @var Book|null
     */
    #[ORM\ManyToOne(targetEntity: Book::class)]
    private ?Book $book;

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
     * Getter for author
     *
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author
     *
     * @param User|null $author
     *
     * @return void
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * Getter for book
     *
     * @return Book|null
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * Setter for book
     *
     * @param Book|null $book
     *
     * @return void
     */
    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
