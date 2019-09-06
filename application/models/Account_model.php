<?php

class Account_model extends CI_Model {

        public $account;
        public $name;
        public $password;        
        public $sex;
        public $birthday;        
        public $location;  //所在位置
        public $email;
        public $language;
        public $icon;
        public $platform;
        
        public function __construct()
        {
                parent::__construct();
                $this->load->database();
        }

        public function login($account, $password)
        {
            $sql = "SELECT * FROM account WHERE account = ? AND password = ?";
            $query = $this->db->query($sql, array($account, $password));
            if ($query->num_rows() > 0)
            {
                return true;
            }
            return false;
        }

        public function exist($account)
        {
            $sql = "SELECT * FROM account WHERE account = ?";
            $query = $this->db->query($sql, array($account));
            if ($query->num_rows() > 0)
            {
                return true;
            }
            return false; 
        }

        public function exisGoogleAccount($account, $email)
        {
            $sql = "SELECT * FROM account WHERE account = ? AND email = ? AND source= ?";
            $query = $this->db->query($sql, array($account, $email, 'GOOGLE'));
            if ($query->num_rows() > 0)
            {
                return true;
            }
            return false; 
        }

        public function create()
        {
            $this->account  = $_POST['account']; // please read the below note
            $this->name     = $_POST['name'];
            $this->password = md5($_POST['password']);
            $this->sex     =  empty($_POST['sex'])? null:$_POST['sex'];
            $this->birthday = empty($_POST['birthday'])? null:$_POST['birthday'];
            $this->location = empty($_POST['location'])? null:$_POST['location'];
            $this->email    = empty($_POST['email'])? null:$_POST['email'];
            $this->language = empty($_POST['language'])? null:$_POST['language'];
            $this->platform = empty($_POST['platform'])? null:$_POST['platform'];
            $this->source   = 'SYS';
            $date = date('Y-m-d H:i:s');
            $this->modified_time = $date;
            $this->created_time  = $date;
            return $this->db->insert('account', $this);
        }

        public function createForGoogle()
        {
            $this->account  = $_POST['account']; // please read the below note
            $this->name     = $_POST['name'];
            //$this->password = md5($_POST['password']);
            $this->sex     =  empty($_POST['sex'])? null:$_POST['sex'];
            $this->birthday = empty($_POST['birthday'])? null:$_POST['birthday'];
            $this->location = empty($_POST['location'])? null:$_POST['location'];
            $this->email    = empty($_POST['email'])? null:$_POST['email'];
            $this->language = empty($_POST['language'])? null:$_POST['language'];
            $this->platform = empty($_POST['platform'])? null:$_POST['platform'];
            $this->source   = 'GOOGLE';    
            $date = date('Y-m-d H:i:s');
            $this->modified_time = $date;
            $this->created_time  = $date;
            return $this->db->insert('account', $this);
        }  

        public function update_account_icon($account, $icon)
        {
            return $this->db->update('account', $this, array('account' => $account, 'icon'=>$icon));
        }

        public function renewAccountStartDate($account)
        {
            $startDate = date("Y-m-d H:i:s");
            return $this->db->update('account', array('start_date'=>$startDate), array('account' => $account));
        }

        public function getStartDate($account)
        {
            $sql = "SELECT start_date FROM account WHERE account = ?";
            $query = $this->db->query($sql, array($account));
            $query->result();
            $result = null;
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 
                $result = $row->start_date;
            }
            return $result;
        }

        public function getAccountName($account)
        {
            $sql = "SELECT name FROM account WHERE account = ?";
            $query = $this->db->query($sql, array($account));
            $query->result();
            $result = null;
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 
                $result = $row->name;
            }
            return $result;
        }        

        public function getFoodLogs($account)
        {
            //查詢StartDate             
            $sql = "SELECT * FROM account WHERE account = ?";
            $query = $this->db->query($sql, array($account));
            $query->result();
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 
                $sql = @"select datediff(DATE_FORMAT( daily_food_log.created_time,'%Y-%m-%d'), DATE_FORMAT( account.start_date,'%Y-%m-%d')) food_date_index,
                        DATE_FORMAT( daily_food_log.created_time,'%Y-%m-%d') food_date,
                        sum(daily_food_log.real_food_point) food_point
                        from account 
                        left join daily_food_log on account.account = daily_food_log.account
                        WHERE 
                        daily_food_log.account = ?
                        AND
                        daily_food_log.created_time >= account.start_date 
                        and daily_food_log.created_time <= DATE_ADD( account.start_date, INTERVAL 21 DAY)
                        GROUP BY 
                        datediff(DATE_FORMAT( daily_food_log.created_time,'%Y-%m-%d'), DATE_FORMAT( account.start_date,'%Y-%m-%d')),DATE_FORMAT( daily_food_log.created_time,'%Y-%m-%d')";

                $query = $this->db->query( $sql, array($account));
                return $query->result();
            }
            return null;
        }
       
}       