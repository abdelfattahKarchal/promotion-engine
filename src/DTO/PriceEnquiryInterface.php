<?php

namespace App\DTO;

use JsonSerializable;

interface PriceEnquiryInterface extends PromotionEnquiryInterface {
    public function setPrice(int $price);
    public function setDiscountedPrice(int $price);
    public function getQuantity(): ?int;
}