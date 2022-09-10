<?php
/**
 * Comment entity
 */
namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment
 *
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'ztp_comments')]
class Comment
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
     * Content
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    private ?string $content;

    /**
     * Book entity
     *
     * @var Book|null
     */
    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'comments')]
    private ?Book $book;

    /**
     * User entity
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author;

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
     * Getter for content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content
     *
     * @param string $content
     *
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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
     * @return $this
     */
    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
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
}
