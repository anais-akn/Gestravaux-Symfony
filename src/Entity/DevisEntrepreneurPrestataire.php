<?php

namespace App\Entity;

use App\Repository\DevisEntrepreneurPrestataireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisEntrepreneurPrestataireRepository::class)]
class DevisEntrepreneurPrestataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix_unitaire = null;

    #[ORM\ManyToOne(inversedBy: 'devisEntrepreneurPrestataires')]
    private ?DevisEntrepreneur $devis_entrepeneur = null;

    #[ORM\ManyToOne(inversedBy: 'devisEntrepreneurPrestataires')]
    private ?Prestataire $prestation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(string $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;

        return $this;
    }

    public function getDevisEntrepeneur(): ?DevisEntrepreneur
    {
        return $this->devis_entrepeneur;
    }

    public function setDevisEntrepeneur(?DevisEntrepreneur $devis_entrepeneur): static
    {
        $this->devis_entrepeneur = $devis_entrepeneur;

        return $this;
    }

    public function getPrestation(): ?Prestataire
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestataire $prestation): static
    {
        $this->prestation = $prestation;

        return $this;
    }
}
