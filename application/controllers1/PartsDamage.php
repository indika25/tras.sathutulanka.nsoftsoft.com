<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PartsDamage extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('PartsDamage_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    public function delete_part(){
     
       $id = $this->input->post('id');
$updated = $this->PartsDamage_model->delete_damagepart($id);
 if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Deleted successfully.'
                ]);
            } else {
                $db_error = $this->db->error();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to Delete. ' . $db_error['message']
                ]);
            }

    }

    public function getAllDamageParts(){
    $data['data']  = $this->PartsDamage_model->get_all_damageparts();
 echo json_encode($data);

    }
    public function getAllDamageParts2(){
    // Get POST data
    $vehicleNum = $this->input->post('vehicleNum');
    $startDate = $this->input->post('startDate');
    $endDate = $this->input->post('endDate');

    // Pass filters to model
    $data['data']  = $this->PartsDamage_model->get_all_damageparts2($vehicleNum, $startDate, $endDate);

    echo json_encode($data);
}



public function save_damagepart() {
      

        $data = [
            'vehicle_num'   => $this->input->post('vehicle_num'),
            'Driver'        => $this->input->post('Driver'),
            'name'          => $this->input->post('partname'),
            'part_no'       => $this->input->post('partno'),
            'damagedate'   => $this->input->post('last_checked'),
            'remarks'       => $this->input->post('remarks')
        ];

        $id = $this->input->post('id');

        if ($id) {
            $updated = $this->PartsDamage_model->update_damagepart($id, $data);

            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Damage part updated successfully.'
                ]);
            } else {
                $db_error = $this->db->error();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update damage part. ' . $db_error['message']
                ]);
            }

        } else {
            $inserted = $this->PartsDamage_model->insert_damagepart($data);

            if ($inserted) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Damage part added successfully.'
                ]);
            } else {
                $db_error = $this->db->error();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add damage part. ' . $db_error['message']
                ]);
            }
        }
    }

}
