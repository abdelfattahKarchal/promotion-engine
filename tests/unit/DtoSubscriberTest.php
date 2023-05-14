<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiryDTO;
use App\Event\AfterDtoCreatedEvent;
use App\EventSubscriber\DtoSubscriber;
use App\Service\ServiceException;
use App\Tests\ServiceTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcherEventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DtoSubscriberTest extends ServiceTestCase
{
    
    /* public function a_dto_is_validated_after_it_has_been_created(): void
    {
        //Given
        $dto = new LowestPriceEnquiryDTO();
        $dto->setQuantity(-5);

        $event = new AfterDtoCreatedEvent($dto);
        $eventDispatcher = $this->container->get('debug.event_dispatcher');

        // Expected
        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('This value should be positive.');
        // When
        $eventDispatcher->dispatch($event, $event::NAME);
        //Then
    } */


    /**
     * @test
     */
    public function testEventSubscription(){
        $this->assertArrayHasKey(AfterDtoCreatedEvent::NAME, DtoSubscriber::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function testValidateDto(){
        //Given
        $dto = new LowestPriceEnquiryDTO();
        $dto->setQuantity(-5);

        $event = new AfterDtoCreatedEvent($dto);
        $dispatcher = $this->container->get(EventDispatcherInterface::class);

        // Expected
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Validation failed');
        // When
        $dispatcher->dispatch($event, $event::NAME);
        //Then
    }
}