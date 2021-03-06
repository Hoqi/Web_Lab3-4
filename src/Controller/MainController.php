<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)
                        ->findAllProducts();

        return $this->render('main\index.html.twig', [
            'controller_name' => 'MainController',
            'products' => $products
        ]);
    }
}
