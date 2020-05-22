<?php

namespace App\Data;

use App\Entity\Campus;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


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
     * @var DateTimeType
     */
     public $dateMin;

    /**
     * @var DateTimeType
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