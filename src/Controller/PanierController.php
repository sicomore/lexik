<?php
namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;


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
    $panier = new Panier;

    if (null !== $request->getSession()->get('panier')) {
      $panier = $request->getSession()->get('panier');
    }

    return $this->render('panier/index.html.twig', [
      'produits' => $panier->getProduits(),
      'nbProduits' => $panier->count(),
      'total' => $panier->getTotal()
    ]);
  }


  /**
  * Vidage complet du panier
  *
  * @Route("/vider", name="panier_vider", methods="DELETE")
  */
  public function vider(Request $request, TranslatorInterface $translator
): Response
  {
    if ($this->isCsrfTokenValid('vider_panier', $request->request->get('_token'))) {
      $request->getSession()->get('panier')->getProduits()->clear();
      $request->getSession()->get('panier')->setTotal();
    }
    $trans = $translator->trans('panier.vider.message');
    $this->addFlash('info', $trans);

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
    $produit = $panier->getProduit((int)$id);

    if ($this->isCsrfTokenValid('supprimer_article'.$id, $request->request->get('_token'))) {
      $panier->removeProduit($produit);
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
      $panierProduits = $session->get('panier')->getProduits();

      foreach ($panierProduits as $cle => $panierProduit) {
        if ($id === $panierProduit->getId()) {
          $panier->getProduit($id)->setQuantite($quantite);
          if ($quantite < 1) {
            $panier->removeProduit($panierProduit);
          }
        }
      }
      $total = $panier->setTotal()->getTotal();
      $session->set('panier', $panier);
      return new Response($total);
    }
  }

}
