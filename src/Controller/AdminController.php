<?php

namespace App\Controller;

use DateTime;
use App\Entity\Product;
use App\Form\UserType;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/ajout-product", name="ajout_product")
     */
    public function ajout(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            $file = $form->get('photoForm')->getData();
            $fileName = $slugger->slug($product->getName()) . uniqid() . '.' . $file->guessExtension();

            
            try{
                $file->move($this->getParameter('photos_products'), $fileName);
            }catch(FileException $e){
                // gerer les exceptions d'upload image
            }
            $product->setIllustration($fileName);

            // $manager = $this->getDoctrine()->getManager();
            

            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute("admin_gestion_products");

        }
    
        return $this->render('admin/formulaire.html.twig', [
            'formProduct' => $form->createView()
        ]);
    }


    /**
     * @Route("/gestion-products", name="gestion_products")
     */
    public function gestionProducts(ProductRepository $repo)
    {
        $products = $repo->findAll();

        return $this->render("admin/gestion-products.html.twig", [
            'products' => $products
        ]);

    }

    /**
     *@Route("/details-product-{id<\d+>}", name="details_product")
     */
    public function detailsProduct($id, ProductRepository $repo)
    {
        $product = $repo->find($id);

        
        return $this->render("admin/details-product.html.twig", [
            'product' => $product
        ]);
    }

    /**
     * @Route("/update-product-{id<\d+>}", name="update_product")
     */
    public function update($id, ProductRepository $repo, Request $request, SluggerInterface $slugger)
    {
        $product = $repo->find($id);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            if($form->get('photoForm')->getData()){
            $file = $form->get('photoForm')->getData();
            $fileName = $slugger->slug($product->getName()) . uniqid() . '.' . $file->guessExtension();

            try{
                $file->move($this->getParameter('photos_products'), $fileName);
            }catch(FileException $e){
                // gerer les exceptions d'upload image
            }
            $product->setIllustration($fileName);
            }
            $repo->add($product, 1);

            return $this->redirectToRoute("admin_gestion_products");
        }

        return $this->render("admin/formulaire.html.twig", [
            'formProduct' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-product-{id<\d+>}", name="delete_product")
     */
    public function delete($id, ProductRepository $repo)
    {
        $product = $repo->find($id);

        $repo->remove($product, 1);

        return $this->redirectToRoute("admin_gestion_products");
    }

    /**
     * @Route("/ajout-category", name="ajout_category")
     */
    public function ajoutCategory(Request $request, CategoryRepository $repo)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $repo->add($category, 1);

            return $this->redirectToRoute("app_home");
        }

        return $this->render("admin/formCategory.html.twig", [
            "formCategory" => $form->createView()
        ]);

    }

    /**
     * @Route("/ajout-user", name="ajout_user")
     */
    public function ajoutUser(Request $request, UserRepository $repo)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $repo->add($user, 1);

            return $this->redirectToRoute("app_home");
        }

        return $this->render("admin/formUser.html.twig", [
            "formUser" => $form->createView()
        ]);



    }

}
