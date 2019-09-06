<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';


class Diary extends REST_Controller
{
    //Error Code : 1000 - 1999 帳號相關
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper('string');
        $this->load->helper(array('form', 'url'));
        $this->load->model('DailyFood_model', 'dailyFood');
        $this->load->model('DailyFoodLog_model', 'dailyFoodLog');
        $this->load->model('AccountLogin_model', 'accountLogin');
        $this->load->model('AccountPatient_model', 'AccountPatient');

        $this->load->library('session');
    
        $this->methods['login_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['create_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['upload_post']['limit'] = 250; // 50 requests per hour per user/key
    }

    public function setDailyWeight_post()
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
            if($this->dailyFood->exist( $_POST['account'], $_POST['log_time']))
            {
                $message['result'] = $this->dailyFood->update();
            }
            else
            {
                $message['result'] = $this->dailyFood->add();
            }
            $this->AccountPatient->updateAccountWeight( $_POST['account'], $_POST['weight']);
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function getDailyWeight_post()
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
            $message = 
            [
                'result'=> 1, //token 驗證失敗
                'weight' => intval($this->dailyFood->queryDailyWeight($_POST['account'], $_POST['log_time']))
            ];
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function addFoodLog_post()
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
            $message['result'] = $this->dailyFoodLog->add();            
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    //當前使用 AddFoodLog API
    public function addFoodLogEx_post()
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
            $message['result'] = $this->dailyFoodLog->addFoodLog();            
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }


    public function delFoodLog_post()
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
            $message['result'] = $this->dailyFoodLog->remove();            
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function listFoodLog_post()
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
            $message = 
            [
                'result'=>1,
                'data'=> $this->dailyFoodLog->getAllFoodLog()
            ];
        }  
        $this->set_response($message, REST_Controller::HTTP_OK);      
    }

}