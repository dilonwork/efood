<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';


class Note extends REST_Controller
{
    //Error Code : 1000 - 1999 帳號相關
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper('string');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Note_model', 'note');
        
        $this->load->model('Account_model', 'accountInfo');
        $this->load->model('AccountLogin_model', 'accountLogin');
        $this->load->model('AccountPatient_model', 'AccountPatient');

        $this->load->library('session');
    
        $this->methods['login_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['create_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['upload_post']['limit'] = 250; // 50 requests per hour per user/key
    }

    public function add_post()
    {
        $token = $this->getBearerToken();
        if(!$this->accountLogin->avaliabletoken($this->getBearerToken()))
        {
            $message = 
            [
                'result'=> 1001, //token 驗證失敗
                'data' => ''                
            ];
        }
        else
        {
            $account = $_POST['account'];
            $postMessage = $_POST['message'];
            $post_by = $this->accountInfo->getAccountName($account);

            if($this->note->add($account, $post_by, $postMessage) >= 0)
            {
                $message['result'] = 1;
            }
            else
            {
                $message['result'] = -1;
            }
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function update_post()
    {
        $token = $this->getBearerToken();
        if(!$this->accountLogin->avaliabletoken($this->getBearerToken()))
        {
            $message = 
            [
                'result'=> 1001, //token 驗證失敗
                'data' => ''                
            ];
        }
        else
        {
            if($this->note->exist( $_POST['account'], $_POST['note_id']))
            {
                $message['result'] = $this->note->update();
            }
            else
            {
                $message['result'] = 9001;
            }
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function remove_post()
    {
        $token = $this->getBearerToken();
        if(!$this->accountLogin->avaliabletoken($this->getBearerToken()))
        {
            $message = 
            [
                'result'=> 1001, //token 驗證失敗
                'data' => ''                
            ];
        }
        else
        {
            if($this->note->exist( $_POST['account'], $_POST['note_id']))
            {
                $message['result'] = $this->note->remove();
            }
            else
            {
                $message['result'] = 9001;
            }
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function get_post()
    {
        $token = $this->getBearerToken();
        if(!$this->accountLogin->avaliabletoken($this->getBearerToken()))
        {
            $message = 
            [
                'result'=> 1001, //token 驗證失敗
                'data' => ''                
            ];
        }
        else
        {
            $account = empty($_POST['account'])?null:$_POST['account'];
            $lastUpdateTime = empty($_POST['lastUpdateTime'])?null:$_POST['lastUpdateTime'];

            $message = 
            [
                'result'=> 1, //token 驗證失敗
                'data' => $this->note->getNotes( $account, $lastUpdateTime)  
            ];
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }


}