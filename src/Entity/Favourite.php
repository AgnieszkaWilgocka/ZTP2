<?php
/**
 * Favourite entity.
 */

namespace App\Entity;

use App\Repository\FavouriteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Favourite.
 */
#[ORM\Entity(repositoryClass: FavouriteRepository::class)]
#[ORM\Table(name: 'favourites')]
class Favourite
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
     * User
     *
     * @var User|null Author
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author;

    /**
     * Book.
     *
     * @var Book|null Book
     */
    #[ORM\ManyToOne(targetEntity: Book::class)]
    private ?Book $book;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for author.
     *
     * @return User|null Author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param User|null $author Author
     *
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * Getter for book.
     *
     * @return Book|null Book
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * Setter for book.
     *
     * @param Book|null $book Book
     *
     */
    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
