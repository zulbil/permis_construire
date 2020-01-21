<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TTypeterritorial
 *
 * @ORM\Table(name="T_TypeTerritorial")
 * @ORM\Entity
 */
class TTypeterritorial
{
    /**
     * @var string
     *
     * @ORM\Column(name="IdTypeTerritorial", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtypeterritorial;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IntituleTerritorial", type="string", length=150, nullable=true)
     */
    private $intituleterritorial;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Etat", type="integer", nullable=true)
     */
    private $etat;

    public function getIdtypeterritorial(): ?string
    {
        return $this->idtypeterritorial;
    }

    public function getIntituleterritorial(): ?string
    {
        return $this->intituleterritorial;
    }

    public function setIntituleterritorial(?string $intituleterritorial): self
    {
        $this->intituleterritorial = $intituleterritorial;

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


}
