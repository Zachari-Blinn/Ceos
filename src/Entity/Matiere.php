<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatiereRepository")
 */
class Matiere
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
    private $NomMatiere;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enseignement", mappedBy="matiere")
     */
    private $Enseignement;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    public function __construct()
    {
        $this->Enseignement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMatiere(): ?string
    {
        return $this->NomMatiere;
    }

    public function setNomMatiere(string $NomMatiere): self
    {
        $this->NomMatiere = $NomMatiere;

        return $this;
    }

    /**
     * @return Collection|Enseignement[]
     */
    public function getEnseignement(): Collection
    {
        return $this->Enseignement;
    }

    public function addEnseignement(Enseignement $enseignement): self
    {
        if (!$this->Enseignement->contains($enseignement)) {
            $this->Enseignement[] = $enseignement;
            $enseignement->setMatiere($this);
        }

        return $this;
    }

    public function removeEnseignement(Enseignement $enseignement): self
    {
        if ($this->Enseignement->contains($enseignement)) {
            $this->Enseignement->removeElement($enseignement);
            // set the owning side to null (unless already changed)
            if ($enseignement->getMatiere() === $this) {
                $enseignement->setMatiere(null);
            }
        }

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
