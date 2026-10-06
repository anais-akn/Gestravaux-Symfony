<?php

namespace App\Entity;

use App\Repository\PrestataireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestataireRepository::class)]
class Prestataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix_base = null;

    #[ORM\ManyToOne(inversedBy: 'prestataires')]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, Entrepreneur>
     */
    #[ORM\ManyToMany(targetEntity: Entrepreneur::class, inversedBy: 'prestataires')]
    private Collection $entrepreneurs;

    /**
     * @var Collection<int, DevisType>
     */
    #[ORM\ManyToMany(targetEntity: DevisType::class, mappedBy: 'prestataires')]
    private Collection $devisTypes;

    /**
     * @var Collection<int, DevisEntrepreneurPrestataire>
     */
    #[ORM\OneToMany(targetEntity: DevisEntrepreneurPrestataire::class, mappedBy: 'prestation')]
    private Collection $devisEntrepreneurPrestataires;

    /**
     * @var Collection<int, DevisTypePrestation>
     */
    #[ORM\OneToMany(targetEntity: DevisTypePrestation::class, mappedBy: 'prestataire', orphanRemoval: true)]
    private Collection $devisTypePrestations;

    public function __construct()
    {
        $this->entrepreneurs = new ArrayCollection();
        $this->devisTypes = new ArrayCollection();
        $this->devisEntrepreneurPrestataires = new ArrayCollection();
        $this->devisTypePrestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixBase(): ?string
    {
        return $this->prix_base;
    }

    public function setPrixBase(string $prix_base): static
    {
        $this->prix_base = $prix_base;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

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
     * @return Collection<int, DevisType>
     */
    public function getDevisTypes(): Collection
    {
        return $this->devisTypes;
    }

    public function addDevisType(DevisType $devisType): static
    {
        if (!$this->devisTypes->contains($devisType)) {
            $this->devisTypes->add($devisType);
            $devisType->addPrestataire($this);
        }

        return $this;
    }

    public function removeDevisType(DevisType $devisType): static
    {
        if ($this->devisTypes->removeElement($devisType)) {
            $devisType->removePrestataire($this);
        }

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
            $devisEntrepreneurPrestataire->setPrestation($this);
        }

        return $this;
    }

    public function removeDevisEntrepreneurPrestataire(DevisEntrepreneurPrestataire $devisEntrepreneurPrestataire): static
    {
        if ($this->devisEntrepreneurPrestataires->removeElement($devisEntrepreneurPrestataire)) {
            // set the owning side to null (unless already changed)
            if ($devisEntrepreneurPrestataire->getPrestation() === $this) {
                $devisEntrepreneurPrestataire->setPrestation(null);
            }
        }

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
            $devisTypePrestation->setPrestataire($this);
        }

        return $this;
    }

    public function removeDevisTypePrestation(DevisTypePrestation $devisTypePrestation): static
    {
        if ($this->devisTypePrestations->removeElement($devisTypePrestation)) {
            // set the owning side to null (unless already changed)
            if ($devisTypePrestation->getPrestataire() === $this) {
                $devisTypePrestation->setPrestataire(null);
            }
        }

        return $this;
    }
}
