<?php

class RestaurantFood_model extends CI_Model {

    public $id;
    public $restaurant_id;
    public $name;        
    public $nameEn;
    public $food_point;            
    
    public $six_grain;
    public $six_meat;
    public $six_vegatables;
    public $six_oil;
    public $six_fruit;
    public $six_milk;
    public $other_sugars;
    public $vegetarianOvoLacto;
    public $vegetarianFiveForbidden;
    public $vegetarian;
    public $key_word;
    public $modified_time;
    public $created_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //查詢餐廳食物清單
    public function getRestaurantFoods($restaurantId)
    {
        $sql = "SELECT * FROM restaurant_food WHERE restaurant_id = ?";
        $query = $this->db->query($sql, array($restaurantId));
        return $query->result();
    }  

}       