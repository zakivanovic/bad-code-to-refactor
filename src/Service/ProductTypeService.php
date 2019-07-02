<?php

namespace App\Service;

use App\Entity\Product;
use Psr\Log\LoggerInterface;

class ProductTypeService 
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Permer de logger le type d'un produit s'il est active
    */
    public function logProduct(Product $product)
    {
        if ($product->isActive()) {
            //il faut ajouter le champ Type de produit dans l'entity Product pour le choper avec getType 
            $message = 'produit type :' . $product->getType() ;
            $this->logger->info($message);
        }     
    }
}