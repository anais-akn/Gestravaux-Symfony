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

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Entrepreneur>
     */
    #[ORM\ManyToMany(targetEntity: Entrepreneur::class, mappedBy: 'categories')]
    private Collection $entrepreneurs;

    /**
     * @var Collection<int, Prestataire>
     */
    #[ORM\OneToMany(targetEntity: Prestataire::class, mappedBy: 'categorie')]
    private Collection $prestataires;

    public function __construct()
    {
        $this->entrepreneurs = new ArrayCollection();
        $this->prestataires = new ArrayCollection();
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
            $entrepreneur->addCategory($this);
        }

        return $this;
    }

    public function removeEntrepreneur(Entrepreneur $entrepreneur): static
    {
        if ($this->entrepreneurs->removeElement($entrepreneur)) {
            $entrepreneur->removeCategory($this);
        }

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
            $prestataire->setCategorie($this);
        }

        return $this;
    }

    public function removePrestataire(Prestataire $prestataire): static
    {
        if ($this->prestataires->removeElement($prestataire)) {
            // set the owning side to null (unless already changed)
            if ($prestataire->getCategorie() === $this) {
                $prestataire->setCategorie(null);
            }
        }

        return $this;
    }
}
