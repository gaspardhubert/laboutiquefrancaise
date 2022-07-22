<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repo): Response
    {
        $derniersProducts = $repo->findBy([]);

        return $this->render('home/index.html.twig', [
            "products" => $derniersProducts
        ]);
    }
}
