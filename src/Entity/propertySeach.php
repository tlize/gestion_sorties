<?php
namespace App\Entity;

class PropertySearch{

    private $campusSearch;

    /**
     * @return mixed
     */
    public function getCampusSearch()
    {
        return $this->campusSearch;
    }

    /**
     * @param mixed $campusSearch
     * @return PropertySearch
     */
    public function setCampusSearch($campusSearch)
    {
        $this->campusSearch = $campusSearch;
        return $this;
    }



}