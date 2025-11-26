<?php

namespace App\Entity;

use App\Repository\DistanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistanceRepository::class)]
class Distance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $stationParent = null;

    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $stationChild = null;

    #[ORM\Column(type: "float")]
    private ?float $distance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStationParent(): ?Station
    {
        return $this->stationParent;
    }

    public function setStationParent(Station $stationParent): static
    {
        $this->stationParent = $stationParent;
        return $this;
    }

    public function getStationChild(): ?Station
    {
        return $this->stationChild;
    }

    public function setStationChild(Station $stationChild): static
    {
        $this->stationChild = $stationChild;
        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): static
    {
        $this->distance = $distance;
        return $this;
    }
}
