<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/product", name="product")
 */
class ProductController  extends AbstractController
{
    /**
     * @Route("/edit/{id}", name="product_update", methods={"GET"})
     */
    public function updateAction($id)
    {
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
}