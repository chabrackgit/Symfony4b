<?php

namespace App\Command;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command{

    protected static $defaultName = 'CreateUser';
    private $manager;
    private $encoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $this->manager = $manager;
        $this->encoder = $encoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Création utilisateur')
             ->setHelp('permet de créer un utilisateur et insertion dans la DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $mail = $io->ask('Saisir votre adresse email');
        $password = $io->askHidden('Saisir votre mot de passe');

        $user = new User();

        $hash = $this->encoder->encodePassword($user, $password);

        $user
             ->setEmail($mail)
             ->setPassword($hash)
             ->setCreatedDate(new DateTime())
             ->setUpdateDate(new DateTime());

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('Utilisateur Créé');

        $output->writeln("Utilisateur : ".$mail." créé en base de données !");
    
    }
    
    
}