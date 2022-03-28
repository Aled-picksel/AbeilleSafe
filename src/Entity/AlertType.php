<?php

namespace App\Entity;

use App\Repository\AlertTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlertTypeRepository::class)
 */
class AlertType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $Danger;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Icon;

    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="Type", orphanRemoval=true)
     */
    private $alerts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Color;

    public function __construct()
    {
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDanger(): ?int
    {
        return $this->Danger;
    }

    public function setDanger(int $Danger): self
    {
        $this->Danger = $Danger;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->Icon;
    }

    public function setIcon(string $Icon): self
    {
        $this->Icon = $Icon;

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setType($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getType() === $this) {
                $alert->setType(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->Color;
    }

    public function setColor(string $Color): self
    {
        $this->Color = $Color;

        return $this;
    }
}
