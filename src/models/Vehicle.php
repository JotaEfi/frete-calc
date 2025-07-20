<?php

namespace App\Models;

use App\Config\Database;

class Vehicle
{
    private $id;
    private $name;
    private $fuel_consumption;
    private $fixed_cost_per_hour;
    private $depreciation_maintenance;

    public function __construct($data = []){
        if(!empty($data)){
            $this->fill($data);
        } 
    }
    
}