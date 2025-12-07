<?php

namespace App\Message;

class AnalyticDistanceMessage
{
    public function __construct(
        public float $totalDistance,
        public int $analyticCodeId,
        public ?\DateTimeImmutable $date = null
    ) {}
}
