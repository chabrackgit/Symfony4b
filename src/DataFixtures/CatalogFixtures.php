<?php

namespace App\DataFixtures;

use App\Entity\Catalog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CatalogFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1; $i<=4; $i++){

            $catalog = new Catalog();
            $catalog ->setName("Catégorie n° ".$i)
                     ->setDescription("description de la catégorie ".$i)
                     ->setCreatedDate(new \DateTime)
                     ->setUpdateDate(new \DateTime)
                     ->setCreatedUser(1)
                     ->setUpdateUser(2);

            $manager->persist($catalog);
            
        }

        $manager->flush();
    }
}
