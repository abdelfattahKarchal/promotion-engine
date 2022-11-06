<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiryDTO;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\EvenItemsMultiplier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Tests\ServiceTestCase;

class PriceModifiersTest extends ServiceTestCase
{
    /**
     * @test
     */
    public function dateRangeMultiplier_returns_a_correctly_modified_price(): void
    {
        //Given
        $enquiry = new LowestPriceEnquiryDTO();
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');

        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(["from" => "2022-11-25", "to" => "2022-11-28"]);
        $promotion->setType('date_range_multiplier');

        $dateRangeModifier = new DateRangeMultiplier();
        // When
        $modifiedPrice = $dateRangeModifier->modify(100, $enquiry->getQuantity(), $promotion, $enquiry);
        //Then
        $this->assertEquals(250, $modifiedPrice);
    }

    /**
     * @test
     */
    public function fixedPricevoucher_returns_a_correctly_modified_price(): void
    {
        //Given
        $enquiry = new LowestPriceEnquiryDTO();
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode('OU812');

        $promotion = new Promotion();
        $promotion->setName('Voucher OU812');
        $promotion->setAdjustment(100);
        $promotion->setCriteria(["code" => "OU812"]);
        $promotion->setType('fixed_price_voucher');

        $fixedPricevoucher = new FixedPriceVoucher();
        // When
        $modifiedPrice = $fixedPricevoucher->modify(150, $enquiry->getQuantity(), $promotion, $enquiry);
        //Then
        $this->assertEquals(500, $modifiedPrice);
    }

    /**
     * @test
     */
    public function evenItemsMultiplier_returns_a_correctly_modified_price(): void
    {
        //Given
        $enquiry = new LowestPriceEnquiryDTO();
        $enquiry->setQuantity(5);

        $promotion = new Promotion();
        $promotion->setName('Buy one get one free');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(["minimum_quantity" => 2]);
        $promotion->setType('even_items_multiplier');

        $evenItemsMultiplier = new EvenItemsMultiplier();
        // When
        $modifiedPrice = $evenItemsMultiplier->modify(100, $enquiry->getQuantity(), $promotion, $enquiry);
        //Then

        $this->assertEquals(300, $modifiedPrice);
    }
}