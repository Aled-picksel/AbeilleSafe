<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Report;
use App\Entity\Hive;
use App\Entity\Alert;


class IndexController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Request $request, EntityManagerInterface $em): Response
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

        $repositoryReports = $em->getRepository(Report::class);
        $repositoryHives = $em->getRepository(Hive::class);
        $hives = $repositoryHives->findAllByOwner($this->getUser()->getId());
        $lastreports = array();
        foreach($hives as $hive){
            $lastreport = $repositoryReports->findLastReport($hive->getId());
            if($lastreport) {
                array_push($lastreports,$lastreport);
            }
        }
        //$reports_dt = $repositoryReports->findByHiveId_DT(1);
        //$reports_w = $repositoryReports->findByHiveId_W(1);
        
        return $this->render('dashboard.html.twig', [
            'title' => 'Tableau de bord',
            'hives' => $hives,
            'lastreports' => $lastreports,
            'useremail' => $this->getUser()->getEmail(),
            'alerts' => $alerts,
            'notDismissedAlertsCount' => $notDismissedAlertsCount
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {

        return $this->render('index.html.twig', [
            'title' => 'Accueil'
        ]);
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation(): Response
    {

        return $this->render('presentation.html.twig', [
            'title' => 'Présentation'
        ]);
    }
}
