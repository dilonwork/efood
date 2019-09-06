<?php

class FoodUnit_model extends CI_Model {

    public $id;
    public $name;   
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    
    public function getFoodUnits()
    {
        $query = $this->db->get('food_unit');
        return $query->result();
    }
       
}       