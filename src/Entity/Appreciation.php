<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppreciationRepository")
 */
class Appreciation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="appreciations")
     */
    private $Eleve;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enseignement", inversedBy="Appreciation")
     */
    private $enseignement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(string $Commentaire): self
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->Eleve;
    }

    public function setEleve(?Eleve $Eleve): self
    {
        $this->Eleve = $Eleve;

        return $this;
    }

    public function getEnseignement(): ?Enseignement
    {
        return $this->enseignement;
    }

    public function setEnseignement(?Enseignement $enseignement): self
    {
        $this->enseignement = $enseignement;

        return $this;
    }
}
