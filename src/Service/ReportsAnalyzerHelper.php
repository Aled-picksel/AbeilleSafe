<?php

namespace App\Service;


use App\Repository\ReportRepository;
use App\Repository\HiveRepository;
use App\Service\AlertMakerHelper;
use App\Entity\Alert;


class ReportsAnalyzerHelper 
{
    private $reportRepository;
    private $hiveRepository;
    private $alertService;

    public function __construct(ReportRepository $reportRepository, HiveRepository $hiveRepository, AlertMakerHelper $alertService)
    {
        $this->reportRepository = $reportRepository;
        $this->hiveRepository = $hiveRepository;
        $this->alertService = $alertService;

    }

    public function analyzeAllReportsBetweenDates(\DateTime $begin, \DateTime $end) : bool
    {
        $success=true;
        $hives = $this->hiveRepository->findAll();
        foreach($hives as $hive){
            $hivereports = $this->reportRepository->findReportsByHiveBetweenDates($hive->getId(), $begin, $end);
            if(!$this->analyzeArrayOfReports($hive,$hivereports)){
                $success=false;
            }
        }
        
        return $success;
    }

    public function analyzeArrayOfReports($hive, array $reports) : bool 
    {
        
        for($i=1; $i< count($reports) ; $i++){ //il faut min 2 rapports pour comparer variati
            //Analyse de la masse
            
            $month = $reports[$i]->getDateTime()->format('n');

            //toutes saisons : alerte sur diminution très forte
            $v_w = $reports[$i]->getWeight()-$reports[$i-1]->getWeight();
            $v_d = date_diff($reports[$i-1]->getDateTime(),$reports[$i]->getDateTime())->format('s');



            if($v_w < -10 && $v_d < 2*3600){ //si il y a une perte de + 10kg entre 2 rapports qui ont - de 2h d'écart temporel
                //Alerte ! 
                
                $this->alertService->makeAlert(AlertMakerHelper::ALERT_CRITICAL, $hive->getOwner(),
                    $hive->getName()." : votre ruche n'est plus détectée", "Votre ruche a perdu plus de 10kg en moins de deux heures ; il est probable qu'elle ait disparu.
                    Vous devez prendre des mesures d'urgence.");
            }

            if($month<10 && $month>=4){ //Printemps été : alerte sur peu de fluctuation au long cours
                if($i>=10){ //comparaison sur 10 rapports consécutifs
                    $s = 0;
                    for($j = 10; $j>0;$j--){
                        $s += abs($reports[$i-$j]->getWeight()-$reports[$i-$j+1]->getWeight());
                    }
                    $minvar = 0.2;
                    if($month >=6 && $month <=8){
                        $minvar = 0.2;
                    }
                    if($s<$minvar) { //s'il y a eu - de 1 ou 1.5kg de variation absolue au cours des 10 derniers rapports
                        //Alerte ! 
                        $this->alertService->makeAlert(AlertMakerHelper::ALERT_DANGER, $hive->getOwner(),
                        $hive->getName()." : votre ruche a anormalement peu d'activité", "Le poids de votre ruche varie anormalement peu. Visitez la page relative à cette ruche. ");
                    }
                }
            } else if($i>1) {
                $v_w1 = $reports[$i-1]->getWeight()-$reports[$i-2]->getWeight();
                $v_w2 = $reports[$i]->getWeight()-$reports[$i-1]->getWeight();
                if(abs($v_w1-$v_w2)>0.5){
                    //Alerte ! 
                    $this->alertService->makeAlert(AlertMakerHelper::ALERT_WARNING, $hive->getOwner(),
                    $hive->getName()." : votre ruche semble anormalement active", "Le poids de votre ruche varie de manière anormale pour la saison. Visitez la page relative à cette ruche");
                }
            }
        }
        return true;
    }
}