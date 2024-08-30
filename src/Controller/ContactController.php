<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        $error = null;  // Définir la variable d'erreur

        if ($form->isSubmitted() && !$form->isValid()) {
            // Gérer les erreurs ici
            $error = 'Il y a des erreurs dans le formulaire. Veuillez vérifier les champs.';
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');

            $mail = new Mail();
            $mail->send(
                'minetoukeita7@gmail.com',           // to
                'MGK-BOUTIQUE',                      // subject
                'Vous avez reçu une nouvelle demande de contact.', // body
                'no-reply@mgkboutique.com'           // from
            );
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error,  // Passer la variable d'erreur au template Twig
        ]);
    }
}
