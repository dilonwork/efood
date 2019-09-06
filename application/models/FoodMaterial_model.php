<?php

class FoodMaterial_model extends CI_Model {

    public $id;
    public $name;
    public $calorie;        
    public $unit;   
    public $modified_time;
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //查詢食材清單_For卡路里計算機
    public function getFoodMaterials($lastUpdateTime)
    {
         $sql = @"SELECT 
                food_material.id, 
                food_material.name,
                food_material.calorie,
                food_unit.name as unit,
                food_material.modified_time
                FROM food_material 
                left join food_unit on food_material.unit = food_unit.id
                left join food_component on food_material.component = food_component.id    
                WHERE  1 = 1 ";

        if($lastUpdateTime != null)
        {
            $sql = $sql." AND food_material.modified_time > ?";
            $query = $this->db->query($sql, array($lastUpdateTime));
        }else
        {
            $query = $this->db->query($sql);
        }
        return $query->result();
    }

}       