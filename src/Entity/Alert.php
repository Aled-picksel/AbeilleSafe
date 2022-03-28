<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlertRepository::class)
 */
class Alert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AlertType::class, inversedBy="alerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ShortDesc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $LongDesc;

    /**
     * @ORM\Column(type="datetime")
     */
    private $EmissionDateTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsDismissed;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="alerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?AlertType
    {
        return $this->Type;
    }

    public function setType(?AlertType $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getShortDesc(): ?string
    {
        return $this->ShortDesc;
    }

    public function setShortDesc(string $ShortDesc): self
    {
        $this->ShortDesc = $ShortDesc;

        return $this;
    }

    public function getLongDesc(): ?string
    {
        return $this->LongDesc;
    }

    public function setLongDesc(string $LongDesc): self
    {
        $this->LongDesc = $LongDesc;

        return $this;
    }

    public function getEmissionDateTime(): ?\DateTimeInterface
    {
        return $this->EmissionDateTime;
    }

    public function setEmissionDateTime(\DateTimeInterface $EmissionDateTime): self
    {
        $this->EmissionDateTime = $EmissionDateTime;

        return $this;
    }

    public function getIsDismissed(): ?bool
    {
        return $this->IsDismissed;
    }

    public function setIsDismissed(bool $IsDismissed): self
    {
        $this->IsDismissed = $IsDismissed;

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
