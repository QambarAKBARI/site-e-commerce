<?php


namespace App\Service\Search;

use App\Entity\Categorie;
use App\Entity\Marque;

class SearchService {

    public int $page = 1;

    public string $q;
    public Marque $marques;
    public int $max;
    public int $min;



}