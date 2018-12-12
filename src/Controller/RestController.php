<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;


/**
* Gestion des produits
*/
final class RestController extends FOSRestController
{

  /**
  * API de la liste des produits
  * @Rest\Get("/produits")
  * @return View
  */
  public function getProduits(ProduitRepository $produitRepository):View
  {
    $produits = $produitRepository->findAllOrderNom();

    $jsonFormat = [];

    foreach ($produits as $produit) {
      $jsonFormat[] = [
        'id' => $produit->getId(),
        'nom' => $produit->getNom(),
        'slug' => $produit->getSlug(),
        'description' => $produit->getDescription(),
        'prix' => $produit->getPrix(),
        'image' => $produit->getImage(),
        'cree_le' => $produit->getCreatedAt(),
        'maj_le' => $produit->getUpdatedAt()
      ];
    }

    if (empty($jsonFormat)) {
      return new JsonResponse('Aucun produit trouvé.', Response::HTTP_NOT_FOUND);
    }

    return View::create($jsonFormat, Response::HTTP_OK);
  }

}
