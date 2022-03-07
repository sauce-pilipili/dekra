<?php

namespace App\Entity;

use App\Repository\ReferenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReferenceRepository::class)
 */
class Reference
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idLotUnique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idKizeoForm;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $complet;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $depotDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRestitution;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validation;

    /**
     * @return mixed
     */
    public function getDateRestitution()
    {
        return $this->dateRestitution;
    }

    /**
     * @param mixed $dateRestitution
     */
    public function setDateRestitution($dateRestitution): void
    {
        $this->dateRestitution = $dateRestitution;
    }

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="referenceFiche")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getComplet(): ?bool
    {
        return $this->complet;
    }

    public function setComplet(?bool $complet): self
    {
        $this->complet = $complet;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getIdLotUnique()
    {
        return $this->idLotUnique;
    }

    /**
     * @param mixed $idLotUnique
     */
    public function setIdLotUnique($idLotUnique): void
    {
        $this->idLotUnique = $idLotUnique;
    }

    /**
     * @return mixed
     */
    public function getIdKizeoForm()
    {
        return $this->idKizeoForm;
    }

    /**
     * @param mixed $idKizeoForm
     */
    public function setIdKizeoForm($idKizeoForm): void
    {
        $this->idKizeoForm = $idKizeoForm;
    }

    /**
     * @return mixed
     */
    public function getDepotDate()
    {
        return $this->depotDate;
    }

    /**
     * @param mixed $depotDate
     */
    public function setDepotDate($depotDate): void
    {
        $this->depotDate = $depotDate;
    }



    public function getValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(?bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }


}
