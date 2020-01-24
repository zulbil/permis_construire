<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Personne
 * @ORM\Entity(repositoryClass="App\Repository\PersonneRepository")
 * @ORM\Table(name="personne", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_FCEC9EF450FF010", columns={"telephone"}), @ORM\UniqueConstraint(name="UNIQ_FCEC9EFE7927C74", columns={"email"}), @ORM\UniqueConstraint(name="UNIQ_FCEC9EFADE62BBB", columns={"nif"})}, indexes={@ORM\Index(name="IDX_FCEC9EFFDEF8996", columns={"profession_id"}), @ORM\Index(name="IDX_FCEC9EF532830B5", columns={"secteur_activites_id"}), @ORM\Index(name="IDX_FCEC9EFFB88E14F", columns={"utilisateur_id"})})
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet email")
 * @UniqueEntity(fields={"nif"}, message="Ce numéro d'identification existe déjà")
 * @UniqueEntity(fields={"telephone"}, message="Ce numéro de téléphone existe déjà")
 * @UniqueEntity(fields={"numeroIdNationale"}, message="Ce numéro d'identification nationale existe déjà")
 * @UniqueEntity(fields={"numeroRegistreCommerce"}, message="Ce numéro de registre de commerce existe déjà")
 */
class Personne
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
     * @Assert\NotBlank(message= "Ce chanmp ne peut être vide")
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postnom", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message= "Ce chanmp ne peut être vide")
     */
    private $postnom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message= "Ce champ ne peut être vide")
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexe", type="string", length=1, nullable=true)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message= "Ce champ ne peut être vide")
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_civil", type="string", length=20, nullable=true)
     */
    private $etatCivil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieu_de_naissance", type="string", length=255, nullable=true)
     */
    private $lieuDeNaissance;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_de_naissance", type="date", nullable=true)
     */
    private $dateDeNaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nationalite", type="string", length=255, nullable=true)
     */
    private $nationalite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=15, nullable=true, unique=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nif", type="string", length=255, nullable=true, unique=true)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer", nullable=false)
     */
    private $etat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_id_nationale", type="string", length=255, nullable=true, unique=true)
     */
    private $numeroIdNationale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_registre_commerce", type="string", length=255, nullable=true, unique=true)
     */
    private $numeroRegistreCommerce;

    /**
     * @var string
     *
     * @ORM\Column(name="forme_juridique", type="string", length=20, nullable=false)
     */
    private $formeJuridique;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="personne")
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activite", inversedBy="personnes")
     */
    private $secteur_activites;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction", inversedBy="personnes")
     */
    private $profession;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Adresse", mappedBy="personne")
     */
    private $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
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

    public function getPostnom(): ?string
    {
        return $this->postnom;
    }

    public function setPostnom(?string $postnom): self
    {
        $this->postnom = $postnom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEtatCivil(): ?string
    {
        return $this->etatCivil;
    }

    public function setEtatCivil(?string $etatCivil): self
    {
        $this->etatCivil = $etatCivil;

        return $this;
    }

    public function getLieuDeNaissance(): ?string
    {
        return $this->lieuDeNaissance;
    }

    public function setLieuDeNaissance(?string $lieuDeNaissance): self
    {
        $this->lieuDeNaissance = $lieuDeNaissance;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTimeInterface $dateDeNaissance): self
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(?string $nif): self
    {
        $this->nif = $nif;

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

    public function getNumeroIdNationale(): ?string
    {
        return $this->numeroIdNationale;
    }

    public function setNumeroIdNationale(?string $numeroIdNationale): self
    {
        $this->numeroIdNationale = $numeroIdNationale;

        return $this;
    }

    public function getNumeroRegistreCommerce(): ?string
    {
        return $this->numeroRegistreCommerce;
    }

    public function setNumeroRegistreCommerce(?string $numeroRegistreCommerce): self
    {
        $this->numeroRegistreCommerce = $numeroRegistreCommerce;

        return $this;
    }

    public function getFormeJuridique(): ?string
    {
        return $this->formeJuridique;
    }

    public function setFormeJuridique(string $formeJuridique): self
    {
        $this->formeJuridique = $formeJuridique;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getSecteurActivites(): ?Activite
    {
        return $this->secteur_activites;
    }

    public function setSecteurActivites(?Activite $secteur_activites): self
    {
        $this->secteur_activites = $secteur_activites;

        return $this;
    }

    public function getProfession(): ?Fonction
    {
        return $this->profession;
    }

    public function setProfession(?Fonction $profession): self
    {
        $this->profession = $profession;

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
            $adress->addPersonne($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->contains($adress)) {
            $this->adresses->removeElement($adress);
            $adress->removePersonne($this);
        }

        return $this;
    }


}
