<?php

namespace App\Tests\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ProduitControllerTest extends WebTestCase
{
  /**
  * Test fonctionnel de la liste des produits
  */
  public function testIndex()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $this->assertEquals(12, $crawler->filter('.ligne_produit')->count());

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }
}
