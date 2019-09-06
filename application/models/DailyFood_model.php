<?php

class DailyFood_model extends CI_Model {

        public $account;
        public $food_date;
        public $food_type;        
        public $weight;
        
        public function __construct()
        {
                parent::__construct();
                $this->load->database();
        }

        public function exist($account, $food_date)
        {
            $sql = "SELECT * FROM daily_food WHERE account = ? and food_date = ?";
            $query = $this->db->query($sql, array($account, $food_date));
            if ($query->num_rows() > 0)
            {
                return true;
            }
            return false; 
        }

        public function queryDailyWeight($account, $food_date)
        {
            $sql = "SELECT weight FROM daily_food WHERE account = ? and food_date = ? limit 1";
            $query = $this->db->query($sql, array($account, $food_date));
            $result = $query->result();
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 

                return $row->weight;
            }
            $sql = "SELECT weight FROM daily_food WHERE account = ? and food_date < ? ORDER BY food_date desc  limit 1";
            $query = $this->db->query($sql, array($account, $food_date));
            $row = $query->row(); 
            if(empty($row->weight))
                return 0;
            return $row->weight;
        }

        public function add()
        {
            $this->account  = empty($_POST['account'])? null:$_POST['account'];
            $this->food_date= empty($_POST['log_time'])? null:$_POST['log_time'];
            $this->weight   = empty($_POST['weight'])? null:$_POST['weight'];

            return $this->db->insert('daily_food', $this);
        }

        public function update()
        {
            $this->account  = empty($_POST['account'])? null:$_POST['account'];
            $this->food_date= empty($_POST['log_time'])? null:$_POST['log_time'];
            $this->weight   = empty($_POST['weight'])? null:$_POST['weight'];

            return $this->db->update('daily_food'
                , $this
                , array('account' =>  $this->account  ,'food_date'=> $this->food_date));
        }

}