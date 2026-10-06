<?php

namespace App\Entity;

use App\Repository\DevisEntrepreneurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisEntrepreneurRepository::class)]
class DevisEntrepreneur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date_debut = null;

    #[ORM\Column]
    private ?int $duree_estimee_jour = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'devisEntrepreneurs')]
    private ?Entrepreneur $entrepreneur = null;

    #[ORM\ManyToOne(inversedBy: 'devisEntrepreneurs')]
    private ?Chantier $chantier = null;

    /**
     * @var Collection<int, DevisEntrepreneurPrestataire>
     */
    #[ORM\OneToMany(targetEntity: DevisEntrepreneurPrestataire::class, mappedBy: 'devis_entrepeneur')]
    private Collection $devisEntrepreneurPrestataires;

    public function __construct()
    {
        $this->devisEntrepreneurPrestataires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDureeEstimeeJour(): ?int
    {
        return $this->duree_estimee_jour;
    }

    public function setDureeEstimeeJour(int $duree_estimee_jour): static
    {
        $this->duree_estimee_jour = $duree_estimee_jour;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEntrepreneur(): ?Entrepreneur
    {
        return $this->entrepreneur;
    }

    public function setEntrepreneur(?Entrepreneur $entrepreneur): static
    {
        $this->entrepreneur = $entrepreneur;

        return $this;
    }

    public function getChantier(): ?Chantier
    {
        return $this->chantier;
    }

    public function setChantier(?Chantier $chantier): static
    {
        $this->chantier = $chantier;

        return $this;
    }

    /**
     * @return Collection<int, DevisEntrepreneurPrestataire>
     */
    public function getDevisEntrepreneurPrestataires(): Collection
    {
        return $this->devisEntrepreneurPrestataires;
    }

    public function addDevisEntrepreneurPrestataire(DevisEntrepreneurPrestataire $devisEntrepreneurPrestataire): static
    {
        if (!$this->devisEntrepreneurPrestataires->contains($devisEntrepreneurPrestataire)) {
            $this->devisEntrepreneurPrestataires->add($devisEntrepreneurPrestataire);
            $devisEntrepreneurPrestataire->setDevisEntrepeneur($this);
        }

        return $this;
    }

    public function removeDevisEntrepreneurPrestataire(DevisEntrepreneurPrestataire $devisEntrepreneurPrestataire): static
    {
        if ($this->devisEntrepreneurPrestataires->removeElement($devisEntrepreneurPrestataire)) {
            // set the owning side to null (unless already changed)
            if ($devisEntrepreneurPrestataire->getDevisEntrepeneur() === $this) {
                $devisEntrepreneurPrestataire->setDevisEntrepeneur(null);
            }
        }

        return $this;
    }
}
