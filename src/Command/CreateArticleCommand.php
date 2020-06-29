<?php


namespace App\Command;

use DateTime;
use App\Entity\Article;
use App\Repository\CatalogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateArticleCommand extends Command{

    protected static $defaultName = 'CreateArticle';
    private $manager;
    private $repo;
    
    public function __construct(EntityManagerInterface $manager, CatalogRepository $repo){
        $this->manager = $manager;
        $this->repo = $repo;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Création article')
             ->setHelp('permet de créer un nouvel article et insertion dans la DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Saisir nom article ');        
        $description = $io->ask('Saisir desription article ');
        $prix = $io->ask('Saisir Prix');
        
        $catalogs = $this->repo->listCatalogName();

        $res = array();
        foreach($catalogs as $item){
            $res[] = $item['name']; 
        }

        $choix = $io->choice('Sélectionner la catégorie:  ', $res);

        $catalog = $this->repo->findOneByName($choix);

        $article = new Article();

        $article->setName($name)
                ->setDescription($description)
                ->setPrice($prix)
                ->setCreatedDate(new DateTime())
                ->setUpdateDate(new DateTime())
                ->setCreatedUser(440)
                ->setUpdateUser(440)
                ->setCatalog($catalog);

        $this->manager->persist($article);
        $this->manager->flush();

        $io->success('Article Créé');

        $output->writeln("Article : ".$name." - ".$description." - id : ".$catalog->getId()." - ".$choix." créé en base de données !");
    }
    
    
}