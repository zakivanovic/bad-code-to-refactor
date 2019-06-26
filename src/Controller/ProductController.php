<?php


namespace App\Controller;

use App\Service\FoodProductService;
use App\Service\HightechProductService;
use App\Service\OfficeProductService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/product", name="product")
 */
class ProductController  extends AbstractController
{
    public $logger, $service1, $service2, $service3;

    public function __construct()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->service1 = new HightechProductService($entityManager);
        $this->service2 = new FoodProductService($entityManager);
        $this->service3 = new OfficeProductService($entityManager);
    }

    /**
     * @Route("/edit/{id}", name="product_update", methods={"GET"})
     */
    public function updateAction($id)
    {
        $this->logger = $this->container->get('logger');
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('App:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $request = $this->getRequest();
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $product->setLastUpdated(new \DateTime);
            $em->flush();
            $this->logger->info('Saved!');

            $message = \Swift_Message::newInstance()
                ->setSubject('Product updated')
                ->setFrom($this->container->get('product_email.from'))
                ->setTo($this->container->get('product_email.to'))
                ->setBody($this->render(
                    'product/email.txt.twig',
                    array('product' => $product))
                )
            ;
            $this->get('mailer')->send($message);

            $this->logProduct($product);

            return $this->redirect(
                $this->generateUrl('product', array('id' => $id))
            );
        }

        return $this->render(
            'product/edit.html.twig',
            array(
                'product'      => $product,
                'form'   => $productForm->createView(),
            )
        );
    }

    /**
     *
     */
    private function logProduct($product)
    {
        $message = 'produit type '; // @todo internationaliser ce message
        if($this->service1->checkProductType($product)) {
            $this->logger->info($message . 'Hightech');
        }
        if($this->service2->checkProductType($product)) {
            $this->logger->info($message .  'Food');
        }
        if($this->service3->checkProductType($product)) {
            $this->logger->info($message .  'Office');
        }
    }
}