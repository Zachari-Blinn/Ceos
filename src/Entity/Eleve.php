<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EleveRepository")
 */
class Eleve
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $AdresseRue;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $CP;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $Ville;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $Tel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AppreciationGenerale", mappedBy="eleve")
     */
    private $appreciationGenerales;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appreciation", mappedBy="Eleve")
     */
    private $appreciations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Noter", mappedBy="Eleve")
     */
    private $noters;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classe", inversedBy="Eleve")
     */
    private $classe;

    public function __construct()
    {
        $this->appreciationGenerales = new ArrayCollection();
        $this->appreciations = new ArrayCollection();
        $this->Noter = new ArrayCollection();
        $this->noters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseRue(): ?string
    {
        return $this->AdresseRue;
    }

    public function setAdresseRue(string $AdresseRue): self
    {
        $this->AdresseRue = $AdresseRue;

        return $this;
    }

    public function getCP(): ?string
    {
        return $this->CP;
    }

    public function setCP(string $CP): self
    {
        $this->CP = $CP;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="Eleve", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @return Collection|AppreciationGenerale[]
     */
    public function getAppreciationGenerales(): Collection
    {
        return $this->appreciationGenerales;
    }

    public function addAppreciationGenerale(AppreciationGenerale $appreciationGenerale): self
    {
        if (!$this->appreciationGenerales->contains($appreciationGenerale)) {
            $this->appreciationGenerales[] = $appreciationGenerale;
            $appreciationGenerale->setEleve($this);
        }

        return $this;
    }

    public function removeAppreciationGenerale(AppreciationGenerale $appreciationGenerale): self
    {
        if ($this->appreciationGenerales->contains($appreciationGenerale)) {
            $this->appreciationGenerales->removeElement($appreciationGenerale);
            // set the owning side to null (unless already changed)
            if ($appreciationGenerale->getEleve() === $this) {
                $appreciationGenerale->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Appreciation[]
     */
    public function getAppreciations(): Collection
    {
        return $this->appreciations;
    }

    public function addAppreciation(Appreciation $appreciation): self
    {
        if (!$this->appreciations->contains($appreciation)) {
            $this->appreciations[] = $appreciation;
            $appreciation->setEleve($this);
        }

        return $this;
    }

    public function removeAppreciation(Appreciation $appreciation): self
    {
        if ($this->appreciations->contains($appreciation)) {
            $this->appreciations->removeElement($appreciation);
            // set the owning side to null (unless already changed)
            if ($appreciation->getEleve() === $this) {
                $appreciation->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Noter[]
     */
    public function getNoters(): Collection
    {
        return $this->noters;
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
        $newEleve = null === $user ? null : $this;
        if ($user->getEleve() !== $newEleve) {
            $user->setEleve($newEleve);
        }

        return $this;
    }
}
