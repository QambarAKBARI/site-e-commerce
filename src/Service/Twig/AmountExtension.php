<?php 

namespace App\Service\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AmountExtension extends AbstractExtension {

    
    public function getFilters(){

        return[
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount($value){

    $finaleValue = $value * 100 / 100;

    $finaleValue = number_format($finaleValue, 2, ',', '' );

    return $finaleValue . '€';
    }
    
}