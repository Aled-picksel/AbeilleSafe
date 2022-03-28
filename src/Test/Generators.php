<?php

namespace App\Test;

class Generators {
    public static function generateInsideTemperature(\DateTime $date, float $otemp, float $previoustemp) : float {
        $coretemp = 35;
        return (3*$coretemp+$otemp)/4;//coef 3 sur la temp du coeur, coef 1 sur temp ext
    }

    public static function generateInsideHumidity(\DateTime $date, float $ohum, float $previoushum) : float {
        $corehum = 98;
        return (3*$corehum+$ohum)/4;
    }

    public static function generateOutsideTemperature(\DateTime $date, float $previoustemp) : float {
        $hour = $date->format('G');
        $month = $date->format('n');
        if($month<3){ //variation température saisons
            $bonusaprem=0;
        } elseif($month<7){
            $bonusaprem=+0.003; //normal+1, printemps
        } elseif($month<10){
            $bonusaprem=0;
        } else {
            $bonusaprem=-0.003; //normal-1, automne
        }
        $add;
        if($hour<6 OR $hour >=22){ //variation température heures de la journée
            $add=-0.1*rand(3,6); //8h -
        } elseif($hour<10) { 
            $add=0.1*rand(3,6); //4h +
        } elseif($hour<14) { 
            $add=(0.1+$bonusaprem)*rand(4,8); //4h ++
        } elseif($hour<18) { 
            $add=0.1*rand(3,6); //4h +
        } else { 
            $add=-0.1*rand(4,8); //4h --
        }
        return $previoustemp + $add;
    }

    public static function generateOutsideHumidity(\DateTime $date, float $previoushum) : float {
        $hour = $date->format('G');
        $add;
        if($hour<6){
            $add=1*rand(1,3);
        } elseif($hour<18) {
            $add=-1*rand(1,3);
        } else {
            $add=1*rand(1,3);
        }
        if($previoushum + $add > 100 OR $previoushum + $add < 15){
            $add=0;
        }
        return $previoushum + $add;
    }

    public static function generateWeight(\DateTime $date, float $previousw) : float {
        $hour = $date->format('G');
        $month = $date->format('n');
        $summermult=0;
        $wintermult=0;
        switch($month){
            case 11:
            case 12:
            case 1:
            case 2:
            case 3:
                $summermult=0;
                $wintermult=1;
                break;
            case 10:
            case 4:
                $summermult=0.3;
                $wintermult=0.7;
                break;
            case 9:
            case 5:
                $summermult=0.7;
                $wintermult=0.3;
                break;
            case 6:
            case 7:
            case 8:
                $summermult=1;
                $wintermult=0;

                //proba de retrait de (10kg) de miel en saison
                $prob_honey_out=rand(1,30*48);
                if($prob_honey_out==1){
                    return $previousw - 10;
                }
                break;
        }
        $summeradd=0;
        $winteradd=0;
        if($hour<9 OR $hour >= 20) {
            $summeradd=-0.025; //-50g/h (pdt 13h)
        } elseif($hour < 13){
            $summeradd=-0.3; //-600g/h (pdt 4h)
        } else {
            $summeradd=+0.22*(1+0.15*$summermult); //440g/h (pdt 7h)
        }
        if($previousw>35){
            $winteradd=-0.0001*rand(30,50); //(-192g/jour en hiver)
        }

        return $previousw + $summeradd * $summermult + $winteradd * $wintermult;
    }

    public static function generateAtmosphericPressure(\DateTime $date, float $previouspress) : float {
        $numsem = $date->format('W');
        $add;
        if($numsem%2==0){
            $add= 0.02*rand(2,6);
        } else {
            $add = -0.02*rand(2,6);
        }
        return $previouspress + $add;
    }
}