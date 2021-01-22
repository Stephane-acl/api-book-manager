<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ApiResource(
 *   attributes = {
 *       "order": {"dateOfPublication":"desc"}
 *   },
 *
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={
 *          "GET"={
 *              "method"="GET",
 *              "normalization_context"={
 *                  "groups"={"get_book"}
 *              }
 *           },
 *          "PUT"
 *   },
 *     normalizationContext={"groups"={"get_books"}},
 *     denormalizationContext={"disable_type_enforcement"=true}
 * )
 * @ApiFilter(SearchFilter::class, properties={"title":"partial"})
 * @ApiFilter(OrderFilter::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_book", "get_books"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_book", "get_books", "get_authors", "get_libraries"})
     * @Assert\NotBlank(message="Title of the book is required")
     * @Assert\Length(min=3, minMessage="The title must have between 3 and 255 characters", max=255, *
     *     maxMessage="The title must have between 3 and 255 characters")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"get_book", "get_books", "get_libraries"})
     */
    private $language;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"get_book", "get_books"})
     * @Assert\Type( type = "\DateTime",message="The format must be YYYY-MM-DD")
     */
    private $dateOfPublication;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get_book", "get_books"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"get_book", "get_books", "get_libraries"})
     * @Assert\Type(type="numeric", message="The number of pages must be numeric")
     */
    private $nbrPages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_book", "get_books", "get_authors"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_book", "get_books", "get_libraries"})
     * @Assert\NotBlank(message="Availability of the book is required")
     */
    private $isAvailable;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_book", "get_books"})
     * @Assert\NotBlank(message="created date of the book is required")
     * @Assert\Type( type = "\DateTime",message="The format must be YYYY-MM-DD")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_book", "get_books"})
     * @Assert\Type( type = "\DateTime",message="The format must be YYYY-MM-DD")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="book")
     * @Groups({"get_book", "get_books"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="book")
     * @Groups({"get_book", "get_books"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Library::class, inversedBy="book")
     * @Groups({"get_book", "get_books"})
     * @Assert\NotBlank(message="A library is required")
     */
    private $library;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getDateOfPublication(): ?\DateTimeInterface
    {
        return $this->dateOfPublication;
    }

    public function setDateOfPublication ($dateOfPublication): self
    {
        $this->dateOfPublication = $dateOfPublication;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbrPages(): ?int
    {
        return $this->nbrPages;
    }

    public function setNbrPages($nbrPages): self
    {
        $this->nbrPages = $nbrPages;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): self
    {
        $this->library = $library;

        return $this;
    }
}
