<?php

namespace App\Command;

use App\Entity\Ville;
use App\Entity\Region;
use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[AsCommand(
    //php bin/console cities
    name: 'cities',
    description: 'Add a short description for your command',
)]
class CitiesCommand extends Command
{
    private $entityManager;
    protected $projectDir;

    public function __construct(EntityManagerInterface $entityManager,KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->projectDir = $kernel->getProjectDir();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
         /* @var $em EntityManager */
        $em = $this->entityManager;
        $projectDir = $this->projectDir;

        // yolo
        ini_set("memory_limit", "-1");

        $csv = dirname($projectDir) . '/CRM' .DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'villes.csv';
        $lines = explode("\n", file_get_contents($csv));
        $output->writeln($lines);
        $regions = [];
        $departements = [];
        $villes = [];

        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            if (count($line) > 10 && $k > 0) {
                // On sauvegarde la region
                if (!key_exists($line[1], $regions)) {
                    $region = new Region();
                    $region->setCode($line[1]);
                    $region->setName($line[2]);
                    $regions[$line[1]] = $region;
                    $em->persist($region);
                } else {
                    $region = $regions[$line[1]];
                }

                // On sauvegarde le departement
                if (!key_exists($line[4], $departements)) {
                    $departement = new Departement();
                    $departement->setName($line[5]);
                    $departement->setCode($line[4]);
                    $departement->setRegion($region);
                    $departements[$line[4]] = $departement;
                    $em->persist($departement);
                } else {
                    $departement = $departements[$line[4]];
                }

                // On sauvegarde la ville
                $ville = new Ville();
                $ville->setName($line[8]);
                $ville->setCode($line[9]);
                $ville->setDepartement($departement);
                $villes[] = $line[8];
                $em->persist($ville);
            }
        }
        $em->flush();
        $output->writeln(count($villes) . ' villes importées');

        return Command::SUCCESS;
    }
}
