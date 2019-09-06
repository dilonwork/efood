<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends CI_Controller
{
    
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper('string');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');

        $this->load->model('Restaurant_model', 'restaurant');
        //$this->load->model('RestaurantFood_model', 'restaurantFood');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['login_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['create_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['upload_post']['limit'] = 250; // 50 requests per hour per user/key
    }

    function index()
    {
        $this->load->helper('url');
        
        if ($this->session->userdata('logged_in') == FALSE)
        {
            redirect( base_url('index.php'));
        }
        else
        {
            $data['food'] = $this->restaurant->getFoods( null, null);
            $data['restaurant'] = $this->restaurant->getRestaurants();
            $data['error_message'] ="";
            $this->load->view('site/console', $data);
        }
    }

    function login()
    {
        $logged_in = true;
        $data['account'] =  $this->input->post('account');
        $data['password'] = $this->input->post('password');
        
        if($data['account'] == 'admin' && $data['password'] == 'admin1234')
        {   
            $this->session->set_userdata('logged_in', $logged_in);
            $this->session->set_userdata('account_data', $data);
            redirect('site/console');
        }
        else
        {
            redirect( base_url('index.php'));
        }
    }

    function logout()
    {

    }

    function add() 
    {

        if ($this->session->userdata('logged_in') == FALSE)
        {
            redirect( base_url('index.php'));
        }

        $this->restaurant->add( $this->input->post('rt_name')
            ,  $this->input->post('rt_address')
            ,  $this->input->post('rt_phone')
            ,  $this->input->post('rt_comment')
            , 'SYS');

        redirect('site/console');
        //$this->load->view('site/console', $data);
    }

    function syncRestaurantFood()
    {
        if ($this->session->userdata('logged_in') == FALSE)
        {
            echo 'ERROR !!!';
        }

    }
}
