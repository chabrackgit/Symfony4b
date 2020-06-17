<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Catalog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Rest\View()
     * @Rest\Post("/api/article")
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer){

        $data = $request->getContent();
        $article = new Article();

        $article = $serializer->deserialize($data, Article::class, 'json');


        $article->setCatalog($data['catalog'])
                ->setCreatedDate(new \DateTime())
                ->setUpdateDate(new \DateTime())
                ->setCreatedUser(1)
                ->setUpdateUser(2);

        $manager->persist($article);
        $manager->flush();

        return new JsonResponse(
            "L\'article a bien été créée",
            Response::HTTP_CREATED,
            ["location"=>"api/article/".$article->getId()],
            true);
        
    }


    /**
     * @Rest\View()
     * @Rest\Put("/api/article/{id}")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer){

        $data = $request->getContent();

        $serializer->deserialize($data, Catalog::class, 'json', ['object_to_populate'=>$article]);

        $article->setUpdateDate(new \DateTime());

        $manager->persist($article);
        $manager->flush();

        return new JsonResponse(
            "L\'article a bien été mise à jour",
            Response::HTTP_OK,
            [],
            true);
        
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/api/article/{id}")
     */
    public function delete(Article $article, EntityManagerInterface $manager){

        $manager->remove($article);
        $manager->flush();

        return new JsonResponse(
            "L\' article a bien été supprimé",
            Response::HTTP_OK,
            [],
            true);

    }
}
