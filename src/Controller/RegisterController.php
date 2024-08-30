<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Classe\Mail; 



class RegisterController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // cette fnction recherche si le mail n'existe pas si il existe pas il soumet la condition
            $searchEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if (!$searchEmail) {
                // Encoder le mot de passe
                $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);

               //enregistré les info dans la base de donné
                $this->entityManager->persist($user);
                $this->entityManager->flush();

               
                $mail = new Mail(); 
                // Préparer le contenu de l'email
            
             $content = 'Bonjour ' . $user->getFirstname() . "<br/> Bienvenue sur la page MGK-BOUTIQUE";
                //envoie un mail a utilisateur 
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue chez MGK-BOUTIQUE', $content);
                //et le cntenu du mail
                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez vous connecter à votre compte.";
            } else {
                $notification = "L'email que vous avez renseigné existe déjà.";
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
