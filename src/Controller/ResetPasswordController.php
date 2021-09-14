<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 
    }
    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        if($this->getUser())
        {
            $this->redirectToRoute('home');
        }

        if($request->get('email'))
        {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user)
            {
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush(); 

                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);
            
                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."<br/>";
                $content .= "Merci de cliquer sur le lien suivant pour mettre à jour votre <a href='".$url."'>mot de passe<a/>"; 

                $mail->send($user->getEmail(), $user->getFirstname(), 'Réinitialiser votre mot de passe', $content);
                
                $this->addFlash('notice', 'Vous allez recevoir un mail de confirmation'); 
            }
        }else
        {
            $this->addFlash('notice', 'Adresse mail inconnu'); 
        }

        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-le-mot-de-passe/{token}", name="update_password")
     */
    public function update($token, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findByToken($token);

        if(!$reset_password)
        {
            $this->redirectToRoute('reset_password');
        }

        $now = new \DateTime();
        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour'))
        {
            $this->addFlash('notice', 'Votre demande de mot de passe a exprié. Veuillez renouveler');
            return $this->redirectToRoute('reset_password');
        }

        //rendre une vue pour le mot de passe
        $form = $this->createForm(ResetPasswordType::class); 
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $new_pwd = $form->get('new_password')->getData(); 

            //encodage du mot de passe
            $password = $encoder->encodePassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);
            
            $this->entityManager->flush(); 

            //redirection
            $this->addFlash('notice', 'Votre mot de passe a été mis à jour');
            return $this->redirectToRoute('app_login'); 
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
