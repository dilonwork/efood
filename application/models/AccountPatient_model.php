<?php

class AccountPatient_model extends CI_Model {

    public $id;
    public $account;   
    public $height;   
    public $weight; 
    public $target;
    public $activity;  
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAccountInfo($account)
    {
        $sql = @"SELECT t1.*, t2.sex, t2.birthday, t2.start_date FROM account_patient t1 
                 Left Join account t2 on t1.account = t2.account WHERE t1.account = ?";
        $query = $this->db->query($sql, array($account));
        return $query->result();
    }
    
    public function updateAccountInfo( $account, $height, $weight)
    {
        if($this->existPatient($account))
        {
            $result =$this->updatePatient($account, $height, $weight);
        }else
        {
            $result =$this->addPatient($account, $height, $weight);
        }
        return $result;
    }

    public function updateAccountWeight( $account, $weight)
    {
        if($this->existPatient($account))
        {
            $result =$this->updatePatientWeight($account, $weight);
        }
        return $result;
    }

    public function updateAccountInfo2( $account, $sex, $birthday)
    {
        return $this->updatePatient2($account, $sex, $birthday);
    }


    public function updateAccountTarget( $account, $target, $activity)
    {
        if($this->existPatient($account))
        {
            $result =$this->updatePatientTarget($account, $target, $activity);
        }else
        {
            $result =$this->addPatientTarget($account, $target, $activity);
        }
        return $result;
    }

    //account_patient    
    private function addPatient($account, $height, $weight)
    {
        $this->account = $account;
        $this->height  = $height;
        $this->weight  = $weight;
        return $this->db->insert('account_patient', $this);
    }

    private function updatePatient($account, $height, $weight)
    {
        return $this->db->update('account_patient', array('height'=>$height, 'weight'=>$weight)
            , array('account' => $account));
    }

    private function updatePatientWeight($account, $weight)
    {
        return $this->db->update('account_patient', array('weight'=>$weight)
            , array('account' => $account));
    }

    private function updatePatient2($account, $sex, $birthday)
    {
        return $this->db->update('account', array('sex'=>$sex, 'birthday'=>$birthday)
            , array('account' => $account));  
    }

    private function addPatientTarget($account, $target, $activity)
    {
        $this->account = $account;
        $this->target  = $target;
        $this->activity  = $activity;
        return $this->db->insert('account_patient', $this);
    }

    private function updatePatientTarget($account, $target, $activity)
    {
        return $this->db->update('account_patient', array('target'=>$target, 'activity'=>$activity)
            , array('account' => $account));
    }

    private function existPatient($account)
    {
        $sql = "SELECT * FROM account_patient WHERE account = ?";
        $query = $this->db->query($sql, array($account));
        if ($query->num_rows() > 0)
        {
            return true;
        }
        return false;
    }

}       