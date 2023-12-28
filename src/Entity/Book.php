<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'books')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $authors;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorites')]
    private Collection $favoriteBy;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->favoriteBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function removeAllGenres(): static
    {
        $this->genres = new ArrayCollection();

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);

        return $this;
    }

    public function removeAllAuthors(): static
    {
        $this->authors = new ArrayCollection();

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoriteBy(): Collection
    {
        return $this->favoriteBy;
    }

    public function addFavoriteBy(User $favoriteBy): static
    {
        if (!$this->favoriteBy->contains($favoriteBy)) {
            $this->favoriteBy->add($favoriteBy);
            $favoriteBy->addFavorite($this);
        }

        return $this;
    }

    public function removeFavoriteBy(User $favoriteBy): static
    {
        if ($this->favoriteBy->removeElement($favoriteBy)) {
            $favoriteBy->removeFavorite($this);
        }

        return $this;
    }
}
