<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddProductFormType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class AdminProductController extends AbstractController
{
    /**
     * @Route("/add_product", name="add_product")
     */
    public function index(Request $request)
    {
        if ($this->getUser() && $this->getUser()->getRoles()[0] != "ROLE_ADMIN") {
            var_dump($this->getUser()->getRoles()[0]);
            return $this->redirectToRoute('main');
        }
        $Product = new Product();
        $form = $this->createForm(AddProductFormType::class, $Product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Product->setDate(new \DateTime());
            $Product->setRate(0);
            $Product->setRateCount(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Product);
            $entityManager->flush();
            return $this->redirectToRoute('main');
        }

        return $this->render('add_product/index.html.twig', [
            'addProductForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/edit_product{id}", name="edit_product")
     */
    public function editProduct(Request $request,$id){
        if ($this->getUser() && $this->getUser()->getRoles()[0] != "ROLE_ADMIN") {
            var_dump($this->getUser()->getRoles()[0]);
            return $this->redirectToRoute('main');
        }
        $Product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(AddProductFormType::class, $Product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Product);
            $entityManager->flush();
            return $this->redirectToRoute('main');
        }

        return $this->render('add_product/index.html.twig', [
            'addProductForm' => $form->createView(),
        ]);
    }
}
