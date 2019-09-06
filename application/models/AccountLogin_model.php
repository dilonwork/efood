<?php

class AccountLogin_model extends CI_Model {

        public $account;
        public $token;
        public $available_time;        
        public $last_login_time;
        public $available;          // 0: 無效 1: 有效
        
        public function __construct()
        {
                parent::__construct();

                $this->load->database();
        }

        public function create($account)
        {
            $this->account          = $account;
            $date = date('Y-m-d H:i:s');
            $this->last_login_time  = $date;

            return $this->db->insert('account_login', $this);
        }

        public function exist( $account)
        {
            $sql = "SELECT * FROM account_login WHERE account = ?";
            $query = $this->db->query($sql, array($account));
            if ($query->num_rows() > 0)
            {
                return true;
            }
            return false;             
        }

        public function avaliabletoken($token)
        {
            
            $currentTime   = new DateTime("now");
            $sql = "SELECT * FROM account_login WHERE token = ?";
            $query = $this->db->query($sql, array($token));
            $query->result();
            if ($query->num_rows() > 0)
            {
                $row = $query->row(); 
                if($row->available == 0)
                    return false;
                //$availableTime = new DateTime($row->available_time);
                //if($availableTime > $currentTime)
                return true;
            }
            return false;                            
        }

        public function update_account_token( $account, $token, $loginTime, $availableTime)
        {
            $this->account= $account;
            $this->token = $token;
            $this->available_time =  $availableTime;
            $this->last_login_time  = $loginTime;
            $this->available = 1;
            return $this->db->update('account_login', $this, array('account' => $account));
        }

}       