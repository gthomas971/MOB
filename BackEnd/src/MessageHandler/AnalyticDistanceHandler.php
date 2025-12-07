<?php

namespace App\MessageHandler;

use App\Message\AnalyticDistanceMessage;
use App\Entity\AnalyticDistance;
use App\Repository\AnalyticCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AnalyticDistanceHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private AnalyticCodeRepository $codeRepo
    ) {}

    public function __invoke(AnalyticDistanceMessage $message)
    {

        $code = $this->codeRepo->find($message->analyticCodeId);
        if (!$code) {
            return;
        }

        try {
            $distance = new AnalyticDistance();
            $distance->setDistanceKm($message->totalDistance);
            $distance->setAnalyticCode($code);
            $distance->setDate($message->date ?? new \DateTime());

            $this->em->persist($distance);
            $this->em->flush();
        } catch (\Throwable $e) {
           // echo 'Erreur : ' . $e->getMessage();
        }
    }
}
