<?php

namespace App\Entity;

use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SemestreRepository")
 */
class Semestre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluation", mappedBy="semestre")
     */
    private $Evaluation;

    public function __construct()
    {
        $this->Evaluation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluation(): Collection
    {
        return $this->Evaluation;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->Evaluation->contains($evaluation)) {
            $this->Evaluation[] = $evaluation;
            $evaluation->setSemestre($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->Evaluation->contains($evaluation)) {
            $this->Evaluation->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getSemestre() === $this) {
                $evaluation->setSemestre(null);
            }
        }

        return $this;
    }
}
