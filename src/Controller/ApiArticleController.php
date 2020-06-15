<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiArticleController extends AbstractController
{
    /**
     * @Rest\View()
     * @Rest\Get("/api/articles")
     */
    public function index(SerializerInterface $serializer)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        $data = $serializer->serialize($articles, 'json', [
            'groups'=>['listArticles']
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
