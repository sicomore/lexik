<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;


/**
* Controlleur d'APIs
*/
final class RestController extends FOSRestController
{

  /**
  * API de la liste des produits
  * @Rest\Get("/produits")
  *
  * @param ProduitRepository
  * @return View
  */
  public function getProduits(ProduitRepository $produitRepository):View
  {
    $produits = $produitRepository->findAll();

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
