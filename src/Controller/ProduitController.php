<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/produit")
*/
class ProduitController extends Controller
{
  /**
  * @Route("/", name="produit_index", methods="GET")
  */
  public function index(ProduitRepository $produitRepository): Response
  {

    // $session = $request->getSession();

    $produits = $produitRepository->findAllOrderNom();

    // if ($session->has('message')) {
    //   $message = $session->get('message');
    // }

    return $this->render('produit/index.html.twig', [
      'produits' => $produits,
    ]);
  }


  /**
  * @Route("/{id}", name="produit_show", methods="GET|POST")
  */
  public function show(Request $request, Produit $produit): Response
  {
    // $message = null;
    $session = $request->getSession();

    var_dump($session->get('panier'));
    // var_dump($session);

    if (!$produit) {
      throw $this->createNotFoundException("Le produit n°" . $produit->getId() . " n'existe pas.");
    }

    $quantite = intval($request->request->get('quantite'));

    if (isset($quantite) && !empty($quantite)) {
      if (!is_int($quantite) && $quantite <= 1) {
        $this->addFlash('message', 'La quantité renseignée "' . $quantite . '" n\'est pas conforme et doit être supérieure à 0.');

      } else {
        if ($session->has('panier')) {
          $panier = $session->get('panier');

          foreach ($panier as $cle => $produitPanier) {
            if ($produit->getId() === $produitPanier['produitId']) {
              $quantitePanier = $produitPanier['produitQuantite'] + $quantite;

              $produitMaj[$cle] = [
                'produitId' => $produitPanier['produitId'],
                'produitPrix' => $produitPanier['produitPrix'],
                'produitQuantite' => $quantitePanier
              ];
              $panier = array_replace($panier, $produitMaj);
            }
          }
        }

        if (!isset($produitMaj)) {
          $panier[] = [
            'produitId' => $produit->getId(),
            'produitPrix' => $produit->getPrix(),
            'produitQuantite' => $quantite
          ];
        }
      }

      $session->set('panier', $panier);
      $this->addFlash('info', 'Votre panier a bien été mis à jour.');
      return $this->redirectToRoute('produit_index');
    }

    return $this->render('produit/show.html.twig', [
      'produit' => $produit
    ]);
  }


  /**
  * @Route("/panier", name="produit_panier", methods="GET")
  */
  public function panier(Session $session): Response
  {
    // $session = $request->getSession();
    $panier = $session->get('panier');
    var_dump($panier);

    return $this->render('produit/panier.html.twig', ['panier' => $panier]);
  }


  /**
  * @Route("/new", name="produit_new", methods="GET|POST")
  */
  public function new(Request $request): Response
  {
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($produit);
      $em->flush();

      return $this->redirectToRoute('produit_index');
    }

    return $this->render('produit/new.html.twig', [
      'produit' => $produit,
      'form' => $form->createView(),
    ]);
  }


  /**
  * @Route("/{id}/edit", name="produit_edit", methods="GET|POST")
  */
  public function edit(Request $request, Produit $produit): Response
  {
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('produit_index', ['id' => $produit->getId()]);
    }

    return $this->render('produit/edit.html.twig', [
      'produit' => $produit,
      'form' => $form->createView(),
    ]);
  }

  /**
  * @Route("/{id}", name="produit_delete", methods="DELETE")
  */
  public function delete(Request $request, Produit $produit): Response
  {
    if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($produit);
      $em->flush();
    }

    return $this->redirectToRoute('produit_index');
  }
}
