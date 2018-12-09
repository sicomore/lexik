<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;



/**
* @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
*/
class Panier
{
  private $total;
  private $produits;

  public function __construct()
  {
    $this->produits = new ArrayCollection();

    // if (!$this->has($produit)) {
    //   $produit->quantite = $quantite;
    //   $this->setProduits($produit);
    // } else {
    // foreach ($this->produits as $panierProduit) {
    //   if ($panierProduit['id'] === $produit->getId()) {
    //     $this->produits->quantite = $quantite;
    //   }
    // }
    // }
    // $this->setTotal($this->produits);
  }

  public function getTotal(): ?int
  {
    return $this->total;
  }

  public function setTotal($produits): self
  {
    foreach ($produits as $produit) {
      $this->total += $produit['prix'] * $produit->quantite;
    }
    return $this;
  }

  public function getProduits(): ?Collection
  {
    return $this->produits;
  }

  // public function setProduits($produit): self
  // {
  //   if (!$this->produits->contains($produit)) {
  //     $this->produits[] = $produit;
  //   }
  //   return $this;
  // }

  public function addProduit(Produit $produit, $quantite): self
  {
    $produit->quantite = $quantite;
    if (!$this->produits->contains($produit)) {
      $this->produits[] = $produit;
    }
    return $this;
  }

  public function removeProduit(Produit $produit): self
  {
    if ($this->produits->contains($produit)) {
      $this->produits->removeElement($produit);
    }
    return $this;
  }

  public function has(Produit $produit): bool
  {
    foreach ($this->produits as $panierProduit) {
      if ($produit['id'] === $panierProduit['id']) {
        return true;
      }
    }
    return false;
  }

  public function vider()
  {
    $this->produits = [];
    $this->total = 0;
    $session->set('panier', $this);
  }

  // public function supprimer(Produit $produitSupp)
  // {
  //   foreach ($this->produits as $cle => $produit) {
  //     if ($produit['id'] === $produitSupp['id']) {
  //       unset($this->produits[$cle]);
  //       $session->set('panier', $this);
  //     }
  //   }
  // }
}

// produit controller
// if ($session->has('panier')) {
//   $panier = $session->get('panier');
//
//   if ($panier->has($produit)) {
//     $panier = new Panier($produit);
//   }

// panier controller
// if (null !== $request->getSession()->get('panier')) {
//   $panier = $request->getSession()->get('panier');
//   $total = $panier->getTotal();
// }
