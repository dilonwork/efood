<?php

class Restaurant_model extends CI_Model {

    public $id;
    public $name;
    public $address;        
    public $phone;
    public $longitude;        
    public $latitude;
    public $note;
    public $from;
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //查詢餐廳清單
    public function getRestaurants()
    {
        $query = $this->db->get('restaurant');
        return $query->result();
    }

    public function add($name, $address, $phone, $note, $from)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->note = $note;
        $this->from = $from;

        $result = $this->db->insert('restaurant', $this);

        if($result == false)
        {
            return -1;
        }
        return $this->db->insert_id();
    }

    public function getFoods( $account, $lastUpdateTime)
    {
        $sql = @"SELECT 
                restaurant_id, 
                restaurant_name,
                food_id,
                food_name,
                calories,
                food_point,
                six_grain,
                six_meat,
                six_vegatables,
                six_oil,
                six_fruit,
                six_milk,
                vegetarianOvoLacto,
                vegetarianFiveForbidden,
                vegetarian,
                other_sugars,
                key_word,
                source,
                modified_time
                FROM  v_restaurant_food 
                WHERE  1 = 1";

        if($account != null && $lastUpdateTime != null)
        {
            $sql = $sql." AND (source = 'SYS' OR source = ? ) AND modified_time > ?";
            $query = $this->db->query($sql, array( $account, $lastUpdateTime));
        }
        else if($account != null && $lastUpdateTime == null)
        {
            $sql = $sql." AND (source = 'SYS' OR source = ? ) ";
            $query = $this->db->query($sql, array($account));
        }else
        {
            $sql = $sql." AND (source = 'SYS') ";
            $sql = $sql." ORDER BY restaurant_id, food_id";
            $query = $this->db->query($sql);
        }
        return $query->result();
    }

    public function getRestaurantFoods($lastUpdateTime)
    {
        $sql = @"SELECT 
                restaurant.id as restaurant_id, 
                restaurant.name as restaurant_name,
                restaurant_food.id as food_id,
                restaurant_food.name as food_name,
                restaurant_food.calories,
                restaurant_food.food_point,
                restaurant_food.six_grain,
                restaurant_food.six_meat,
                restaurant_food.six_vegatables,
                restaurant_food.six_oil,
                restaurant_food.six_fruit,
                restaurant_food.six_milk,
                restaurant_food.vegetarianOvoLacto,
                restaurant_food.vegetarianFiveForbidden,
                restaurant_food.vegetarian,
                restaurant_food.other_sugars,
                restaurant_food.key_word,
                restaurant_food.source,
                restaurant_food.modified_time
                FROM restaurant left join restaurant_food on restaurant.id = restaurant_food.restaurant_id 
                WHERE  restaurant_food.id is not null 
                and restaurant_food.calories is not null 
                and restaurant_food.food_point is not null";
        
        if($lastUpdateTime != null)
        {
            $sql = $sql." AND restaurant_food.modified_time > ?";
            $query = $this->db->query($sql, array($lastUpdateTime));
        }
        else
        {
            $query = $this->db->query($sql);
        }
        return $query->result();
    }

}       