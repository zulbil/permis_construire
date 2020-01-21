<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TTerritorial
 *
 * @ORM\Table(name="T_Territorial")
 * @ORM\Entity(repositoryClass="App\Repository\TTerritorialRepository")
 */
class TTerritorial
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdTerritorial", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idterritorial;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Fk_TypeEntite", type="string", length=50, nullable=true)
     */
    private $fkTypeentite = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Fk_TypeEntitéMere", type="string", length=50, nullable=true)
     */
    private $fkTypeentitemere = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Fk_TypeTerritorial", type="string", length=50, nullable=true)
     */
    private $fkTypeterritorial = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="OrdreTerritorial", type="integer", nullable=false)
     */
    private $ordreterritorial = '0';

    public function getIdterritorial(): ?int
    {
        return $this->idterritorial;
    }

    public function getFkTypeentite(): ?string
    {
        return $this->fkTypeentite;
    }

    public function setFkTypeentite(?string $fkTypeentite): self
    {
        $this->fkTypeentite = $fkTypeentite;

        return $this;
    }

    public function getFkTypeentitemere(): ?string
    {
        return $this->fkTypeentitemere;
    }

    public function setFkTypeentitemere(?string $fkTypeentitemere): self
    {
        $this->fkTypeentitemere = $fkTypeentitemere;

        return $this;
    }

    public function getFkTypeterritorial(): ?string
    {
        return $this->fkTypeterritorial;
    }

    public function setFkTypeterritorial(?string $fkTypeterritorial): self
    {
        $this->fkTypeterritorial = $fkTypeterritorial;

        return $this;
    }

    public function getOrdreterritorial(): ?int
    {
        return $this->ordreterritorial;
    }

    public function setOrdreterritorial(int $ordreterritorial): self
    {
        $this->ordreterritorial = $ordreterritorial;

        return $this;
    }


}
