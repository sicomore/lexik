<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Repository\ProduitRepository;
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
* @Route("/produits")
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
  public function show($slug, Request $request, TranslatorInterface $translator): Response
  {
    $session = $request->getSession();

    if (null === $session->get('referer')) {
      $referer = $request->server->get('HTTP_REFERER');
      $session->set('referer', $referer);
    }

    $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['slug' => $slug]);

    if (null === $produit) {
      $translation = $translator->trans('produit.not.exists');
      throw $this->createNotFoundException($translation);
    }

    $quantite = intval($request->request->get('quantite'));

    if (isset($quantite) && !empty($quantite)) {
      if (!is_int($quantite) && $quantite <= 1) {
        $translationWarning = $translator->trans('produit.quantite.fausse');
        $this->addFlash('message', $translationWarning);

      } else {
        if ($session->has('panier')) {
          $panier = $session->get('panier');

          if (!$panier->getProduit($produit->getId())) {
            $panier = $panier->addProduit($produit, $quantite);

          } else {
            $produit = $panier->getProduit($produit->getId());
            $produit->setQuantite($produit->getQuantite() + $quantite);
          }

          $translationMajPanier = $translator->trans('produit.panier.maj');
          $this->addFlash('info', $translationMajPanier);

        } else {
          $panier = new Panier();
          $panier = $panier->addProduit($produit, $quantite);
        }

        $panier->setTotal();

        $session->set('panier', $panier);
        $ref = $session->get('referer');
        $session->set('referer', null);
        return $this->redirect($ref);
      }
    }

    return $this->render('produit/show.html.twig', [
      'produit' => $produit,
    ]);
  }

}
