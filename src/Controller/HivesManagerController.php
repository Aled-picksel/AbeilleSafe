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
use App\Form\HiveFormType;


class HivesManagerController extends AbstractController
{
    
    /**
     * @Route("/dashboard/hivesmanager", name="manage_hives")
     */
    public function hivesManager(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 

        $repositoryAlerts = $em->getRepository(Alert::class);
        $notDismissedAlertsCount = $repositoryAlerts->getCountNotDismissedAlertsByOwner($this->getUser()->getId());
        $alerts = $repositoryAlerts->findAlertsByOwner($this->getUser()->getId());

        $repositoryHives = $em->getRepository(Hive::class);
        $repositoryReports = $em->getRepository(Report::class);

        $hives = $repositoryHives->findAllByOwner($this->getUser()->getId());

        $lastreports = array();
        foreach($hives as $hive){
            $lastreport = $repositoryReports->findLastReport($hive->getId());
            if($lastreport) {
                array_push($lastreports,$lastreport);
            }
        }
        
        return $this->render('hivesmanager.html.twig', [
            'title' => 'Gestion des ruches',
            'hives' => $hives,
            'lastreports' => $lastreports,
            'useremail' => $this->getUser()->getEmail(),
            'userid' => $this->getUser()->getId(),
            'alerts' => $alerts,
            'notDismissedAlertsCount' => $notDismissedAlertsCount
        ]);
    }

    /**
     * @Route("/dashboard/hivesmanager/create", name="create_hive")
     */
    public function hivesCreate(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 

        $repositoryHives = $em->getRepository(Hive::class);
        $newhive = new Hive();
        $form = $this->createForm(HiveFormType::class, $newhive);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newhive = $form->getData();
            $newhive->setOwner($this->getUser());
            $newhive->setCreationDate(new \DateTime('now'));
            $em->persist($newhive);
            $em->flush();

            return $this->redirectToRoute('manage_hives', array('okmessage' => 'La ruche a été créée'));
        }

        
        return $this->render('hiveform.html.twig', [
            'title' => 'Création de ruche',
            'update' => false,
            'form' => $form->createView(),
            'useremail' => $this->getUser()->getEmail()
        ]);
    }

    /**
     * @Route("/dashboard/hivesmanager/edit/{hiveid}", name="edit_hive")
     */
    public function hivesEdit($hiveid, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 

        $repositoryHives = $em->getRepository(Hive::class);
        $newhive = $repositoryHives->find($hiveid);
        $form = $this->createForm(HiveFormType::class, $newhive);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newhive = $form->getData();

            $em->persist($newhive);
            $em->flush();

            return $this->redirectToRoute('manage_hives', array('okmessage' => 'La ruche a été modifiée'));
        }

        
        return $this->render('hiveform.html.twig', [
            'title' => 'Édition de ruche',
            'update' => true,
            'form' => $form->createView(),
            'useremail' => $this->getUser()->getEmail()
        ]);
    }

    /**
     * @Route("/dashboard/hivesmanager/delete/{hiveid}", name="delete_hive")
     */
    public function hivesDelete($hiveid, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 

        $repositoryHives = $em->getRepository(Hive::class);
        $hivetodelete = $repositoryHives->find($hiveid);
        $em->remove($hivetodelete);
        $em->flush();
        
        return $this->redirectToRoute('manage_hives', array('okmessage' => 'La ruche a été supprimée'));
    }
}
