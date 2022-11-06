<?php

namespace App\Filter\Modifier\Factory;

use App\Filter\Modifier\PriceModifierInterface;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class PriceModifierFactory implements PriceModifierFactoryInterfce
{
    public function create(string $modiferType): PriceModifierInterface
    {
        $modifierClassBaseName = str_replace('_', '',ucwords($modiferType, "_"));
        
        $modifier = self::PRICE_MODIFIER_NAMESPACE .$modifierClassBaseName;
        //dd($modifier);

        if (!class_exists($modifier)) {
           
            throw new ClassNotFoundException($modifier);
        }

        return new $modifier();
    }
}