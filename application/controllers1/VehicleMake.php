<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleMake extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('Make_model');

        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
	
public function loadmakes() {
    $data['makes'] = $this->Make_model->loadallmakes();
    echo json_encode($data);
}

public function addMake() {
        $make = $this->input->post('make', TRUE);


        if (empty($make)) {
            echo json_encode(['status' => 'error', 'message' => 'Make is required.']);
            return;
        }

        $make_id = $this->Make_model->insertMake($make);

        if ($make_id) {
            echo json_encode(['status' => 'success', 'make_id' => $make_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert make.']);
        }
    }
  

}
