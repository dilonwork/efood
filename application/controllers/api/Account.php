<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';


class Account extends REST_Controller
{
    //Error Code : 1000 - 1999 帳號相關
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper('string');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Account_model', 'account_info');
        $this->load->model('AccountLogin_model', 'accountLogin');
        $this->load->model('AccountPatient_model', 'accountPatient');
        $this->load->library('session');
        
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['login_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['create_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['upload_post']['limit'] = 250; // 50 requests per hour per user/key
    }

    public function login_post()
    {

        if($this->account_info->login($this->post('account'), md5($this->post('password'))))
        {
            $token = random_string('alnum', 16);
            $time = time(); 
            $loginTime = date("Y-m-d H:i:s", $time);
            // $availableTime = date("Y-m-d H:i:s", $time + 30 * 60);
            $this->accountLogin->update_account_token($this->post('account'), $token, $loginTime, null);
            $message = 
            [
                'result'=>1,
                'token'=> $token
                // 'availableTime'=>$availableTime   
            ];
            //$message = $this->getBearerToken();
        }
        else
        {
            $message = 
            [
                'result'=>0,
                'token'=>''
                // 'availableTime'=>''
            ];  
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    //1011 帳號不存在
    public function googleLogin_post()
    {
        $account = $this->post('account');
        if(empty($account))
            $account = $this->post('googleId');

        //確認Google 帳號是否存在
        if(!$this->existGoogleAccount( $account, $this->post('email')))
        {
            $message = 
            [
                'result'=> 1011,
                'token' => ''
            ];
        }
        else
        {

            $token = random_string('alnum', 16);
            $time = time(); 
            $loginTime = date("Y-m-d H:i:s", $time);
            //$availableTime = date("Y-m-d H:i:s", $time + 30 * 60);
            $result = $this->accountLogin->update_account_token( $account, $token, $loginTime, null);
            if(!$result)
            {
                $message = 
                [
                    'result'=>0,
                    'message'=> $result
                    //'availableTime'=>$availableTime   
                ];  
            }
            else
            {
                $message = 
                [
                    'result'=>1,
                    'token'=> $token
                    //'availableTime'=>$availableTime   
                ];            
            }
        }
         $this->set_response($message, REST_Controller::HTTP_OK);
    }

    //1001 Token 驗證失敗
    public function getToken_post()
    {
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

            $token = random_string('alnum', 16);
            $time = time(); 
            $loginTime = date("Y-m-d H:i:s", $time);
            //$availableTime = date("Y-m-d H:i:s", $time + 30 * 60);
            $this->accountLogin->update_account_token($this->post('account'), $token, $loginTime, null);
            $message = 
            [
                'result'=>1,
                'token'=> $token
                //'availableTime'=>$availableTime   
            ];
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

	public function create_post()
	{		
        if($this->exist($this->post('account')))
        {
            $message['result'] = 1010;
            $this->set_response($message, REST_Controller::HTTP_OK);
            return;
        }
        
		$result = $this->account_info->create();	
        if(!$result)
        {
            $message['result'] = 1012;
            $this->set_response($message, REST_Controller::HTTP_OK);
            return;    
        }	
        $result = $this->accountLogin->create($this->post('account'));
        if(!$result)
        {
            $message['result'] = 1013;
            $this->set_response($message, REST_Controller::HTTP_OK);
            return;    
        }
        
		return $this->login_post();
	}

    public function updateStartDate_post() 
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
            $message['result'] = $this->account_info->renewAccountStartDate($this->post('account'));   
        }
        $this->set_response($message, REST_Controller::HTTP_OK);                
    }

    public function createGoogle_post()
    {
        if($this->existGoogleAccount($this->post('account'), $this->post('email')))
        {
            $message['result'] = 1010;
            $this->set_response($message, REST_Controller::HTTP_OK);
            return;
        }

        $result = $this->account_info->createForGoogle();
        if(!$result)
        {
            $message['result'] = 1012;
            $this->set_response($message, REST_Controller::HTTP_OK);
            return;    
        }

        $result = $this->accountLogin->create($this->post('account'));
        if(!$result)
        {
            $message['result'] = 1013;
            $this->set_response($message, REST_Controller::HTTP_OK);
            return;    
        }
        return $this->googleLogin_post();
    }

    public function exist_post()
    {
        $message['result'] = $this->exist($this->post('account'));
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function upload_post()
    {
        
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['overwrite']            = true;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('icon'))
        {
            $error = array('error' => $this->upload->display_errors());
            $message = [
                'result'=>false,
                'error_message'=>$error
            ];
        }
        else
        {
            $this->load->model('Account_model', 'account_info');
            $account = $this->post('account');
            $icon    = $this->upload->data('file_name');
            if($this->account_info->update_account_icon($account, $icon))
            {
                $message = [
                    'result'=>true,
                    'error_message'=>''
                ];                
            }else
            {
                 $error = array('error' => 'update database[icon] failed.');
                 $message = [
                    'result'=>false,
                    'error_message'=>$error
                 ];
            }
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    private function exist($account)
    {
        return $this->account_info->exist($account);
    }

    private function existGoogleAccount($account, $email)
    {
        return $this->account_info->exisGoogleAccount( $account, $email);
    }

    //AccountPatient
    public function getAccountInfo_post()
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
                'result'=> 1, 
                'data' => $this->accountPatient->getAccountInfo($this->post('account'))   
            ];
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function updateAccountInfo_post()
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
            $message['result'] = $this->accountPatient->updateAccountInfo( 
                $this->post('account'), 
                $this->post('height'), 
                $this->post('weight')
            );   
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function updateAccountInfo2_post()
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
            $message['result'] = $this->accountPatient->updateAccountInfo2( 
                $this->post('account'),
                $this->post('sex'),
                $this->post('birthday')
            );   
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function updateAccountTarget_post()
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
            $message['result'] = $this->accountPatient->updateAccountTarget( 
                $this->post('account'), $this->post('target'), $this->post('activity'));   
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function getFoodLogs_post()
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
                'result'=> 1, 
                'start_date' =>$this->account_info->getStartDate($this->post('account')),
                'data' => $this->account_info->getFoodLogs($this->post('account'))   
            ];
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    

}
