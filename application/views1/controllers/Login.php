<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');   // Load model here
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

  
    public function index() {
    if ($this->session->userdata('is_logged_in')) {
        // Already logged in, redirect to dashboard
        redirect('dashboard');
    }

    // Else, show login form
    $this->load->view('login');
}


 public function verify() {
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() == FALSE) {
        $errors = $this->form_validation->error_array();
        $this->session->set_flashdata('errors', $errors);
        redirect('login');
    } else {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

       
        $result = $this->User_model->login($email, $password);

        $errors = [];
        switch ($result) {
            case 'no_users':
                $errors['login'] = 'No users exist in the system!';
                break;
            case 'not_found':
                $errors['email'] = 'Username not found!';
                break;
            case 'empty_password':
                $errors['password'] = 'Password cannot be empty!';
                break;
            case 'mismatch':
                $errors['login'] = 'Email and Password mismatch!';
                break;
        }

        if (!empty($errors)) {
    $this->session->set_flashdata('errors', $errors);
    redirect('login');
} elseif (is_object($result) && isset($result->idUser)) {
    $this->session->set_userdata([
    'user_id' => $result->idUser,
    'email' => $result->email,
     'is_logged_in' => TRUE
]);

    redirect('dashboard');
} else {
    log_message('error', 'Unexpected login result: ' . print_r($result, true));
    redirect('login');
}

    }
}



    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function forgot_password() {
        $this->load->view('forgot_password');
    }
}
