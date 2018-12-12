<?php

namespace App\Tests\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProduitControllerTest extends WebTestCase
{
  /**
  * Unit test du total du panier
  */
  public function testIndex()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $this->assertEquals(12, $crawler->filter('.ligne_produit')->count());

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }
}
