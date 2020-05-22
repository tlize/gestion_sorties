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

    /**
     * @var boolean
     */
    public $organisateur;

    /**
     * @var boolean
     */
    public $inscrit;

    /**
     * @var boolean
     */
    public $pasInscrit;

    /**
     * @var boolean
     */
    public $passees;

}