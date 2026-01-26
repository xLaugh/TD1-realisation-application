<?php

namespace App\Entity;

use App\Repository\RealisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealisateurRepository::class)]
class Realisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToOne(mappedBy: 'realisateur_id', cascade: ['persist', 'remove'])]
    private ?Film $film_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFilmId(): ?Film
    {
        return $this->film_id;
    }

    public function setFilmId(?Film $film_id): static
    {
        // unset the owning side of the relation if necessary
        if ($film_id === null && $this->film_id !== null) {
            $this->film_id->setRealisateurId(null);
        }

        // set the owning side of the relation if necessary
        if ($film_id !== null && $film_id->getRealisateurId() !== $this) {
            $film_id->setRealisateurId($this);
        }

        $this->film_id = $film_id;

        return $this;
    }
}
