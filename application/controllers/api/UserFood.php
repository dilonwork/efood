<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';


class UserFood extends REST_Controller
{
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper('string');
        $this->load->helper(array('form', 'url'));
        $this->load->model('UserFood_model', 'userFood');
        $this->load->model('UserFoodMaterial_model', 'userFoodMaterial');
        $this->load->model('DailyFood_model', 'dailyFood');
        $this->load->model('DailyFoodLog_model', 'dailyFoodLog');

        $this->load->model('AccountLogin_model', 'accountLogin');
        $this->load->library('session');
        
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
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
            $message ="";
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) 
            {
                $user_food_id = $this->userFood->addUserFood($data["account"], $data["name"], $data["nameEn"], $data["calories"], $data["food_point"]);
                if($user_food_id < 0)
                {
                    $message = 
                    [
                        'result'=> 2001, 
                        'data' => ''                
                    ];
                    return $message;                  
                }

                if( $data["foodMaterals"] != null && $data["foodMaterals"] != '')
                {
                    foreach ($data["foodMaterals"] as $key => $value) 
                    {
                        $this->userFoodMaterial->add( $user_food_id,$data["foodMaterals"][$key]["food_material_id"],$data["foodMaterals"][$key]["quantity"]);
                    } 
                }
                
                $message = 
                [
                    'result'=> 1,
                    'food_id'=> $user_food_id
                ];
                
                //新增每日飲食
                $this->dailyFoodLog->addFoodLogEx( $data["account"], 1, $user_food_id, $data["calories"], $data["food_point"]);
            }
            else
            {
                $message = file_get_contents('php://input');
            }
        }
        $this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function delete_post()
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
            //Check FOOD EXIST IN DailyFoodLogs         
            if( $this->dailyFoodLog->existFoodLog( $_POST['food_id']))
            {
                $message = 
                [
                    'result'=> 2002
                ];
            }
            else
            {
                $message = 
                [
                    'result'=> $this->userFood->remove( $_POST['account'], $_POST['food_id'])
                ];
            }
        }
        $this->set_response($message, REST_Controller::HTTP_OK);   
    }
    /*
	public function li$message = 
            [
                'result'=> 1, //token 驗證成功
                'data' => $this->restaurantFood->getRestaurantFoods($this->post('restaurantId'))          
            ];st_post()
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
    */
}