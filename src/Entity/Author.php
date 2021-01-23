<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"get_authors"}},
 *     attributes = {
 *       "order": {"createdAt":"desc"}
 *    },
 * )
 * @ApiFilter(SearchFilter::class, properties={"firstName":"partial","lastName":"partial"})
 */
class Author
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_authors", "get_book", "get_books"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_authors", "get_book", "get_books"})
     * @Assert\Length(min="3", minMessage="Le prénom de l'auteur doit faire au minimum 3 caractères", max="255",
     *     maxMessage="Le prénom de l'auteur doit faire au maximum 255 caractères")
     * @Assert\NotBlank(message="Le prénom de l'auteur est obligatoire")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_authors", "get_book", "get_books"})
     * @Assert\Length(min="3", minMessage="Le nom de l'auteur doit faire au minimum 3 caractères", max="255",
     *     maxMessage="Le nom de l'auteur doit faire au maximum 255 caractères")
     * @Assert\NotBlank(message="Le nom de l'auteur est obligatoire")
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"get_authors", "get_book", "get_books"})
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_authors", "get_book", "get_books"})
     * @Assert\Length(min="2", minMessage="La nationnalité de l'auteur doit faire au minimum 2 caractères", max="255",
     *     maxMessage="La nationnalité de l'auteur doit faire au maximum 255 caractères")
     * @Assert\NotBlank(message="La nationnalité de l'auteur est obligatoire")
     */
    private $nationality;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_authors", "get_book", "get_books"})
     * @Assert\Type( type = "\DateTime",message="The format must be YYYY-MM-DD")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="author")
     * @Groups({"get_authors"})
     */
    private $book;

    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

    /**
     * Return the number of books of each authors
     * @Groups({"get_authors"})
     * @return integer
     */
    public function getTotalOfBook() : int {

        return array_reduce($this->book->toArray(), function ($total, $book) {
            $books[] = $book;
            return $total + count($books);
        }, 0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }
}
