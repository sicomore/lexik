<?php

namespace App\Tests\Controller;

use App\Controller\ProduitController;
use PHPUnit\Framework\TestCase;

class ProduitControllerTest extends TestCase
{
  public function testIndexProduit()
  {

    $panier = new PanierController();
    $result = $panier->total($articlesPanier);

    $this->assertEquals(99, $result);
  }
}
