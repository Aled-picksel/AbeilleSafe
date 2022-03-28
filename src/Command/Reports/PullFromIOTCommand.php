<?php

namespace App\Command\Reports;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PullFromIOTCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:reports:pull-from-iot';

    protected function configure()
    {
        $this
        ->setDescription('Se connecte à l\'IOT Cloud et récupère les derniers rapports')
        ->addArgument('time', InputArgument::REQUIRED, 'Intervalle de temps en SECONDES jusqu\'à maintenant, pour lequel vérifier les rapports')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Récupération des rapports depuis l\'IOT');
        
        if(!is_int($input->getArgument('time'))){
            $output->writeln('Erreur : veuillez entrer un nombre entier pour l\'argument');
            return Command::FAILURE;
        }

        $intvSeconds = strtoint($input->getArgument('time'));
        //todo iot

        return Command::SUCCESS;
    }
}