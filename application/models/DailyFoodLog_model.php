<?php

class DailyFoodLog_model extends CI_Model 
{

        public $account;
        public $restaurant_id;
        public $food_id;        
        public $food_point;             
        public $count;
        public $quantity;  //來自手機端份量
        public $created_time;   
        public $real_food_point;
        
        public function __construct()
        {
                parent::__construct();
                $this->load->database();
        }

        public function getAllFoodLog()
        {
                $sql = "SELECT * FROM daily_food_log WHERE account = ? and Date(created_time) = ?";
                $this->account  = empty($_POST['account'])? null:$_POST['account'];
                $this->created_time= empty($_POST['food_time'])? null:$_POST['food_time'];

                $query = $this->db->query($sql, 
                 array( 'account' =>$this->account
                        ,'created_time' =>$this->created_time)); 

                return $query->result();
        }

        public function existFoodLog( $foodId)
        {
                $sql = "SELECT 1 FROM daily_food_log WHERE food_id = ? ";
                
                $query = $this->db->query($sql, array( 'food_id' =>$foodId )); 

                return $query->result();
        }

        public function add()
        {
            $this->account  = empty($_POST['account'])? null:$_POST['account'];
            $this->restaurant_id  = empty($_POST['restaurant_id'])? null:$_POST['restaurant_id'];
            $this->food_id  = empty($_POST['food_id'])? null:$_POST['food_id'];            
            $this->count= empty($_POST['count'])? null:$_POST['count'];
            $this->created_time= empty($_POST['food_time'])? null:$_POST['food_time'];

            $sql = "SELECT * FROM restaurant_food WHERE id = ?";
            $query = $this->db->query($sql, array($this->food_id));
            $query->result();
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 
                $this->food_point      = empty($row->food_point)? 0 : $row->food_point;
                $this->real_food_point = $this->food_point * $this->count;
            }
            return $this->db->insert('daily_food_log', $this);
        }

        public function addFoodLog()
        {
            $this->account  = empty($_POST['account'])? null:$_POST['account'];
            $this->restaurant_id  = empty($_POST['restaurant_id'])? null:$_POST['restaurant_id'];
            $this->food_id  = empty($_POST['food_id'])? null:$_POST['food_id'];            

            $this->quantity = empty($_POST['quantity'])? null:$_POST['quantity'];
            $this->created_time= empty($_POST['food_time'])? null:$_POST['food_time'];

            $sql = "SELECT * FROM restaurant_food WHERE id = ?";
            $query = $this->db->query($sql, array($this->food_id));
            $query->result();
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 
                $this->food_point      = empty($row->food_point)? 0 : $row->food_point;
                //$this->real_food_point = $this->food_point * $this->count;
            }
            $this->real_food_point = empty($_POST['point'])? null:$_POST['point'];
            return $this->db->insert('daily_food_log', $this);
        }

        public function addFoodLogEx( $account, $restaurant_id, $food_id, $quantity, $food_point)
        {
            $this->account  = $account;
            $this->restaurant_id  = $restaurant_id;
            $this->food_id  = $food_id;
            $this->quantity = $quantity;            
            $this->food_point      = $food_point;
            $this->real_food_point = $food_point;
            $this->created_time = date("Y-m-d H:i:s");

            return $this->db->insert('daily_food_log', $this);
        }

        
        public function remove()
        {
            $this->account  = empty($_POST['account'])? null:$_POST['account'];
            $this->restaurant_id  = empty($_POST['restaurant_id'])? null:$_POST['restaurant_id'];
            $this->food_id  = empty($_POST['food_id'])? null:$_POST['food_id']; 
            $this->created_time= empty($_POST['food_time'])? null:$_POST['food_time'];

            return $this->db->delete('daily_food_log', 
                array( 'account' =>$this->account
                        ,'restaurant_id' =>$this->restaurant_id
                        ,'food_id' =>$this->food_id
                        ,'created_time' =>$this->created_time));                                                
        }
}