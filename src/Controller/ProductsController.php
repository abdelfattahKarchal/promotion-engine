<?php

namespace App\Controller;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiryDTO;
use App\Entity\Promotion;
use App\Filter\PromotionFilter;
use App\Filter\PromotionFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\SerializerDTO;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsController extends AbstractController
{

    public function __construct(
        private ProductRepository $repository,
        private EntityManagerInterface $entityManager
        )
    {
        
    }
    #[ Route('products/{id}/lowest-price', name:'lowest-price', methods:'POST')]
    public function lowestPrice( Request $request,
    int $id,
    SerializerDTO $serializer,
    PromotionFilterInterface $promotionFilter,
    PromotionCache $promotionCache
    ): Response{
                
        if ($request->headers->has('force-fail')) {
            return new JsonResponse(
                ["error" => "Promotion engin failure message"],
                $request->headers->get('force-fail')
            );
        }

        // 1- deserialize json data into enquiryDTO
        /** @var LowestPriceEnquiryDTO */
        $lowsetPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiryDTO::class, 'json');
        // 2- Pass the enquiry to promotion filter + the appropriate promotion will be applied

        $product = $this->repository->findOrFail($id);

        $lowsetPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowsetPriceEnquiry->getRequestDate());

        

        $modifiedEnquiry = $promotionFilter->apply($lowsetPriceEnquiry, ...$promotions);
        // 3- return the modified Enquiry
        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
        //return new Response($responseContent, 200, ['Content-Type' => 'application/json']);

        //return new JsonResponse($lowsetPriceEnquiry,200);
    }

   #[Route('products/{id}/promotions', name:'promotions', methods:'GET')]
    public function promotions(){

    }
}
