<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
* Définit les propriétés de l'entité Panier (non persistée)
*
*/
class Panier
{
  /**
  * @var int
  */
  private $total;

  /**
  * @var array
  */
  private $produits;

  public function __construct()
  {
    $this->produits = new ArrayCollection();
  }


  public function getTotal(): ?int
  {
    return $this->total;
  }


  public function setTotal(): self
  {
    $this->total = 0;
    foreach ($this->produits as $produit) {
      $this->total += $produit->getPrix() * $produit->getQuantite();
    }
    return $this;
  }


  public function getProduits()
  {
    return $this->produits;
  }


  public function getProduit($id)
  {
    foreach ($this->produits as $cle => $panierProduit) {
      if ($panierProduit->getId() == $id) {
        return $panierProduit;
      }
    }
    return false;
  }


  public function addProduit(Produit $produit, $quantite): self
  {
    $produit->setQuantite($quantite);
    $this->produits[] = $produit;
    return $this;
  }


  public function removeProduit(Produit $produit): self
  {
    if ($this->produits->contains($produit)) {
      $this->produits->removeElement($produit);
      $this->setTotal();
    }
    return $this;
  }


  public function count(): Int
  {
    return $this->produits->count();
  }

}
