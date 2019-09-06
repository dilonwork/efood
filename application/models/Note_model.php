<?php

class Note_model extends CI_Model {

    public $id;
    
    public $post_by;    //發文者名稱
    public $message;
    public $account;    //發文者帳號
    public $owner;      //訊息擁有帳號
    public $modified_time;
    public $created_time;    
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getNotes( $account, $lastUpdateTime)
    {
        $sql = @"SELECT * FROM  note WHERE  1 = 1";

        if($account != null && $lastUpdateTime != null)
        {
            $sql = $sql." AND (account =  ? ) AND modified_time > ?";
            $query = $this->db->query($sql, array( $account, $lastUpdateTime));
        }else
        {
            $sql = $sql." AND (account =  ? ) ";
            $query = $this->db->query($sql, array( $account));
        }

        $sql = $sql." ORDER BY id ASC ";

        return $query->result();
    }

    public function add($account, $post_by, $message)
    {
        $this->account  = $account; 
        $this->owner    = $account; 
        $this->post_by  = $post_by; 
        $this->message  = $message;

        $date = date('Y-m-d H:i:s');
        $this->modified_time = $date;
        $this->created_time  = $date;
        $result = $this->db->insert('note', $this);

        if($result == false)
        {
            return -1;
        }
        return $this->db->insert_id();
    }

    public function exist($account, $not_id)
    {

        $sql = "SELECT * FROM note WHERE account = ? and owner = ? and id = ?";
        $query = $this->db->query($sql, array( $account, $account, $not_id));

        if ($query->num_rows() > 0)
        {
            return true;
        }

        return false; 
    }

    public function update()
    {
        $this->account  = empty($_POST['account'])? null:$_POST['account'];
        $this->id       = empty($_POST['note_id'])? null:$_POST['note_id']; 
        $this->message  = empty($_POST['message'])? null:$_POST['message'];

        $sql = "SELECT * FROM note WHERE account = ? and owner = ? and id = ?";
        $query = $this->db->query($sql, array( $this->account, $this->account, $this->id));
        $query->result();

        $result = -1;
        if ($query->num_rows() > 0)
        {
            $row = $query->row(); 
        
            $row->account   =  $this->account;
            $row->owner     =  $this->account;
            $row->id        =  $this->id; 
            $row->message   =  $this->message;
            
            $date = date('Y-m-d H:i:s');
            $row->modified_time = $date;
            $result = $this->db->update('note', $row, 
            array('owner' =>  $this->account, 'account' =>  $this->account  ,'id'=> $this->id));
        }
        return $result;
    }

    public function remove()
    {
        $this->account  = empty($_POST['account'])? null:$_POST['account'];
        $this->id  = empty($_POST['note_id'])? null:$_POST['note_id']; 
        
        return $this->db->delete('note', array('owner' =>  $this->account
            , 'account' =>  $this->account  ,'id'=> $this->id));
    }

}       