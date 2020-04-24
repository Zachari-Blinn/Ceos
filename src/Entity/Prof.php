<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfRepository")
 */
class Prof
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enseignement", mappedBy="prof")
     */
    private $Enseignement;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="Prof", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->Enseignement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $enseignement->setProf($this);
        }

        return $this;
    }

    public function removeEnseignement(Enseignement $enseignement): self
    {
        if ($this->Enseignement->contains($enseignement)) {
            $this->Enseignement->removeElement($enseignement);
            // set the owning side to null (unless already changed)
            if ($enseignement->getProf() === $this) {
                $enseignement->setProf(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->Nom;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newProf = null === $user ? null : $this;
        if ($user->getProf() !== $newProf) {
            $user->setProf($newProf);
        }

        return $this;
    }
}
