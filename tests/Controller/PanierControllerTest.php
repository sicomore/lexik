<?php

namespace App\Tests\Controller;

use App\Controller\PanierController;
use PHPUnit\Framework\TestCase;

class PanierControllerTest extends TestCase
{
  public function testTotalPanier()
  {
    $articlesPanier[0]= [
      'produitPrix' => 33,
      'produitQuantite' => 3
    ];

    $panier = new PanierController();
    $result = $panier->total($articlesPanier);

    $this->assertEquals(99, $result);
  }
}
