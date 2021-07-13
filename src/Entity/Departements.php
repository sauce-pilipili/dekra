<?php

namespace App\Entity;

use App\Repository\DepartementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartementsRepository::class)
 */
class Departements
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
    private $numero;

    /**
     * @ORM\ManyToMany(targetEntity=Controleur::class, mappedBy="departement")
     */
    private $controleurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Beneficiaire::class, mappedBy="departement")
     */
    private $beneficiaires;

    public function __toString() {
        return $this->numero.' '.$this->name;
    }

    public function __construct()
    {
        $this->controleurs = new ArrayCollection();
        $this->beneficiaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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
            $controleur->addDepartement($this);
        }

        return $this;
    }

    public function removeControleur(Controleur $controleur): self
    {
        if ($this->controleurs->removeElement($controleur)) {
            $controleur->removeDepartement($this);
        }

        return $this;
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
     * @return Collection|Beneficiaire[]
     */
    public function getBeneficiaires(): Collection
    {
        return $this->beneficiaires;
    }

    public function addBeneficiaire(Beneficiaire $beneficiaire): self
    {
        if (!$this->beneficiaires->contains($beneficiaire)) {
            $this->beneficiaires[] = $beneficiaire;
            $beneficiaire->addDepartement($this);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): self
    {
        if ($this->beneficiaires->removeElement($beneficiaire)) {
            $beneficiaire->removeDepartement($this);
        }

        return $this;
    }
}
