<?php

namespace App\Controller;

use DateTime;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
	/**
	 * @Route("/product/{id<\d+>}", name="product_show")
	 */

	public function show($id, ProductRepository $repo)
	{
		$product = $repo->find($id);

		return $this->render("products/show.html.twig", [
			'product' => $product
		]);
	}
	
    /**
	 * @Route("/products", name="products_all")
	 */
       public function all(ProductRepository $repoPro, CategoryRepository $repoCat)
    {
        $products = $repoPro->findAll();
		$categories = $repoCat->findAll();

        return $this->render('products/all.html.twig', [
            'products' => $products,
			'categories' => $categories
        ]);
    }

	 /**
	 * @Route("/category-{id<\d+>}", name="products_category")
	 */
       public function categoryProducts($id, CategoryRepository $repo)
    {
		//on récupère la catégorie sur laquelle on a cliqué pour accéder aux products liés
        $category = $repo->find($id);
		// on récupère toutes les catégories pour les afficher dans la liste sur la page
		$categories = $repo->findAll();

        return $this->render('products/all.html.twig', [
			// on récupère les produits de la catégorie cliqué grâce à la page
            'products' => $category->getProducts(),
			'categories' => $categories
        ]);
    }

}

