<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Report;
use App\Entity\Hive;
use App\Entity\Alert;


class HiveController extends AbstractController
{
    const MAX_REPORTS = 200;
    
    /**
     * @Route("/dashboard/hive/{hiveid}", name="hive")
     */
    public function hive($hiveid, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); //tableau de bord accessible uniquement si connecté

        $repositoryAlerts = $em->getRepository(Alert::class);

        //Dismiss alert if set
        $dismissAId = $request->query->get('dismissAId');
        if($dismissAId){
            $alert = $repositoryAlerts->find($dismissAId);
            $alert->setIsDismissed(true);
            $em->flush();
        }

        $notDismissedAlertsCount = $repositoryAlerts->getCountNotDismissedAlertsByOwner($this->getUser()->getId());
        $alerts = $repositoryAlerts->findAlertsByOwner($this->getUser()->getId());

        $charttimeparam = $request->query->get('charttime');
        $charttime = 48*30;
        $step = 1;
        

        $repositoryReports = $em->getRepository(Report::class);
        $repositoryHives = $em->getRepository(Hive::class);
        $hive = $repositoryHives->find($hiveid);

        $rcount = $repositoryReports->getCountByHive($hiveid);

        switch($charttimeparam){
            case "all":
                $charttime = 48*365*99;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "2years":
                $charttime = 48*365*2;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "year":
                $charttime = 48*365;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "6months":
                $charttime = 48*30*6;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "3months":
                $charttime = 48*30*3;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "month":
                $charttime = 48*30;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "week":
                $charttime = 48*7;
                $step = ceil(min($rcount, $charttime)/250);
                break;
            case "day":
                $charttime = 48*1;
                $step = 1;
                break;
        }

        $filteredreports = $repositoryReports->findFilteredReportsByHive($hive->getId(), min($charttime,$rcount,250), $step);
        $lastreport = $repositoryReports->findLastReport($hive->getId());
        
        return $this->render('hive.html.twig', [
            'title' => 'Ruche '.$hive->getName(),
            'filteredreports' => $filteredreports,
            'lastreport' => $lastreport,
            'hive' => $hive,
            'useremail' => $this->getUser()->getEmail(),
            'alerts' => $alerts,
            'notDismissedAlertsCount' => $notDismissedAlertsCount,
            'step' => $step,
        ]);
    }
}
