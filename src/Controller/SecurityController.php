<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $user = New User();
        
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            
            $user->setCreatedDate(new \DateTime());
            $user->setUpdateDate(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('security_login');
            }

        return $this->render('security/registration.html.twig',[
            'form'=> $form->createView()
        ]);  
        
    }


    /**
     * @Route("/edit/profil/{id}", name="security_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $repoUser = $this->getDoctrine()->getRepository(User::class);

        $id = $request->get('id');

        $user =  $repoUser->find($id);

        $form = $this->createFormBuilder($user)
                     ->add('email', EmailType::class, [
                         'disabled'=> 1
                     ])
                     ->add('password', PasswordType::class)
                     ->add('save', SubmitType::class, ['label' => 'Mettre à jour'])
                     ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setpasswordConfirm($user->getpassword());
            $user->setUpdateDate(new \Datetime());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('homeindex');
        }
            
        return $this->render('security/update.html.twig',[
            'formUser' => $form->createView()
            
        ]);

    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'error' => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){}
}
