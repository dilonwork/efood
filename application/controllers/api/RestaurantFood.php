<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';


class RestaurantFood extends REST_Controller
{
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper('string');
        $this->load->helper(array('form', 'url'));
        $this->load->model('RestaurantFood_model', 'restaurantFood');
        $this->load->model('AccountLogin_model', 'accountLogin');
        $this->load->library('session');
        
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['login_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['create_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['upload_post']['limit'] = 250; // 50 requests per hour per user/key
    }

	public function list_post()
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
                'result'=> 1, //token 驗證成功
                'data' => $this->restaurantFood->getRestaurantFoods($this->post('restaurantId'))          
            ];
		}
 		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}