<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

      $produits = [
        ['nom' => 'Pull en laine', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 45],
        ['nom' => 'Pantalon gris', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 67],
        ['nom' => 'Chemise en lin', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 75],
        ['nom' => 'Short à fourrure', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 80],
        ['nom' => 'Pantalon en sky', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 77],
        ['nom' => 'Chaussures en croco', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 56],
        ['nom' => 'Chaussettes écossaises', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 98],
        ['nom' => 'Gants en cuir', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 45],
        ['nom' => 'Chapeau de paille', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 66],
        ['nom' => 'Bonnet en latex', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 74],
        ['nom' => 'Écharpe en soie', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 56],
        ['nom' => 'Soutien-gorge en cuir', 'description' => 'Lorem ipsum et caetera dummy text and so on', 'prix' => 55]
      ];
      foreach ($produits as $produit) {
        $unProduit = new Produit();
        $unProduit->setNom($produit['nom']);
        $unProduit->setDescription($produit['description']);
        $unProduit->setPrix($produit['prix']);
        $manager->persist($unProduit);
      }
      $manager->flush();
    }
}
