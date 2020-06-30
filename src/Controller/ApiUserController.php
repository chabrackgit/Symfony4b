<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiUserController extends AbstractController
{

    /**
     * @Rest\View()
     * @Rest\Post("/api/inscription")
     * permet à un utilisateur de s'inscrire
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder){

        $data = $request->getContent();
        $user = new User();

        $user = $serializer->deserialize($data, User::class, 'json');

        $hash = $encoder->encodePassword($user, $user->getPassword());

        $user   ->setPassword($hash)
                ->setCreatedDate(new \DateTime())
                ->setUpdateDate(new \DateTime());

        $manager->persist($user);
        $manager->flush();

        return new JsonResponse(
            "Votre compte a bien été créée",
            Response::HTTP_CREATED,
            [],
            true);
        
    }

}
