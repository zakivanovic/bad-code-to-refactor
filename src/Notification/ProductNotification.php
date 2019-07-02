<?php

namespace App\Notification;

use APP\Entity\Product;
use Twig\Enviroment;


class ProductNotification 
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     * @var Enviroment
     */
    private $renderer;

    public function __construct(\Swiftailer $mailer,Enviroment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }    

    /**
     * Permet d'envoyer un email quand un produit est modifé
     */
    public function notify(Product $product)
    {
        $message = (new \Swift_Message('message :' . $product->getTitle()))
                ->setSubject('Product updated')
                ->setFrom("")
                ->setTo("")
                ->setBody($this->renderer->render(
                    'product/email.html.twig',[
                        'product' => $product
                    ],'text/html')
                )
            ;
        $this->mailer->send($message);
    }
}