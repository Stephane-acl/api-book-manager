<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *   attributes = {
 *       "order": {"createdAt":"desc"}
 *   },
 *     normalizationContext={"groups"={"get_categories"}},
 * )
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"name":"partial"})
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_categories","get_book", "get_books"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La catégorie est obligatoire")
     * @Assert\Length(min=3, minMessage="La catégorie doit avoir au minimum 3 caractères",
     *     max=255, maxMessage="La catégorie doit avoir au maximum 255 caractères")
     * @Groups({"get_categories", "get_book","get_books"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="category")
     * @Groups({"get_categories"})
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=Library::class, inversedBy="categories")
     */
    private $library;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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
            $book->setCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getCategory() === $this) {
                $book->setCategory(null);
            }
        }

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
