<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $manager;
    private $encoder;
    private $faker;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->faker = Factory::create("fr_FR");
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadUser();

        $manager->flush();
    }
    
    /**
     * Créations des utilisateurs 
     *
     * @return void
     */
    public function loadUser()
    {
        for($i = 1; $i<100; $i++){
            $user = new User();

            $user->setFirstname($this->faker->firstNameMale());
            $user->setLastname($this->faker->lastName());                             

            $user->setEmail($user->getFirstname().'.'.$user->getLastname().'@cbk.fr');


            $hash = $this->encoder->encodePassword($user, $user->getEmail());
            $user->setPassword($hash)
                 ->setCreatedDate(new \DateTime())
                 ->setUpdateDate(new \DateTime());

            $this->addReference("User ".$i, $user);

            $this->manager->persist($user);
        } 
        
        $user = new User();

        $user->setEmail('boss@cbk.fr');
        
        $hash = $this->encoder->encodePassword($user, $user->getEmail());

        $user
             ->setLastname('BOSS')
             ->setFirstname('Admin')
             ->setPassword($hash)
             ->setRoles(USER::ROLE_ADMIN)
             ->setCreatedDate(new \DateTime())
             ->setUpdateDate(new \DateTime());

        $this->manager->persist($user);

        $user = new User();

        $user->setEmail('manager@cbk.fr');
        
        $hash = $this->encoder->encodePassword($user, $user->getEmail());

        $user
             ->setLastname('MANAGER')
             ->setFirstname('Manager')  
             ->setPassword($hash)
             ->setRoles(USER::ROLE_MANAGER)
             ->setCreatedDate(new \DateTime())
             ->setUpdateDate(new \DateTime());

        $this->manager->persist($user);

        $this->manager->flush();

    }

}
