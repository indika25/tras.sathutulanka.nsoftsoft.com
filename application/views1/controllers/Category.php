<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('Category_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
	 public function index()
    {
        $data['title'] = "Dashboard";
        $data['content'] = $this->load->view('dashboard/index', [], TRUE);
        $this->load->view('layouts/main', $data);
    }
public function loadcategory() {
    $data['categories'] = $this->Category_model->loadallcategory();
    echo json_encode($data);
}
}
