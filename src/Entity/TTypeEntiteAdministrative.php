<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TTypeEntiteAdministrative
 *
 * @ORM\Table(name="T_Type_Entite_Administrative")
 * @ORM\Entity(repositoryClass="App\Repository\TTypeEntiteAdministrativeRepository")
 */
class TTypeEntiteAdministrative
{
    /**
     * @var string
     *
     * @ORM\Column(name="IdTypeEntite", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtypeentite;

    /**
     * @var string
     *
     * @ORM\Column(name="IntituleTypeEntite", type="string", length=150, nullable=false)
     */
    private $intituletypeentite;

    /**
     * @var int
     *
     * @ORM\Column(name="Etat", type="integer", nullable=false)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TEntiteAdministrative", mappedBy="Fk_TypeEntite")
     */
    private $entites_administratives;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TTerritorial", mappedBy="Fk_TypeEntite")
     */
    private $territorials;

    public function __construct()
    {
        $this->entites_administratives = new ArrayCollection();
        $this->territorials = new ArrayCollection();
    }

    public function getIdtypeentite(): ?string
    {
        return $this->idtypeentite;
    }

    public function getIntituletypeentite(): ?string
    {
        return $this->intituletypeentite;
    }

    public function setIntituletypeentite(string $intituletypeentite): self
    {
        $this->intituletypeentite = $intituletypeentite;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|TEntiteAdministrative[]
     */
    public function getEntitesAdministratives(): Collection
    {
        return $this->entites_administratives;
    }

    public function addEntitesAdministrative(TEntiteAdministrative $entitesAdministrative): self
    {
        if (!$this->entites_administratives->contains($entitesAdministrative)) {
            $this->entites_administratives[] = $entitesAdministrative;
            $entitesAdministrative->setFkTypeentite($this);
        }

        return $this;
    }

    public function removeEntitesAdministrative(TEntiteAdministrative $entitesAdministrative): self
    {
        if ($this->entites_administratives->contains($entitesAdministrative)) {
            $this->entites_administratives->removeElement($entitesAdministrative);
            // set the owning side to null (unless already changed)
            if ($entitesAdministrative->getFkTypeentite() === $this) {
                $entitesAdministrative->setFkTypeentite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TTerritorial[]
     */
    public function getTerritorials(): Collection
    {
        return $this->territorials;
    }

    public function addTerritorial(TTerritorial $territorial): self
    {
        if (!$this->territorials->contains($territorial)) {
            $this->territorials[] = $territorial;
            $territorial->setFkTypeentite($this);
        }

        return $this;
    }

    public function removeTerritorial(TTerritorial $territorial): self
    {
        if ($this->territorials->contains($territorial)) {
            $this->territorials->removeElement($territorial);
            // set the owning side to null (unless already changed)
            if ($territorial->getFkTypeentite() === $this) {
                $territorial->setFkTypeentite(null);
            }
        }

        return $this;
    }


}
