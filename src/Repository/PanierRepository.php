<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
* @method Panier|null find($id, $lockMode = null, $lockVersion = null)
* @method Panier|null findOneBy(array $criteria, array $orderBy = null)
* @method Panier[]    findAll()
* @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class PanierRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Panier::class);
  }

}
