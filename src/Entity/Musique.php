<?php

namespace App\Entity;

use App\Repository\MusiqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MusiqueRepository::class)]
class Musique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("musiques")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 1,
        max: 20,
        minMessage: 'Le longueur du titre doit {{ limit }} au minimum',
        maxMessage: 'Le longueur du titre doit {{ limit }} au maximum',
    )]
    #[Groups("musiques")]
    private ?string $nom = null;
    
    #[ORM\Column(length: 255)]
    #[Groups("musiques")]
    private ?string $chemin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("musiques")]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255)]
    #[Groups("musiques")]
    private ?string $longueur = null;

    #[ORM\ManyToOne(inversedBy: 'musiques')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("musiques")]
    private ?Utilisateur $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'musiques')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("musiques")]
    private ?Categorie $idCategorie = null;

    #[ORM\ManyToOne(inversedBy: 'musiques')]
    #[Groups("musiques")]
    private ?Album $idAlbum = null;

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

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): self
    {
        $this->chemin = $chemin;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getLongueur(): ?string
    {
        return $this->longueur;
    }

    public function setLongueur(string $longueur): self
    {
        $this->longueur = $longueur;

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

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    public function getIdAlbum(): ?Album
    {
        return $this->idAlbum;
    }

    public function setIdAlbum(?Album $idAlbum): self
    {
        $this->idAlbum = $idAlbum;

        return $this;
    }
}
