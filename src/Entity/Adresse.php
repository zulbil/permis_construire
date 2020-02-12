<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Adresse
 *
 * @ORM\Table(name="adresse")
 * @ORM\Entity(repositoryClass="App\Repository\AdresseRepository")
 */
class Adresse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $adresse_complete;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $par_defaut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Personne", inversedBy="adresses")
     */
    private $personne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TEntiteAdministrative", inversedBy="adresses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_entite", referencedColumnName="IdEntite")
     * })
     */
    public $fk_entite;

    public function __construct()
    {
        $this->personne = new ArrayCollection();
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

    public function getAdresseComplete(): ?string
    {
        return $this->adresse_complete;
    }

    public function setAdresseComplete(string $adresse_complete): self
    {
        $this->adresse_complete = $adresse_complete;

        return $this;
    }

    public function getParDefaut(): ?int
    {
        return $this->par_defaut;
    }

    public function setParDefaut(int $par_defaut): self
    {
        $this->par_defaut = $par_defaut;

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
     * @return Collection|Personne[]
     */
    public function getPersonne(): Collection
    {
        return $this->personne;
    }

    public function addPersonne(Personne $personne): self
    {
        if (!$this->personne->contains($personne)) {
            $this->personne[] = $personne;
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personne->contains($personne)) {
            $this->personne->removeElement($personne);
        }

        return $this;
    }

    public function getFkEntite(): ?TEntiteAdministrative
    {
        return $this->fk_entite;
    }

    public function setFkEntite(?TEntiteAdministrative $fk_entite): self
    {
        $this->fk_entite = $fk_entite;

        return $this;
    }


}
