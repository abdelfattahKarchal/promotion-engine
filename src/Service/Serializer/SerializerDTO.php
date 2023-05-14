<?php

namespace App\Service\Serializer;

use App\Event\AfterDtoCreatedEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SerializerDTO implements SerializerInterface{

    private SerializerInterface $serializer;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = new Serializer(
            [new ObjectNormalizer(
                // to read a metadata attribute lik #[Ignore] in LowestPriceEnquiryDTO
                classMetadataFactory: new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())),
                nameConverter: new CamelCaseToSnakeCaseNameConverter()
                )
            ],
            [new JsonEncoder()]
        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data,$format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $dto = $this->serializer->deserialize($data, $type, $format, $context);
        
        $event = new AfterDtoCreatedEvent($dto);
        // dispatch an after dto created
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $dto;
    }

}