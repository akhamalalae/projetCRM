<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Services\GenerationAutomatiqueRendezVous;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\HistoriqueGenerationAutomatiqueRouting;

#[AsCommand(
    //php bin/console generationAutomatiqueRendezVous
    name: 'generationAutomatiqueRendezVous',
    description: 'Generation automatique rendezVous',
)]
class GenerationAutomatiqueRendezVousCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager, private GenerationAutomatiqueRendezVous $generationAutomatiqueRendezVous)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit','2048M');
        set_time_limit(600);

        $em = $this->entityManager;

        $historiques = $em->getRepository(HistoriqueGenerationAutomatiqueRouting::class)->findBy(
            ['isGenerer' => false]
        );

        foreach ($historiques as $historique) {
            $this->generationAutomatiqueRendezVous->create($historique);
            $historique->setIsGenerer(true);
            $em->persist($historique);
            $em->flush();
        }

        $output->writeln(' nombre des historiques créés par la commande : '.count($historiques));

        return Command::SUCCESS;
    }
}
