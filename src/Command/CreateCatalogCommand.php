<?php

namespace App\Command;

use DateTime;
use App\Entity\Catalog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCatalogCommand extends Command{

    protected static $defaultName = 'CreateCatalog';
    private $manager;
    
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Création catégorie')
             ->setHelp('permet de créer une nouvelle catégorie et insertion dans la DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Saisir nom catégorie ');
        $description = $io->ask('Saisir description catégorie ');

        $catalog = new Catalog();
        $catalog
             ->setName($name)
             ->setDescription($description)
             ->setCreatedDate(new DateTime())
             ->setUpdateDate(new DateTime())
             ->setCreatedUser(440)
             ->setUpdateUser(440);


        $this->manager->persist($catalog);
        $this->manager->flush();

        $output->writeln("Catégorie : ".$name."  créé en base de données !");
            
    }
    
    
}