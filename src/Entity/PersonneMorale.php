<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonneMorale
 *
 * @ORM\Table(name="personne_morale", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_56031D2A450FF010", columns={"telephone"})}, indexes={@ORM\Index(name="IDX_56031D2AFB88E14F", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class PersonneMorale
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
     * @ORM\Column(name="raison_social", type="string", length=255, nullable=false)
     */
    private $raisonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="sigle", type="string", length=255, nullable=false)
     */
    private $sigle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_id_nationale", type="string", length=255, nullable=true)
     */
    private $numeroIdNationale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_registre_commerce", type="string", length=255, nullable=true)
     */
    private $numeroRegistreCommerce;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="forme_juridique", type="string", length=255, nullable=false)
     */
    private $formeJuridique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="secteur_activite", type="string", length=255, nullable=true)
     */
    private $secteurActivite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="activite", type="string", length=255, nullable=true)
     */
    private $activite;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer", nullable=false)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255, nullable=false)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresses_succursales", type="string", length=255, nullable=true)
     */
    private $adressesSuccursales;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(string $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): self
    {
        $this->sigle = $sigle;

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

    public function getFormeJuridique(): ?string
    {
        return $this->formeJuridique;
    }

    public function setFormeJuridique(string $formeJuridique): self
    {
        $this->formeJuridique = $formeJuridique;

        return $this;
    }

    public function getSecteurActivite(): ?string
    {
        return $this->secteurActivite;
    }

    public function setSecteurActivite(?string $secteurActivite): self
    {
        $this->secteurActivite = $secteurActivite;

        return $this;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): self
    {
        $this->activite = $activite;

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

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): self
    {
        $this->nif = $nif;

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

    public function getAdressesSuccursales(): ?string
    {
        return $this->adressesSuccursales;
    }

    public function setAdressesSuccursales(?string $adressesSuccursales): self
    {
        $this->adressesSuccursales = $adressesSuccursales;

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


}
