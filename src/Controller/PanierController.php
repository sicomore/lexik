<?php
namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
* @Route("/panier")
*/
class PanierController extends Controller
{

  /**
  * Affichage du panier
  *
  * @Route("/", name="panier_index", methods="GET|POST")
  */
  public function index(Request $request): Response
  {
    $total = 0;
    $panier = null;

    // var_dump( $request->getSession()->get('panier'));
    if (null !== $request->getSession()->get('panier')) {
      $panier = $request->getSession()->get('panier');

      $total = $this->total($panier);
      // foreach ($panier as $article) {
      // $total += $article['produitPrix'] * $article['produitQuantite'];
      // }
    }

    return $this->render('panier/index.html.twig', [
      'panier' => $panier,
      'total' => $total
    ]);
  }


  /**
  */
  public function total($panier)
  {
    $total = 0;

    foreach ($panier as $article) {
      $total += $article['produitPrix'] * $article['produitQuantite'];
    }

    return $total;
  }


  /**
  * Vidage complet du panier
  *
  * @Route("/vider", name="panier_vider", methods="DELETE")
  */
  public function vider(Request $request): Response
  {
    if ($this->isCsrfTokenValid('vider_panier', $request->request->get('_token'))) {
      $request->getSession()->set('panier', []);
    }
    $this->addFlash('info', 'Votre panier a bien été vidé.');

    return $this->redirectToRoute('produit_index');
  }


  /**
  * Suppression d'un article dans le panier
  *
  * @Route("/{id}", name="panier_supprimer", requirements={"id" = "\d+"}, methods="DELETE")
  */
  public function supprimer(Request $request, $id): Response
  {
    $session = $request->getSession();
    $panier = $session->get('panier');

    if ($this->isCsrfTokenValid('supprimer_article'.$id, $request->request->get('_token'))) {
      foreach ($panier as $cle => $article) {
        if ($id == $article['produitId']) {
          unset($panier[$cle]);
          $session->set('panier', $panier);
          $this->addFlash('info', 'L\'article bien été supprimé de votre panier.');
        }
      }
      return $this->redirectToRoute('panier_index');
    }

    return $this->redirectToRoute('produit_index');
  }


  /**
  * Modification des quantités dans le panier
  *
  * @Route("/ajax", name="panier_ajax", methods="GET|POST")
  */
  public function ajaxQuantite(Request $request)
  {
    if (null !== $request->request->get('id') && null !== $request->request->get('quantite') && 0 < $request->request->get('quantite')) {
      $id = (int)$request->request->get('id');
      $quantite = (int)$request->request->get('quantite');
      $session = $request->getSession();
      $panier = $session->get('panier');
      foreach ($panier as $cle => $produit) {
        if ($id == $produit['produitId']) {
          $panier[$cle]['produitQuantite'] = $quantite;
          if ($quantite < 1) {
            unset($panier[$cle]);
          }
          $session->set('panier', $panier);
        }
      }
      $total = $this->total($panier);
      return new Response($total);
    }
  }

}
