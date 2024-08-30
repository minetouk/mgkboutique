<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession(); // Correction du paramètre pour initialiser la session
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // Mettre à jour le panier dans la session
        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function getSession()
    {
        return $this->session;
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        return $this->session->set('cart', $cart);
    }

    public function getFull()
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->entityManager->getRepository(Product::class)->find($id);

                if (!$product_object) {
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity,
                ];
            }
        }

        return $cartComplete;
    }
}
