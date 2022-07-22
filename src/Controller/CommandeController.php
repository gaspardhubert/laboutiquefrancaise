<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Repository\ProductRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeDetailRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * @Route("/passer-commande", name="passer_commande")
     */

     public function passerCommande(SessionInterface $session, ProductRepository $reproPro, CommandeRepository $repoCom, CommandeDetailRepository $repoDet, EntityManagerInterface $manager)
     {
        // on crée un objet commande pour remplir les informations
        $commande = new Commande();
        $panier = $session->get('panier', []);
        
        //dd($panier);
        // on récupère l'utilisateur en cours
        $user = $this->getUser();

        // s'il n'y a pas d'utilisateur en cours connecté, il ne peut passer commande
        if(!$user)
        {
            $this->addFlash("error", "Veuille vous connecter ou vous inscrire  dans le cas échéant pour pouvoir passer une commande !");
            return $this->redirectToRoute("app_login");
        }

        if(empty($panier))
        {
            $sthis->addFlash("error", "Votre panier est vide, vous ne pouvez pas passer commander.");
            return $this->redirectToRoute ("product_all");
        }

        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $product = $reproPro->find($id);
            $dataPanier[]=
            [
                "product" => $product,
                "quantite" => $quantite,
                "sousTotal" => $product->getPrice() * $quantite
            ];

            $total += $product->getPrice() * $quantite;

        }
;
        $commande->setUser($user)
                ->setDateDeCommande(new DateTime("now"))
                ->setMontant($total);

        $repoCom->add($commande);

        foreach ($dataPanier as $key => $value)
        {
            $commandeDetail = new CommandeDetail();

            $product = $value["product"];
            $quantire = $value["quantite"];
            $sousTotal = $value["sousTotal"];

            $commandeDetail->setCommande($commande)
                            ->setProduct($product)
                            ->setQuantite($quantite)
                            ->setPrix($sousTotal);

            $repoDet->add($commandeDetail);

        }
        
        $manager->flush();

        $session->remove("panier");

        $this->addFlash("succes", "Félicitation, votre a été enregistré.");

    
        return $this->redirectToRoute("app_home");
          
                
     }
}
