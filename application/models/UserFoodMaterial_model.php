<?php

class UserFoodMaterial_model extends CI_Model {

    public $user_food_id;
    public $food_meterial_id;
    public $quantity;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //新增用戶自定義食物
    public function add($user_food_id, $food_meterial_id, $quantity)
    {
        $this->user_food_id     = $user_food_id;
        $this->food_meterial_id = $food_meterial_id;
        $this->quantity         = $quantity;
        return $this->db->insert('user_food_material', $this);
    }


}       