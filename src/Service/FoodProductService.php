<?php


namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class FoodProductService
{
    public $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function checkProductType($product) {
        if($product) {
            if(isValid($product)) {
                $result = $this->type_Ok($product->getType());
            }
        } else {
            throw \Exception('no product');
        }
        return $result;
    }

    public function isValid($product) {
        return $this->em->contains($product) && $product->isActive();
    }

    public function type_Ok(string $type) {
        if($type === 'Carottes') {
            return true;
        } else {
            if($type === 'Viande boeuf') {
                return true;
            } else {
                if($type === 'Chips') {
                    return true;
                } else {
                    if($type === 'Chocolat') {
                        return true;
                    }
                }
            }
        }
    }
}