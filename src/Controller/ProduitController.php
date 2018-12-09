<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;


/**
* Gestion des produits
*
* @Route("/produit")
*/
class ProduitController extends Controller
{
  /**
  * Affichage de la liste des produits
  *
  * @Route("/", name="produit_index", methods="GET")
  */
  public function index(Request $request, ProduitRepository $produitRepository): Response
  {
    $produits = $produitRepository->findAllOrderNom();

    return $this->render('produit/index.html.twig', [
      'produits' => $produits,
    ]);
  }


  /**
  * Affichage d'un produit
  *
  * @Route("/{slug}", name="produit_show", methods="GET|POST")
  */
  public function show(Request $request, Produit $produit): Response
  {
    $session = $request->getSession();

    if (null === $session->get('referer')) {
      $referer = $request->server->get('HTTP_REFERER');
      $session->set('referer', $referer);
    }

    if (!$produit) {
      throw $this->createNotFoundException("Ce produit n'existe pas.");
    }

    $quantite = intval($request->request->get('quantite'));
    // var_dump($produit);

    if (isset($quantite) && !empty($quantite)) {
      if (!is_int($quantite) && $quantite <= 1) {
        $this->addFlash('message', 'La quantité renseignée "' . $quantite . '" n\'est pas conforme et doit être supérieure à 0.');

      } else {
        // if ($session->has('panier')) {
        //   $panier = $session->get('panier');



        // if ($panier->has($produit)) {
        $panier = new Panier();
        $panier = $panier->addProduit($produit, $quantite);
        // die(var_dump($panier));
        $session->set('panier', $panier);

        // }

        // foreach ($panier as $cle => $articlePanier) {
        //   if ($produit->getId() === $articlePanier['produitId']) {
        //     $quantitePanier = $articlePanier['produitQuantite'] + $quantite;
        //
        //     $produitMaj[$cle] = [
        //       'produitId' => $articlePanier['produitId'],
        //       'produitNom' => $articlePanier['produitNom'],
        //       'produitSlug' => $articlePanier['produitSlug'],
        //       'produitDescription' => $articlePanier['produitDescription'],
        //       'produitPrix' => $articlePanier['produitPrix'],
        //       'produitQuantite' => $quantitePanier
        //     ];
        //     $panier = array_replace($panier, $produitMaj);
        //   }
        // }
        // }

        // if (!isset($produitMaj)) {
        //   $panier[] = [
        //     'produitId' => $produit->getId(),
        //     'produitNom' => $produit->getNom(),
        //     'produitSlug' => $produit->getSlug(),
        //     'produitDescription' => $produit->getDescription(),
        //     'produitPrix' => $produit->getPrix(),
        //     'produitQuantite' => $quantite
        //   ];
        // }
      }

      // var_dump($session->get('panier'));
      // var_dump($panier);
      // die();


      // $session->set('panier', $panier);
      $this->addFlash('info', 'Votre panier a bien été mis à jour.');

      $ref = $session->get('referer');
      $session->set('referer', null);
      return $this->redirect($ref);
    }

    return $this->render('produit/show.html.twig', [
      'produit' => $produit,
    ]);
  }


  /**
  *
  * @Route("/new", name="produit_new", methods="GET|POST")
  */
  // public function new(Request $request): Response
  // {
  //   $produit = new Produit();
  //   $form = $this->createForm(ProduitType::class, $produit);
  //   $form->handleRequest($request);
  //
  //   if ($form->isSubmitted() && $form->isValid()) {
  //     $em = $this->getDoctrine()->getManager();
  //     $em->persist($produit);
  //     $em->flush();
  //
  //     return $this->redirectToRoute('produit_index');
  //   }
  //
  //   return $this->render('produit/new.html.twig', [
  //     'produit' => $produit,
  //     'form' => $form->createView(),
  //   ]);
  // }


  /**
  * @Route("/{id}/edit", name="produit_edit", methods="GET|POST")
  */
  // public function edit(Request $request, Produit $produit): Response
  // {
  //   $form = $this->createForm(ProduitType::class, $produit);
  //   $form->handleRequest($request);
  //
  //   if ($form->isSubmitted() && $form->isValid()) {
  //     $this->getDoctrine()->getManager()->flush();
  //
  //     return $this->redirectToRoute('produit_index', ['id' => $produit->getId()]);
  //   }
  //
  //   return $this->render('produit/edit.html.twig', [
  //     'produit' => $produit,
  //     'form' => $form->createView(),
  //   ]);
  // }

  /**
  * Suppression d'un produit
  *
  * @Route("/{id}", name="produit_delete", methods="DELETE")
  */
  // public function delete(Request $request, Produit $produit): Response
  // {
  //   if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
  //     $em = $this->getDoctrine()->getManager();
  //     $em->remove($produit);
  //     $em->flush();
  //   }
  //
  //   return $this->redirectToRoute('produit_index');
  // }
}
