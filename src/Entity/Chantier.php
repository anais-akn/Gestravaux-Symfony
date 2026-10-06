<?php

namespace App\Entity;

use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierRepository::class)]
class Chantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_validation = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'chantiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bien $bien = null;

    #[ORM\ManyToOne(inversedBy: 'chantiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inspecteur $Inspecteur = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'chantier')]
    private Collection $photos;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'chantier')]
    private Collection $documents;

    /**
     * @var Collection<int, DevisEntrepreneur>
     */
    #[ORM\OneToMany(targetEntity: DevisEntrepreneur::class, mappedBy: 'chantier')]
    private Collection $devisEntrepreneurs;

    /**
     * @var Collection<int, DevisType>
     */
    #[ORM\OneToMany(targetEntity: DevisType::class, mappedBy: 'chantier')]
    private Collection $devisTypes;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->devisEntrepreneurs = new ArrayCollection();
        $this->devisTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateValidation(): ?\DateTime
    {
        return $this->date_validation;
    }

    public function setDateValidation(?\DateTime $date_validation): static
    {
        $this->date_validation = $date_validation;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(?Bien $bien): static
    {
        $this->bien = $bien;

        return $this;
    }

    public function getInspecteur(): ?Inspecteur
    {
        return $this->Inspecteur;
    }

    public function setInspecteur(?Inspecteur $Inspecteur): static
    {
        $this->Inspecteur = $Inspecteur;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setChantier($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getChantier() === $this) {
                $photo->setChantier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setChantier($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getChantier() === $this) {
                $document->setChantier(null);
            }
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
            $devisEntrepreneur->setChantier($this);
        }

        return $this;
    }

    public function removeDevisEntrepreneur(DevisEntrepreneur $devisEntrepreneur): static
    {
        if ($this->devisEntrepreneurs->removeElement($devisEntrepreneur)) {
            // set the owning side to null (unless already changed)
            if ($devisEntrepreneur->getChantier() === $this) {
                $devisEntrepreneur->setChantier(null);
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
            $devisType->setChantier($this);
        }

        return $this;
    }

    public function removeDevisType(DevisType $devisType): static
    {
        if ($this->devisTypes->removeElement($devisType)) {
            // set the owning side to null (unless already changed)
            if ($devisType->getChantier() === $this) {
                $devisType->setChantier(null);
            }
        }

        return $this;
    }
}
