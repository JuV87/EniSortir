<?php

namespace App\Search;

use App\Entity\Location;
use App\Entity\User;

class SearchTrip
{
    /**
     * @var string
     */
    public $name='';

    /**
     * @var User[]
     */
    public $user=[];
    public $register=[];


    /**
     * @var Location[]
     */
    public $location=[];



}