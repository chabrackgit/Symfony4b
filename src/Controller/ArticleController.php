<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Catalog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/new", name="article_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Article();

        $form = $this ->createFormBuilder($article)
                      ->add('name')
                      ->add('description')
                      ->add('price')
                      ->add('catalog', EntityType::class, [
                        'class' => Catalog::class,
                        'choice_label' => 'name' ])
                      ->add('save', SubmitType::class, ['label' => 'Créer article'])
                      ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article->setcreatedDate(new \Datetime());
            $article->setupdateDate(new \Datetime());
            $article->setCreatedUser(1);
            $article->setUpdateUser(1);
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('catalogs');
        }

            
        return $this->render('article/create.html.twig',[
            'controller_name' => 'Création nouvel article',
            'formArticle' => $form->createView()
            
        ]);

    }

   
    /**
     * @Route("/articles/catalog/{id}", name="articles")
     */
    public function listArticleByCategory(Request $request, EntityManagerInterface $entityManager)
    {
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        $id = $request->get('id');

        $articles = $repoArticle->findBy(['catalog'=>$id]);

        return $this->render('article/list.html.twig',[
            'controller_name' => 'liste des articles par catégories',
            'articles' => $articles
            
        ]);

    }
}
