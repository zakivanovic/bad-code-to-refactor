<?php


namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class OfficeProductService
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
        if($type === 'Stylo') {
            return true;
        } else {
            if($type === 'post-it') {
                return true;
            } else {
                if($type === 'Cahier') {
                    return true;
                } else {
                    if($type === 'agrafeuse') {
                        return true;
                    }
                }
            }
        }
    }
}