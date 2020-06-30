<?php

namespace App\Command;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command{

    protected static $defaultName = 'CreateUser';
    private $manager;
    private $encoder;
    private $mailer;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer){
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
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

        $lastname = $io->ask('Saisir votre nom');
        $firstname = $io->ask('Saisir votre prénom');
        $mail = $io->ask('Saisir votre adresse email');
        $password = $io->askHidden('Saisir votre mot de passe');

        $user = new User();

        $hash = $this->encoder->encodePassword($user, $password);

        $user
             ->setFirstname($firstname)
             ->setLastname($lastname)
             ->setEmail($mail)
             ->setPassword($hash)
             ->setCreatedDate(new DateTime())
             ->setUpdateDate(new DateTime());

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('Enregistrement avec succès');

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('symfchabrack@gmail.com')
            ->setTo('symfchabrack@gmail.com')
            ->setBody("Test Email", 'text/html')
        ;

        
        $this->mailer->send($message);

        $output->writeln("Bonjour ".$firstname.", votre compte a été créé");
    
    }
    
    
}