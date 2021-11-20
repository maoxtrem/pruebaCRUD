<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;


class ProductRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function listaProductos()
    {
        $em = $this->getEntityManager();
        $dql = "SELECT P.id,P.code,P.name,P.description,P.brand,C.name as category ,P.price"
            . " FROM App\Entity\Product P "
            . " LEFT JOIN P.category C  "
            . " ORDER BY P.id ASC";
        $query = $em->createQuery($dql);
        return $query->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}