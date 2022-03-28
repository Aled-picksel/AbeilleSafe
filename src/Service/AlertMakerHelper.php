<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReportRepository;
use App\Repository\AlertRepository;
use App\Repository\AlertTypeRepository;
use App\Entity\Alert;

class AlertMakerHelper 
{
    public const ALERT_INFO = 0;
    public const ALERT_WARNING = 1;
    public const ALERT_DANGER = 2;
    public const ALERT_CRITICAL = 3;

    
    private $reportRepository;
    private $alertRepository;
    private $alertTypeRepository;
    private $em;

    public function __construct(ReportRepository $reportRepository, AlertRepository $alertRepository, AlertTypeRepository $alertTypeRepository, EntityManagerInterface $em)
    {
        $this->reportRepository = $reportRepository;
        $this->alertRepository = $alertRepository;
        $this->alertTypeRepository = $alertTypeRepository;
        $this->em = $em;

    }

    public function makeAlert(int $level, $user, string $shortdesc, string $longdesc, \DateTime $datetime = null)
    {
        $newalert = new Alert();
        $newalert->setType($this->alertTypeRepository->findOneByDanger($level));
        $newalert->setOwner($user);
        if($datetime == null) {
            $datetime = new \DateTime("now");
        }
        $newalert->setEmissionDateTime($datetime);
        $newalert->setShortDesc($shortdesc);
        $newalert->setLongDesc($longdesc);
        $newalert->setIsDismissed(false);
        $this->em->persist($newalert);
        $this->em->flush();
    }
}