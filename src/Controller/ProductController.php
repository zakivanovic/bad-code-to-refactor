<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Psr\Log\LoggerInterface;
use App\Service\ProductTypeService;
use App\Notification\ProductNotification;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product", name="product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/edit/{id}", name="product_update")
     * @Security("is_granted('ROLE_ADMIN'), message="tu dois avoir le role ADMIN pour modifier ce produit.")
     */
    public function updateAction(Request $request, ObjectManager $manager, Product $product, LoggerInterface $logger, ProductTypeService $productype, ProductNotification $notification)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //il faut ajouter le champ LastUpdated dans l'entity Product
            $product->setLastUpdated(new \DateTime);
            $manager->persist($product);
            $manager->flush();
            $logger->info('Saved!');
            $notification->notify($product);
            $productype->logProduct($product);

            return $this->redirectToRoute('product', [
                'id' => $product->getId()
            ]);
        }
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
