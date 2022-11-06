<?php

namespace App\Filter;

use App\DTO\PriceEnquiryInterface;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;
use App\Filter\Modifier\Factory\PriceModifierFactoryInterfce;

class LowestPriceFilter implements PriceFilterInterface
{
    public function __construct(private PriceModifierFactoryInterfce $priceModifierFactory)
    {
    }

    public function apply(PriceEnquiryInterface $enquiry, Promotion ...$promotions): PriceEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $enquiry->setPrice($price);
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $quantity * $price;
        // loop over the promotions
        foreach ($promotions as $promotion) {
            // Run the promotions modification logic against the enquiry
            // 1. check does the promotion apply e.g is it in date range / is the code voucher valid ?
            // 2. Apply the price modification to obtain the $modifiedPrice (how?)

            $priceModifier = $this->priceModifierFactory->create($promotion->getType());
            $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);
            // 3. Check IF $modifiedPrice < $lowestPrice
            if ($modifiedPrice < $lowestPrice) {
                // 1. Save to Enquiry properties
                $enquiry->setDiscountedPrice($modifiedPrice)
                    ->setPromotionId($promotion->getId())
                    ->setPromotionName($promotion->getName());
                // 2. Update $lowestPrice
                $lowestPrice = $modifiedPrice;
            }
        }
        return $enquiry;
    }
}
