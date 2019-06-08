<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
* @method Produit|null find($id, $lockMode = null, $lockVersion = null)
* @method Produit|null findOneBy(array $criteria, array $orderBy = null)
* @method Produit[]    findAll()
* @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class ProduitRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Produit::class);
  }

  /**
  * Affichage de la liste des produits par ordre croissant
  *
  * @param void
  * @return array
  */
  public function findAllOrderNom()
  {
    $produits = $this->findBy([], ['nom' => 'ASC']);

    return $produits;
  }
}
