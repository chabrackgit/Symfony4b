<?php

namespace App\Controller;

use App\Entity\Catalog;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCatalogController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/api/catalog")
     */
    public function index(SerializerInterface $serializer)
    {
        $repo = $this->getDoctrine()->getRepository(Catalog::class);

        $catalogs = $repo->findAll();

        $data = $serializer->serialize($catalogs, 'json', [
            'groups'=>['listCatalog']
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
