<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvaluationRepository")
 */
class Evaluation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, nullable=false)
     */
    private $Libelle;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $DateEval;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "You must be at least {{ limit }}cm tall to enter",
     *      maxMessage = "You cannot be taller than {{ limit }}cm to enter"
     * )
     */
    private $Coef;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Noter", mappedBy="Evaluation")
     */
    private $noters;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Semestre", inversedBy="Evaluation")
     */
    private $semestre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enseignement", inversedBy="Evaluation")
     */
    private $enseignement;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive
     */
    private $notation;

    public function __construct()
    {
        $this->noters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getDateEval(): ?\DateTimeInterface
    {
        return $this->DateEval;
    }

    public function setDateEval(\DateTimeInterface $DateEval): self
    {
        $this->DateEval = $DateEval;

        return $this;
    }

    public function getCoef(): ?int
    {
        return $this->Coef;
    }

    public function setCoef(int $Coef): self
    {
        $this->Coef = $Coef;

        return $this;
    }

    /**
     * @return Collection|Noter[]
     */
    public function getNoters(): Collection
    {
        return $this->noters;
    }

    public function addNoter(Noter $noter): self
    {
        if (!$this->noters->contains($noter)) {
            $this->noters[] = $noter;
            $noter->setEvaluation($this);
        }

        return $this;
    }

    public function removeNoter(Noter $noter): self
    {
        if ($this->noters->contains($noter)) {
            $this->noters->removeElement($noter);
            // set the owning side to null (unless already changed)
            if ($noter->getEvaluation() === $this) {
                $noter->setEvaluation(null);
            }
        }

        return $this;
    }

    public function getSemestre(): ?Semestre
    {
        return $this->semestre;
    }

    public function setSemestre(?Semestre $semestre): self
    {
        $this->semestre = $semestre;

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

    public function __toString():string
    {
        return $this->Libelle;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(?int $notation): self
    {
        $this->notation = $notation;

        return $this;
    }

}
