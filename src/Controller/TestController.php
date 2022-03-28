<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Report;
use App\Entity\Hive;
use Doctrine\ORM\EntityManagerInterface;
use App\Test\Generators;

class TestController extends AbstractController
{
    /**
     * @Route("/test/generate/{hiveid}/{days}", name="generate_year")
     */
    public function createProduct($hiveid, $days, EntityManagerInterface $entityManager): Response
    {
        ini_set('max_execution_time', 0); //ça peut être très long => désactivaiton timeout

        $hive = $this->getDoctrine()
            ->getRepository(Hive::class)
            ->find($hiveid);
        
        $date = new \DateTime("now");
        $date = $date->sub(new \DateInterval('P'.$days.'D'));
        $itemp=20; $ihum=50; $otemp=8; $ohum=60; $weight=40; $press=1010;
        $i; $r;
        for ($i=0 ; $i < $days ; $i++) { //jour
            for ($r=0 ; $r < 48 ; $r++){ //1 rapport par demi heure
                $report = new Report();
                $report->setDateTime(clone $date);
                $report->setHiveReported($hive);

                $otemp=Generators::generateOutsideTemperature($date, $otemp);
                $report->setOutsideTemperature($otemp);

                $ohum=Generators::generateOutsideHumidity($date, $ohum);
                $report->setOutsideHumidity($ohum);
                
                $itemp = Generators::generateInsideTemperature($date, $otemp, $itemp);
                $report->setInsideTemperature($itemp);

                $ihum=Generators::generateInsideHumidity($date, $ohum, $ihum);
                $report->setInsideHumidity($ihum);

                $weight=Generators::generateWeight($date, $weight);
                $report->setWeight($weight);

                $press=Generators::generateAtmosphericPressure($date, $press);
                $report->setAtmosphericPressure($press);

                $entityManager->persist($report);

                $date->add(new \DateInterval('PT30M')); //+30min
                unset($report);
            }
            $entityManager->flush();
        }
        
        

        return $this->redirectToRoute('dashboard');
    }
    
}