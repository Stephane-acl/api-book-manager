<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LibraryRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "GET"={
 *              "method"="GET",
 *              "normalization_context"={
 *                  "groups"={"get_libraries"}
 *              }},
 *          "POST"},
 *     itemOperations={
 *          "GET"={
 *              "method"="GET",
 *              "normalization_context"={
 *                  "groups"={"get_library"}
 *              }}, "PUT", "DELETE"},
 * attributes={"pagination_client_enabled"=false},
 * )
 * @ApiFilter(SearchFilter::class, properties={"label":"partial"})
 */
class Library
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_library", "get_libraries"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_library", "get_libraries", "get_books"})
     * @Assert\NotBlank(message="Label of the library is required")
     * @Assert\Length(min=3, minMessage="The label must have between 3 and 255 characters", max=255, *
     *     maxMessage="The label must have between 3 and 255 characters")
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_library", "get_libraries", "get_books"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_library", "get_libraries", "get_books"})
     */
    private $cpo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_library", "get_libraries", "get_books"})
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get_library", "get_libraries", "get_books"})
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="library")
     * @Groups({"get_library", "get_libraries"})
     */
    private $book;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="library")
     * @Groups({"get_library", "get_libraries"})
     */
    private $user;

    public function __construct()
    {
        $this->book = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCpo(): ?string
    {
        return $this->cpo;
    }

    public function setCpo(?string $cpo): self
    {
        $this->cpo = $cpo;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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
            $book->setLibrary($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getLibrary() === $this) {
                $book->setLibrary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setLibrary($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getLibrary() === $this) {
                $user->setLibrary(null);
            }
        }

        return $this;
    }
}
