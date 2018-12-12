<?php

namespace App\Tests\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use PHPUnit\Framework\TestCase;

class PanierControllerTest extends TestCase
{
  /**
  * Unit test du total du panier
  */
  public function testTotalPanier()
  {
    $panier = new Panier();
    $produit1 = new Produit();
    $produit2 = new Produit();
    $produit3 = new Produit();

    $produit1
    ->setNom('Bottes')
    ->setDescription('Eiusmod landjaeger filet mignon, nisi buffalo ipsum burgdoggen jowl pork belly tenderloin cow mollit dolore in dolor.')
    ->setPrix(88);
    $panier->addProduit($produit1, 2);

    $produit2
    ->setNom('Chaussures')
    ->setDescription('Eiusmod landjaeger filet mignon, nisi buffalo ipsum burgdoggen jowl pork belly tenderloin cow mollit dolore in dolor.')
    ->setPrix(122);
    $panier->addProduit($produit2, 1);

    $produit3
    ->setNom('Chaussettes')
    ->setDescription('Eiusmod landjaeger filet mignon, nisi buffalo ipsum burgdoggen jowl pork belly tenderloin cow mollit dolore in dolor.')
    ->setPrix(30);
    $panier->addProduit($produit3, 3);

    $result = $panier->setTotal()->getTotal();

    $this->assertEquals(388, $result);
  }
}
