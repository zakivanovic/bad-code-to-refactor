<?php


namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class HightechProductService
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
        if($type === 'TV') {
            return true;
        } else {
            if($type === 'smartphone') {
                return true;
            } else {
                if($type === 'PC') {
                    return true;
                } else {
                    if($type === 'casque bluetooth') {
                        return true;
                    }
                }
            }
        }
    }
}