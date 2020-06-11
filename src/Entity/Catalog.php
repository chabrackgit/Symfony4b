<?php

namespace App\Entity;

use App\Repository\CatalogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatalogRepository::class)
 */
class Catalog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdUser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateUser;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="catalog")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

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

    public function getCreatedUser(): ?\DateTimeInterface
    {
        return $this->createdUser;
    }

    public function setCreatedUser(\DateTimeInterface $createdUser): self
    {
        $this->createdUser = $createdUser;

        return $this;
    }

    public function getUpdateUser(): ?\DateTimeInterface
    {
        return $this->updateUser;
    }

    public function setUpdateUser(\DateTimeInterface $updateUser): self
    {
        $this->updateUser = $updateUser;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCatalog($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCatalog() === $this) {
                $article->setCatalog(null);
            }
        }

        return $this;
    }
}
