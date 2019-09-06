<?php

class FoodComponent_model extends CI_Model {

    public $id;
    public $name;      
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    
    public function getFoodComponents()
    {
        $query = $this->db->get('food_component');
        return $query->result();
    }
       
}       