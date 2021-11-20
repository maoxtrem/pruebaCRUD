<?php

namespace App\Repository;


use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }


    public function listCategories()
    {
        $em = $this->getEntityManager();
        $dql = "SELECT C.id,C.name,C.active"
            . " FROM App\Entity\Category C "
            . " ORDER BY C.id ASC";
        $query = $em->createQuery($dql);
        return $query->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}