<?php

namespace App\Controller;

use App\Entity\Catalog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCatalogController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/api/catalog")
     * Renvoie la liste de toutes les catégories
     */
    public function index(SerializerInterface $serializer)
    {
        $repo = $this->getDoctrine()->getRepository(Catalog::class);

        date_default_timezone_set('UTC');

        $catalogs = $repo->findAll();

        $data = $serializer->serialize($catalogs, 'json', [
            'groups'=>['listCatalog']
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Rest\View()
     * @Rest\Get("/api/catalog/{id}")
     * Renvoi une catégorie grâce à son id
     */
    public function show(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('id');

        $repo = $this->getDoctrine()->getRepository(Catalog::class);

        $catalog = $repo->find($id);

        $data = $serializer->serialize($catalog, 'json', [
            'groups'=>['listCatalog']
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);

    }


    /**
     * @Rest\View()
     * @Rest\Post("/api/catalog")
     * @Security("has_role('ROLE_MANAGER')")
     * Insèrez une nouvelle catégorie
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer){

        $data = $request->getContent();
        $catalog = new Catalog();

        $catalog = $serializer->deserialize($data, Catalog::class, 'json');

        $catalog->setCreatedDate(new \DateTime())
                ->setUpdateDate(new \DateTime())
                ->setCreatedUser(1)
                ->setUpdateUser(2);

        $manager->persist($catalog);
        $manager->flush();

        return new JsonResponse(
            "La catégorie a bien été créée",
            Response::HTTP_CREATED,
            ["location"=>"api/catalog/".$catalog->getId()],
            true);
        
    }


    /**
     * @Rest\View()
     * @Rest\Put("/api/catalog/{id}")
     * @Security("has_role('ROLE_MANAGER')")
     * Modifier une catégorie avec son id
     */
    public function edit(Catalog $catalog, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer){

        $data = $request->getContent();

        $serializer->deserialize($data, Catalog::class, 'json', ['object_to_populate'=>$catalog]);

        $catalog->setUpdateDate(new \DateTime());

        $manager->persist($catalog);
        $manager->flush();

        return new JsonResponse(
            "La catégorie a bien été mise à jour",
            Response::HTTP_OK,
            [],
            true);
        
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/api/catalog/{id}")
     * @Security("has_role('ROLE_ADMIN')")
     * supprime une catégorie
     */
    public function delete(Catalog $catalog, EntityManagerInterface $manager){

        $manager->remove($catalog);
        $manager->flush();

        return new JsonResponse(
            "La catégorie a bien été supprimé",
            Response::HTTP_OK,
            [],
            true);

    }
}
