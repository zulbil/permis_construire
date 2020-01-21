<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Activite
 *
 * @ORM\Entity(repositoryClass="App\Repository\ActiviteRepository")
 * @ORM\Table(name="activite", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_B87555156C6E55B5", columns={"nom"})})
 * @ORM\Entity
 */
class Activite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Personne", mappedBy="secteur_activites")
     */
    private $personnes;

    public function __construct()
    {
        $this->personnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Personne[]
     */
    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }

    public function addPersonne(Personne $personne): self
    {
        if (!$this->personnes->contains($personne)) {
            $this->personnes[] = $personne;
            $personne->setSecteurActivites($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personnes->contains($personne)) {
            $this->personnes->removeElement($personne);
            // set the owning side to null (unless already changed)
            if ($personne->getSecteurActivites() === $this) {
                $personne->setSecteurActivites(null);
            }
        }

        return $this;
    }


}
