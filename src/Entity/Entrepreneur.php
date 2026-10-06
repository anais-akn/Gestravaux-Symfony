<?php

namespace App\Entity;

use App\Repository\EntrepreneurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepreneurRepository::class)]
class Entrepreneur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    #[ORM\Column(length: 250)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(length: 20)]
    private ?string $code_postal = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'entrepreneurs')]
    private Collection $categories;

    /**
     * @var Collection<int, Prestataire>
     */
    #[ORM\ManyToMany(targetEntity: Prestataire::class, mappedBy: 'entrepreneurs')]
    private Collection $prestataires;

    /**
     * @var Collection<int, DevisEntrepreneur>
     */
    #[ORM\OneToMany(targetEntity: DevisEntrepreneur::class, mappedBy: 'entrepreneur')]
    private Collection $devisEntrepreneurs;

    /**
     * @var Collection<int, DevisType>
     */
    #[ORM\ManyToMany(targetEntity: DevisType::class, mappedBy: 'entrepreneurs')]
    private Collection $devisTypes;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->prestataires = new ArrayCollection();
        $this->devisEntrepreneurs = new ArrayCollection();
        $this->devisTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

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
            $prestataire->addEntrepreneur($this);
        }

        return $this;
    }

    public function removePrestataire(Prestataire $prestataire): static
    {
        if ($this->prestataires->removeElement($prestataire)) {
            $prestataire->removeEntrepreneur($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DevisEntrepreneur>
     */
    public function getDevisEntrepreneurs(): Collection
    {
        return $this->devisEntrepreneurs;
    }

    public function addDevisEntrepreneur(DevisEntrepreneur $devisEntrepreneur): static
    {
        if (!$this->devisEntrepreneurs->contains($devisEntrepreneur)) {
            $this->devisEntrepreneurs->add($devisEntrepreneur);
            $devisEntrepreneur->setEntrepreneur($this);
        }

        return $this;
    }

    public function removeDevisEntrepreneur(DevisEntrepreneur $devisEntrepreneur): static
    {
        if ($this->devisEntrepreneurs->removeElement($devisEntrepreneur)) {
            // set the owning side to null (unless already changed)
            if ($devisEntrepreneur->getEntrepreneur() === $this) {
                $devisEntrepreneur->setEntrepreneur(null);
            }
        }

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
            $devisType->addEntrepreneur($this);
        }

        return $this;
    }

    public function removeDevisType(DevisType $devisType): static
    {
        if ($this->devisTypes->removeElement($devisType)) {
            $devisType->removeEntrepreneur($this);
        }

        return $this;
    }
}
