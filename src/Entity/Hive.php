<?php

namespace App\Entity;

use App\Repository\HiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HiveRepository::class)
 */
class Hive
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
    private $Name;

    /**
     * @ORM\Column(type="date")
     */
    private $CreationDate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $HausseCount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ApiUrl;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="HiveReported", orphanRemoval=true)
     */
    private $reports;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="hives")
     */
    private $Owner;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(\DateTimeInterface $CreationDate): self
    {
        $this->CreationDate = $CreationDate;

        return $this;
    }

    public function getHausseCount(): ?int
    {
        return $this->HausseCount;
    }

    public function setHausseCount(int $HausseCount): self
    {
        $this->HausseCount = $HausseCount;

        return $this;
    }

    public function getApiUrl(): ?string
    {
        return $this->ApiUrl;
    }

    public function setApiUrl(string $ApiUrl): self
    {
        $this->ApiUrl = $ApiUrl;

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setHiveReported($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getHiveReported() === $this) {
                $report->setHiveReported(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->Owner;
    }

    public function setOwner(?User $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }
}
