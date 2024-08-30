<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Utiliser cette interface
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class AccountPasswordController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request): Response
    {
         $notification= null;

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour changer le mot de passe.');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);//  je demande a mn formulaire d ecouter ma requete 

        if ($form->isSubmitted() && $form->isValid()) {
            //je demande en gros si le form est soumis et valide alors modifie le mdp
            $oldPassword = $form->get('old_password')->getData();//getdata recupere les donné
            $newPassword = $form->get('new_password')->getData();

            // Vérifiez si le mot de passe actuel est valide
            if ($this->passwordHasher->isPasswordValid($user, $oldPassword)) {
                $encodedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($encodedPassword);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification= 'votre mot de passe a bien etait mis a jour .';

                $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
                return $this->redirectToRoute('account_password');
            } else {
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification'=>$notification // si c est ok sur la page account passwor.twig ca va m'afficher votre mot de passe a bien etait mis a jour .

        ]);
    }
}
