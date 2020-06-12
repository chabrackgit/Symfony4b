<?php

namespace App\Controller;

use App\Entity\Catalog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogController extends AbstractController
{
    /**
     * @Route("/catalog", name="catalogs")
     */
    public function listAllCatalog()
    {
        $repo = $this->getDoctrine()->getRepository(Catalog::class);

        $catalogs = $repo->findAll();

        return $this->render('catalog/index.html.twig', [
            'controller_name' => 'liste des catégories',
            'catalogs' => $catalogs
        ]);
    }

     /**
     * @Route("/catalog/new", name="catalog_create")
     * @Route("/catalog/{id}/edit", name="catalog_edit")
     */
    public function create(Catalog $catalog, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$catalog){
            $catalog = new Catalog();
        }
        
        $catalog->setcreatedDate(new \Datetime());
        $catalog->setupdateDate(new \Datetime());
        $catalog->setCreatedUser(1);
        $catalog->setUpdateUser(2);


        $form = $this ->createFormBuilder($catalog)
                      ->add('name')
                      ->add('description')

                      ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($catalog);
            $entityManager->flush();
        }

            
        return $this->render('catalog/create.html.twig',[
            'controller_name' => 'Création nouvelle catégorie',
            'formCatalog' => $form->createView()
            
        ]);

    }


    /**
     * @Route("/catalog/{id}", name="catalog")
     */
    public function listCatalog($id)
    {
        $repo = $this->getDoctrine()->getRepository(Catalog::class);

        $catalog = $repo->find($id);

        return $this->render('catalog/show.html.twig', [
            'controller_name' => 'informations catégorie '.$id,
            'catalog' => $catalog
        ]);
    }


   
}
