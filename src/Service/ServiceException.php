<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceException extends HttpException{

   // private ServiceExceptionData $exceptionData;
    
    public function __construct(private ServiceExceptionData $exceptionData){
        $statusCode = $exceptionData->getStatusCode();
        $message = $exceptionData->getType();

        parent::__construct($statusCode, $message);
    }   

    /**
     * Get the value of exceptionData
     *
     * @return ServiceExceptionData
     */
    public function getExceptionData(): ServiceExceptionData
    {
        return $this->exceptionData;
    }
}