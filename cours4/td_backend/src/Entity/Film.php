<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\OneToOne(inversedBy: 'film_id', cascade: ['persist', 'remove'])]
    private ?Realisateur $realisateur_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getRealisateurId(): ?Realisateur
    {
        return $this->realisateur_id;
    }

    public function setRealisateurId(?Realisateur $realisateur_id): static
    {
        $this->realisateur_id = $realisateur_id;

        return $this;
    }
}
