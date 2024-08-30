<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Classe\Search;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requête qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
    public function findWithSearch(Search $search): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->leftJoin('p.category', 'c'); // Correction du nom de l'association

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if (!empty($search->string)) {
            $query = $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', '%' . $search->string . '%'); // Correction de la syntaxe
        }

        return $query->getQuery()->getResult();
    }
}
