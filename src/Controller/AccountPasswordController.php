<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {   
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/update-password", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null; 

        $user = $this->getUser(); 
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid())
        {
            //récupérer l'ancier mot de passe saisi dans le formulaire
            $old_pwd = $form->get('old_password')->getData();
            if($encoder->isPasswordValid($user, $old_pwd))
            {
                $new_pwd = $form->get('new_password')->getData(); 
                $password = $encoder->encodePassword($user, $new_pwd);
                $user->setPassword($password); 

                $this->entityManager->flush(); 

                $notification = 'Votre mot de passe a bien été changé'; 
            }
            else 
            {
                $notification = 'Mon de passe incorrect';
            }
        }

        return $this->render('account/password.html.twig', [
            'monFormulaire'=>$form->createView(),
            'notif' => $notification
        ]);
    }
}
