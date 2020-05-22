<?php

namespace App\Data;

use App\Entity\Campus;


class SearchData
{
    /**
     *
     * @var string
     */
     public $q = '';

    /**
     * @var Campus[]
     */
     public $campus = [];

    /**
     * @var \DateTime
     */
     public $dateMin;

    /**
     * @var \DateTime
     */
     public $dateMax;


}