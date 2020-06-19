<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"listArticles"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listArticles"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listArticles"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"listArticles"})
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"listArticles"})
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"listArticles"})
     */
    private $updateDate;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"listArticles"})
     */
    private $createdUser;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"listArticles"})
     */
    private $updateUser;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listArticles"})
     */
    private $catalog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getCreatedUser(): ?int
    {
        return $this->createdUser;
    }

    public function setCreatedUser(int $createdUser): self
    {
        $this->createdUser = $createdUser;

        return $this;
    }

    public function getUpdateUser(): ?int
    {
        return $this->updateUser;
    }

    public function setUpdateUser(int $updateUser): self
    {
        $this->updateUser = $updateUser;

        return $this;
    }

    public function getCatalog(): ?Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(?Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }
}
