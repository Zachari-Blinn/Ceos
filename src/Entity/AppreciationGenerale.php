<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppreciationGeneraleRepository")
 */
class AppreciationGenerale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $CommentaireGeneral;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="appreciationGenerales")
     */
    private $eleve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaireGeneral(): ?string
    {
        return $this->CommentaireGeneral;
    }

    public function setCommentaireGeneral(string $CommentaireGeneral): self
    {
        $this->CommentaireGeneral = $CommentaireGeneral;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }
}
