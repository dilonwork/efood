<?php

class UserFood_model extends CI_Model {

    public $id;
    public $restaurant_id = 1;
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
    public $source;
    public $modified_time;
    public $created_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //查詢餐廳食物清單
    public function getUserFoods($account)
    {
        $sql = "SELECT * FROM user_food WHERE source = ?";
        $query = $this->db->query($sql, array($account));
        return $query->result();
    }  

    //新增用戶自定義食物
    public function addUserFood($account, $name, $nameEn, $calories, $food_point)
    {
        $this->source   = $account;
        $this->name     = $name;
        $this->nameEn     = $nameEn;
        $this->calories   = $calories;
        $this->food_point = $food_point;
        $date = date('Y-m-d H:i:s');
        $this->modified_time = $date;
        $this->created_time  = $date;
        $result = $this->db->insert('user_food', $this);
        if($result == false)
        {
            return -1;
        }
        return $this->db->insert_id();
    }

     public function remove()
     {
        $this->account  = empty($_POST['account'])? null:$_POST['account'];
        $this->food_id  = empty($_POST['food_id'])? null:$_POST['food_id']; 
        
        return $this->db->delete('user_food', 
            array( 'source' =>$this->account, 'id' =>$this->food_id));                                                
    }

}       