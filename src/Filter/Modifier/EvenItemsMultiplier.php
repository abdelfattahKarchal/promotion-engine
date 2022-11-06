<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class EvenItemsMultiplier implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        $minimumQuantity = $promotion->getCriteria()['minimum_quantity'];

        if ($quantity < $minimumQuantity) {
            return $price * $quantity;
        }
        // get the odd item (l'article impair)
        $oddItem = $quantity % $minimumQuantity;
        // count how many even items
        $evenCount = $quantity - $oddItem;

        return (($price * $evenCount) * $promotion->getAdjustment()) + ($price * $oddItem);
    }
}