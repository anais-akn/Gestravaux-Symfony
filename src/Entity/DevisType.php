<?php

namespace App\Entity;

use App\Repository\DevisTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisTypeRepository::class)]
class DevisType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $intitule = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    #[ORM\ManyToOne(inversedBy: 'devisTypes')]
    private ?Chantier $chantier = null;

    /**
     * @var Collection<int, Prestataire>
     */
    #[ORM\ManyToMany(targetEntity: Prestataire::class, inversedBy: 'devisTypes')]
    private Collection $prestataires;

    /**
     * @var Collection<int, Entrepreneur>
     */
    #[ORM\ManyToMany(targetEntity: Entrepreneur::class, inversedBy: 'devisTypes')]
    private Collection $entrepreneurs;

    /**
     * @var Collection<int, DevisTypePrestation>
     */
    #[ORM\OneToMany(targetEntity: DevisTypePrestation::class, mappedBy: 'devisType')]
    private Collection $devisTypePrestations;

    public function __construct()
    {
        $this->prestataires = new ArrayCollection();
        $this->entrepreneurs = new ArrayCollection();
        $this->devisTypePrestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTime $date_creation): static
    {
        $this->date_creation = $date_creation;

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
     * @return Collection<int, Prestataire>
     */
    public function getPrestataires(): Collection
    {
        return $this->prestataires;
    }

    public function addPrestataire(Prestataire $prestataire): static
    {
        if (!$this->prestataires->contains($prestataire)) {
            $this->prestataires->add($prestataire);
        }

        return $this;
    }

    public function removePrestataire(Prestataire $prestataire): static
    {
        $this->prestataires->removeElement($prestataire);

        return $this;
    }

    /**
     * @return Collection<int, Entrepreneur>
     */
    public function getEntrepreneurs(): Collection
    {
        return $this->entrepreneurs;
    }

    public function addEntrepreneur(Entrepreneur $entrepreneur): static
    {
        if (!$this->entrepreneurs->contains($entrepreneur)) {
            $this->entrepreneurs->add($entrepreneur);
        }

        return $this;
    }

    public function removeEntrepreneur(Entrepreneur $entrepreneur): static
    {
        $this->entrepreneurs->removeElement($entrepreneur);

        return $this;
    }

    /**
     * @return Collection<int, DevisTypePrestation>
     */
    public function getDevisTypePrestations(): Collection
    {
        return $this->devisTypePrestations;
    }

    public function addDevisTypePrestation(DevisTypePrestation $devisTypePrestation): static
    {
        if (!$this->devisTypePrestations->contains($devisTypePrestation)) {
            $this->devisTypePrestations->add($devisTypePrestation);
            $devisTypePrestation->addDevisType($this);
        }

        return $this;
    }

    public function removeDevisTypePrestation(DevisTypePrestation $devisTypePrestation): static
    {
        if ($this->devisTypePrestations->removeElement($devisTypePrestation)) {
            $devisTypePrestation->removeDevisType($this);
        }

        return $this;
    }
}
