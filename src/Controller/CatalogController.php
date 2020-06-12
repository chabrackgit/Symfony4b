<?php

namespace App\Controller;

use App\Entity\User;
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

        return $this->render('catalog/list.html.twig', [
            'controller_name' => 'liste des catégories',
            'catalogs' => $catalogs
        ]);
    }

     /**
     * @Route("/catalog/new", name="catalog_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {


        $catalog = new Catalog();

        $form = $this ->createFormBuilder($catalog)
                      ->add('name')
                      ->add('description')

                      ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $catalog->setcreatedDate(new \Datetime());
            $catalog->setupdateDate(new \Datetime());
            $catalog->setCreatedUser(1);
            $catalog->setUpdateUser(2);
            $entityManager->persist($catalog);
            $entityManager->flush();
            return $this->redirectToRoute('catalogs');
        }

            
        return $this->render('catalog/create.html.twig',[
            'controller_name' => 'Création nouvelle catégorie',
            'formCatalog' => $form->createView()
            
        ]);

    }

    /**
     * @Route("/catalog/edit/{id}", name="catalog_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager)
    {
        $repoCatalog = $this->getDoctrine()->getRepository(Catalog::class);

        $id = $request->get('id');

        $catalog =  $repoCatalog->find($id);

        $form = $this ->createFormBuilder($catalog)
                      ->add('name')
                      ->add('description')

                      ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $catalog->setUpdateDate(new \Datetime());
            $entityManager->persist($catalog);
            $entityManager->flush();
            return $this->redirectToRoute('catalogs');
        }
            
        return $this->render('catalog/update.html.twig',[
            'controller_name' => 'Modification de catégorie '.$catalog->getName(),
            'formCatalog' => $form->createView()
            
        ]);

    }

    /**
     * @Route("/catalog/delete/{id}", name="catalog_delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager)
    {
        $repoCatalog = $this->getDoctrine()->getRepository(Catalog::class);

        $id = $request->get('id');

        $catalog =  $repoCatalog->find($id);

        $entityManager->remove($catalog);
        $entityManager->flush();
        return $this->redirectToRoute('catalogs');


    }





   
}
