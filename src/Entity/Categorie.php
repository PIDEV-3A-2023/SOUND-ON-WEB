<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?int $visiteur = null;

    #[ORM\Column(nullable: true)]
    private ?int $starCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

    #[ORM\OneToMany(mappedBy: 'idCategorie', targetEntity: Musique::class, orphanRemoval: true)]
    private Collection $musiques;

    public function __construct()
    {
        $this->musiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVisiteur(): ?int
    {
        return $this->visiteur;
    }

    public function setVisiteur(?int $visiteur): self
    {
        $this->visiteur = $visiteur;

        return $this;
    }

    public function getStarCount(): ?int
    {
        return $this->starCount;
    }

    public function setStarCount(?int $starCount): self
    {
        $this->starCount = $starCount;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Collection<int, Musique>
     */
    public function getMusiques(): Collection
    {
        return $this->musiques;
    }

    public function addMusique(Musique $musique): self
    {
        if (!$this->musiques->contains($musique)) {
            $this->musiques->add($musique);
            $musique->setIdCategorie($this);
        }

        return $this;
    }

    public function removeMusique(Musique $musique): self
    {
        if ($this->musiques->removeElement($musique)) {
            // set the owning side to null (unless already changed)
            if ($musique->getIdCategorie() === $this) {
                $musique->setIdCategorie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ?? ''; // assuming that 'nom' property contains the string representation of the object
    }
}
