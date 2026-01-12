<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleModel extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('VehicleModel_Model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
	

public function loadmodels($id) {
    $models = $this->VehicleModel_Model->loadmodelstoid($id);
    echo json_encode($models);
}
 public function addnewModel(){
    $modelname = $this->input->post('newModel', TRUE);
    $makeid = $this->input->post('makeid', TRUE);
   
    $data = [
        'model' => $modelname,
        'makeid' => $makeid
    ];

    $insertmodel = $this->VehicleModel_Model->insertModel($data);

    if ($insertmodel) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert model.']);
    }
}


}
