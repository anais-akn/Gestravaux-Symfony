<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $type_document = null;

    #[ORM\Column(length: 500)]
    private ?string $chemin_fichier = null;

    #[ORM\Column]
    private ?\DateTime $date_upload = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Chantier $chantier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDocument(): ?string
    {
        return $this->type_document;
    }

    public function setTypeDocument(string $type_document): static
    {
        $this->type_document = $type_document;

        return $this;
    }

    public function getCheminFichier(): ?string
    {
        return $this->chemin_fichier;
    }

    public function setCheminFichier(string $chemin_fichier): static
    {
        $this->chemin_fichier = $chemin_fichier;

        return $this;
    }

    public function getDateUpload(): ?\DateTime
    {
        return $this->date_upload;
    }

    public function setDateUpload(\DateTime $date_upload): static
    {
        $this->date_upload = $date_upload;

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
}
