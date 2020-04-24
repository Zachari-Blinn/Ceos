<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoterRepository")
 */
class Noter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="noters")
     */
    private $Eleve;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluation", inversedBy="noters")
     */
    private $Evaluation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->Note;
    }

    public function setNote(float $Note): self
    {
        $this->Note = $Note;

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

    public function getEvaluation(): ?Evaluation
    {
        return $this->Evaluation;
    }

    public function setEvaluation(?Evaluation $Evaluation): self
    {
        $this->Evaluation = $Evaluation;

        return $this;
    }
}
