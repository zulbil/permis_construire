<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TEntiteAdministrative
 *
 * @ORM\Table(name="T_Entite_Administrative")
 * @ORM\Entity(repositoryClass="App\Repository\TEntiteAdministrativeRepository")
 */
class TEntiteAdministrative
{
    /**
     * @var string
     *
     * @ORM\Column(name="IdEntite", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $identite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IntituleEntite", type="string", length=150, nullable=true)
     */
    private $intituleentite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DenominationHabitant", type="string", length=150, nullable=true)
     */
    private $denominationhabitant;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Etat", type="integer", nullable=true)
     */
    private $etat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="IsVisible", type="integer", nullable=true)
     */
    private $isvisible;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TTypeEntiteAdministrative", inversedBy="entites_administratives")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Fk_TypeEntite", referencedColumnName="IdTypeEntite")
     * })
     */
    private $fkTypeentite;

    /**
     * @ORM\Column(type="string", length=255, name="Fk_EntiteMere")
     */
    private $fkEntitemere;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Adresse", mappedBy="fk_entite")
     */
    private $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }


    //private $fkEntitemere;

    public function getIdentite(): ?string
    {
        return $this->identite;
    }

    public function getIntituleentite(): ?string
    {
        return $this->intituleentite;
    }

    public function setIntituleentite(?string $intituleentite): self
    {
        $this->intituleentite = $intituleentite;

        return $this;
    }

    public function getDenominationhabitant(): ?string
    {
        return $this->denominationhabitant;
    }

    public function setDenominationhabitant(?string $denominationhabitant): self
    {
        $this->denominationhabitant = $denominationhabitant;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIsvisible(): ?int
    {
        return $this->isvisible;
    }

    public function setIsvisible(?int $isvisible): self
    {
        $this->isvisible = $isvisible;

        return $this;
    }

    public function getFkTypeentite(): ?TTypeEntiteAdministrative
    {
        return $this->fkTypeentite;
    }

    public function setFkTypeentite(?TTypeEntiteAdministrative $fkTypeentite): self
    {
        $this->fkTypeentite = $fkTypeentite;

        return $this;
    }

    public function getFkEntitemere(): ?string
    {
        return $this->fkEntitemere;
    }

    public function setFkEntitemere(string $fkEntitemere): self
    {
        $this->fkEntitemere = $fkEntitemere;

        return $this;
    }

    /**
     * @return Collection|Adresse[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setFkEntite($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->contains($adress)) {
            $this->adresses->removeElement($adress);
            // set the owning side to null (unless already changed)
            if ($adress->getFkEntite() === $this) {
                $adress->setFkEntite(null);
            }
        }

        return $this;
    }


}
