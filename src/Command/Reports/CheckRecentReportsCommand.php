<?php

namespace App\Command\Reports;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\ReportsAnalyzerHelper;

class CheckRecentReportsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:reports:check-recent';

    private $analyzer;

    public function __construct(ReportsAnalyzerHelper $a)
    {
        $this->analyzer = $a;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setDescription('Vérifie les derniers rapports reçus, et émet les notifications en conséquence')
        ->addArgument('time', InputArgument::REQUIRED, 'Intervalle de temps en SECONDES jusqu\'à maintenant, pour lequel vérifier les rapports')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!is_numeric($input->getArgument('time'))){
            $output->writeln('Erreur : veuillez entrer un nombre entier pour l\'argument');
            return Command::FAILURE;
        }

        $intvSeconds = intval($input->getArgument('time'));

        $enddt = new \DateTime('now');
        $begindt = clone $enddt;
        $begindt = $begindt->sub(new \DateInterval('PT'.$intvSeconds.'S'));

        $output->writeln('Vérification des rapports depuis le '.$begindt->format('d-m-Y H:i:s').' jusqu\'au '.$enddt->format('d-m-Y H:i:s'));

        if($this->analyzer->analyzeAllReportsBetweenDates($begindt,$enddt)){
            $output->writeln('Analyse réussie');
        } else {
            $output->writeln('Échec de l\'analyse');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}