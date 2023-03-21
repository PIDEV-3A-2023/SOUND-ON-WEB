<?php

namespace App\Entity;

use App\Repository\FavoriRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriRepository::class)]
class Favori
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFavori = null;

    #[ORM\ManyToOne(inversedBy: 'favoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Musique $idMusique = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFavori(): ?\DateTimeInterface
    {
        return $this->dateFavori;
    }

    public function setDateFavori(\DateTimeInterface $dateFavori): self
    {
        $this->dateFavori = $dateFavori;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdMusique(): ?Musique
    {
        return $this->idMusique;
    }

    public function setIdMusique(?Musique $idMusique): self
    {
        $this->idMusique = $idMusique;

        return $this;
    }
}
