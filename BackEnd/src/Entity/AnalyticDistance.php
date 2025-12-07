<?php

namespace App\Entity;

use App\Repository\AnalyticDistanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalyticDistanceRepository::class)]
class AnalyticDistance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'float')]
    private ?float $distanceKm = null;

    #[ORM\ManyToOne(targetEntity: AnalyticCode::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnalyticCode $analyticCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDistanceKm(): ?float
    {
        return $this->distanceKm;
    }

    public function setDistanceKm(float $distanceKm): self
    {
        $this->distanceKm = $distanceKm;
        return $this;
    }

    public function getAnalyticCode(): ?AnalyticCode
    {
        return $this->analyticCode;
    }

    public function setAnalyticCode(?AnalyticCode $analyticCode): self
    {
        $this->analyticCode = $analyticCode;
        return $this;
    }
}
