<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClasseRepository")
 */
class Classe
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
    private $libelle;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Eleve", mappedBy="classe")
     */
    private $Eleve;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enseignement", mappedBy="classe")
     */
    private $Enseignement;

    public function __construct()
    {
        $this->Eleve = new ArrayCollection();
        $this->Enseignement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Eleve[]
     */
    public function getEleve(): Collection
    {
        return $this->Eleve;
    }

    public function addEleve(Eleve $eleve): self
    {
        if (!$this->Eleve->contains($eleve)) {
            $this->Eleve[] = $eleve;
            $eleve->setClasse($this);
        }

        return $this;
    }

    public function removeEleve(Eleve $eleve): self
    {
        if ($this->Eleve->contains($eleve)) {
            $this->Eleve->removeElement($eleve);
            // set the owning side to null (unless already changed)
            if ($eleve->getClasse() === $this) {
                $eleve->setClasse(null);
            }
        }

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
            $enseignement->setClasse($this);
        }

        return $this;
    }

    public function removeEnseignement(Enseignement $enseignement): self
    {
        if ($this->Enseignement->contains($enseignement)) {
            $this->Enseignement->removeElement($enseignement);
            // set the owning side to null (unless already changed)
            if ($enseignement->getClasse() === $this) {
                $enseignement->setClasse(null);
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

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }
}
