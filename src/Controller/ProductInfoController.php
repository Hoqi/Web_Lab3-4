<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Feedback;
use App\Form\CommentFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class ProductInfoController extends AbstractController
{
    /**
     * @Route("/product{id}", name="productInfo")
     */
    public function index($id, Request $request)
    {
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);

        $feedbackRepository = $this->getDoctrine()->getRepository(Feedback::class);
        $feedbacks = $feedbackRepository->findByProductIdWithUsername($id);

        if ($this->getUser()) {
            $Feedback = new Feedback();
            $form = $this->createForm(CommentFormType::class, $Feedback);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $Feedback->setDate(new \DateTime());
                $Feedback->setProductId($product);
                $Feedback->setUserId($this->getUser());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Feedback);
                $entityManager->flush();

                //Пересчет рейтинга и кол-ва отзывов
                $newRate = ($product->getRate() * $product->getRateCount() + $Feedback->getRate()) / ($product->getRateCount() + 1);
                $product->setRateCount($product->getRateCount() + 1);
                $product->setRate($newRate);
                $entityManager->persist($product);
                $entityManager->flush();

                return $this->redirectToRoute('productInfo',['id' => $product->getId()]);
            }
            return $this->render('product/index.html.twig', [
                'addFeedbackForm' => $form->createView(),
                'product' => $product,
                'feedbacks' => $feedbacks
            ]);
        }
        return $this->render('product/index.html.twig', [
            'product' => $product,
            'feedbacks' => $feedbacks
        ]);
    }
    /**
     * @Route("/delete{id}_f{idComment}", name="comment_delete")
     */
    public function deleteFeedback($id, $idComment)
    {

        if ($this->getUser() && $this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            $entityManager = $this->getDoctrine()->getManager();

            $commentRep = $this->getDoctrine()->getRepository(Feedback::class);
            $comment = $commentRep->find($idComment);
            
            //Пересчет рейтинга и кол-ва отзывов
            $productRepository = $this->getDoctrine()->getRepository(Product::class);
            $product = $productRepository->find($id);
            if($product->getRateCount() != 1){
            $newRate = ($product->getRate() * $product->getRateCount() - $comment->getRate()) / ($product->getRateCount() - 1);
            $product->setRateCount($product->getRateCount() - 1);
            $product->setRate($newRate);
            }
            else {
                $product->setRateCount(0);
                $product->setRate(0);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('productInfo',['id' => $id]);
    }
}
