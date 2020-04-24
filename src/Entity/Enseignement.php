<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnseignementRepository")
 */
class Enseignement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluation", mappedBy="enseignement")
     */
    private $Evaluation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appreciation", mappedBy="enseignement")
     */
    private $Appreciation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classe", inversedBy="Enseignement")
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matiere", inversedBy="Enseignement")
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prof", inversedBy="Enseignement")
     */
    private $prof;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    public function __construct()
    {
        $this->Evaluation = new ArrayCollection();
        $this->Appreciation = new ArrayCollection();
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
            $evaluation->setEnseignement($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->Evaluation->contains($evaluation)) {
            $this->Evaluation->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getEnseignement() === $this) {
                $evaluation->setEnseignement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Appreciation[]
     */
    public function getAppreciation(): Collection
    {
        return $this->Appreciation;
    }

    public function addAppreciation(Appreciation $appreciation): self
    {
        if (!$this->Appreciation->contains($appreciation)) {
            $this->Appreciation[] = $appreciation;
            $appreciation->setEnseignement($this);
        }

        return $this;
    }

    public function removeAppreciation(Appreciation $appreciation): self
    {
        if ($this->Appreciation->contains($appreciation)) {
            $this->Appreciation->removeElement($appreciation);
            // set the owning side to null (unless already changed)
            if ($appreciation->getEnseignement() === $this) {
                $appreciation->setEnseignement(null);
            }
        }

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getProf(): ?Prof
    {
        return $this->prof;
    }

    public function setProf(?Prof $prof): self
    {
        $this->prof = $prof;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
