<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiryDTO;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Tests\ServiceTestCase;

class LowestPriceFilterTest extends ServiceTestCase {

    /** @test */
    public function lowest_price_promotions_filtering_is_applied_correctly(): void
    {
        // Given
        $product = new Product();
        $product->setPrice(100);
        $enquiry = new LowestPriceEnquiryDTO();
        $enquiry->setProduct($product);
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate("2022-11-27");
        $enquiry->setVoucherCode("OU812");

        $promotions = $this->promotionsDataProvider();
        $lowsetPriceFilter = $this->container->get(LowestPriceFilter::class);
        // When
        $filteredEnquiry = $lowsetPriceFilter->apply($enquiry, ...$promotions);
        // then
        $this->assertSame(100, $filteredEnquiry->getPrice());
        $this->assertSame(250, $filteredEnquiry->getDiscountedPrice());
        $this->assertSame("Black Friday half price sale", $filteredEnquiry->getPromotionName());
    }


    public function promotionsDataProvider(): array
    {
        $promotionOne = new Promotion();
        $promotionOne->setId(1);
        $promotionOne->setName('Black Friday half price sale');
        $promotionOne->setAdjustment(0.5);
        $promotionOne->setCriteria(["from" => "2022-11-25", "to" => "2022-11-28"]);
        $promotionOne->setType('date_range_multiplier');

        $promotionTwo = new Promotion();
        $promotionTwo->setId(2);
        $promotionTwo->setName('Voucher OU812');
        $promotionTwo->setAdjustment(100);
        $promotionTwo->setCriteria(["code" => "OU812"]);
        $promotionTwo->setType('fixed_price_voucher');

        $promotionThree = new Promotion();
        $promotionThree->setId(3);
        $promotionThree->setName('Buy one get one free');
        $promotionThree->setAdjustment(0.5);
        $promotionThree->setCriteria(["minimum_quantity" => 2]);
        $promotionThree->setType('even_items_multiplier');

        return [$promotionOne, $promotionTwo, $promotionThree];
    }
}