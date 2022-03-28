<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateTime;

    /**
     * @ORM\ManyToOne(targetEntity=Hive::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $HiveReported;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $InsideTemperature;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $InsideHumidity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $OutsideTemperature;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $OutsideHumidity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $AtmosphericPressure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->DateTime;
    }

    public function setDateTime(\DateTimeInterface $DateTime): self
    {
        $this->DateTime = $DateTime;

        return $this;
    }

    public function getHiveReported(): ?Hive
    {
        return $this->HiveReported;
    }

    public function setHiveReported(?Hive $HiveReported): self
    {
        $this->HiveReported = $HiveReported;

        return $this;
    }

    public function getInsideTemperature(): ?float
    {
        return $this->InsideTemperature;
    }

    public function setInsideTemperature(?float $InsideTemperature): self
    {
        $this->InsideTemperature = $InsideTemperature;

        return $this;
    }

    public function getInsideHumidity(): ?float
    {
        return $this->InsideHumidity;
    }

    public function setInsideHumidity(?float $InsideHumidity): self
    {
        $this->InsideHumidity = $InsideHumidity;

        return $this;
    }

    public function getOutsideTemperature(): ?float
    {
        return $this->OutsideTemperature;
    }

    public function setOutsideTemperature(?float $OutsideTemperature): self
    {
        $this->OutsideTemperature = $OutsideTemperature;

        return $this;
    }

    public function getOutsideHumidity(): ?float
    {
        return $this->OutsideHumidity;
    }

    public function setOutsideHumidity(?float $OutsideHumidity): self
    {
        $this->OutsideHumidity = $OutsideHumidity;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->Weight;
    }

    public function setWeight(?float $Weight): self
    {
        $this->Weight = $Weight;

        return $this;
    }

    public function getAtmosphericPressure(): ?float
    {
        return $this->AtmosphericPressure;
    }

    public function setAtmosphericPressure(?float $AtmosphericPressure): self
    {
        $this->AtmosphericPressure = $AtmosphericPressure;

        return $this;
    }
}
