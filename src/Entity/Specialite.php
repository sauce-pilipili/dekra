<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecialiteRepository::class)
 */
class Specialite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Controleur::class, mappedBy="specialite")
     */
    private $controleurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $referenceOperation;

    public function __toString() {
        return $this->name;
    }
    public function __construct()
    {
        $this->controleurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Controleur[]
     */
    public function getControleurs(): Collection
    {
        return $this->controleurs;
    }

    public function addControleur(Controleur $controleur): self
    {
        if (!$this->controleurs->contains($controleur)) {
            $this->controleurs[] = $controleur;
            $controleur->addSpecialite($this);
        }

        return $this;
    }

    public function removeControleur(Controleur $controleur): self
    {
        if ($this->controleurs->removeElement($controleur)) {
            $controleur->removeSpecialite($this);
        }

        return $this;
    }

    public function getReferenceOperation(): ?string
    {
        return $this->referenceOperation;
    }

    public function setReferenceOperation(string $referenceOperation): self
    {
        $this->referenceOperation = $referenceOperation;

        return $this;
    }
}
