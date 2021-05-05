<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\BookRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ApiResource(
 *   attributes = {
 *       "order": {"createdAt":"desc"}
 *   },
 *     iri="http://schema.org/Book",
 *     normalizationContext={
 *         "groups"={"media_book_read"}
 *     },
 *
 *     collectionOperations={
 *         "POST"={
 *             "controller"="App\Controller\CreateBookActionController",
 *             "deserialize"=false,
 *             "security"="is_granted('ROLE_USER')",
 *             "validation_groups"={"Default", "media_book_create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "GET"
 *     },
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
 * @ApiFilter(SearchFilter::class, properties={"title":"partial", "isAvailable":"exact"})
 * @ApiFilter(OrderFilter::class)
 * @Vich\Uploadable
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_book", "get_books", "get_authors", "get_categories"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_book", "get_books", "get_authors", "get_libraries", "get_categories"})
     * @Assert\Length(min=3, minMessage="Le titre doit avoir au minimum 3 caractères",
     *     max=255, maxMessage="Le titre doit avoir au maximum 255 caractères")
     * @Assert\NotBlank(message="Le titre du livre est obligatoire")
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
     */
    private $nbrPages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_book", "get_books", "get_authors", "get_categories"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_book", "get_books", "get_libraries"})
     * @Assert\NotNull(message="La disponibilité du livre est obligatoire")
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
     * @ORM\Column(type="datetime", nullable=true)
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

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"media_book_read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     *
     * @Groups({"media_book_create"})
     * @Vich\UploadableField(mapping="media_book", fileNameProperty="filePath")
     */
    public $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_book", "get_books"})
     */
    private $filePath;

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

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): self
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(File $file = null): Book
    {
        $this->file = $file;
        if ($file) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }
}
