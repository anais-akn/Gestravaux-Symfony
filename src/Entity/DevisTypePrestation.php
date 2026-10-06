<?php

namespace App\Entity;

use App\Repository\DevisTypePrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisTypePrestationRepository::class)]
class DevisTypePrestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'devisTypePrestations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DevisType $devisType = null;

    #[ORM\ManyToOne(inversedBy: 'devisTypePrestations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestataire $prestataire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getDevisType(): ?DevisType
    {
        return $this->devisType;
    }

    public function setDevisType(?DevisType $devisType): static
    {
        $this->devisType = $devisType;
        return $this;
    }

    public function getPrestataire(): ?Prestataire
    {
        return $this->prestataire;
    }

    public function setPrestataire(?Prestataire $prestataire): static
    {
        $this->prestataire = $prestataire;
        return $this;
    }
}