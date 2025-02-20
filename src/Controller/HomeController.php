<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\Header;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'page')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers = $this->entityManager->getrepository(Header::class)->findAll();
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'Headers' => $headers
        ]);
    }
}
