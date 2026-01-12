<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FuelType extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('Fueltype_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
	
public function loadfueltypes() {
    $data['fueltypes'] = $this->Fueltype_model->loadalltypes();
    echo json_encode($data);
}
}
