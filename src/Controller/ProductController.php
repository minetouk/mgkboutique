<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Importation correcte
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search); // Correction du nom de la classe
        } else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show(string $slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products,
        ]);
    }
}
