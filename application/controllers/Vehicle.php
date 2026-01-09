<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehicle_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
public function get_vehicle_details() {
    $id = $this->input->post('id');

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'No ID provided']);
        return;
    }

   
    $drivers = $this->Vehicle_model->get_vehicle_by_id($id);

    if (!empty($drivers)) {
        echo json_encode(['success' => true, 'data' => $drivers]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No drivers found']);
    }
}


    public function get_vehicles_ajax()
{
    $vehicles = $this->Vehicle_model->get_all_vehicles();
    $data = array();

    foreach ($vehicles as $vehicle) {
        $row = array(
            $vehicle->idvehicledetails, // 0: idvehicledetails
                $vehicle->Vehicle_Make,     // 1: Vehicle_Name
                $vehicle->Vehicle_Model,  
                $vehicle->Chassis_No,                  
                $vehicle->Vehicle_Num,      // 3: Vehicle_Num
                $vehicle->typename,        // 5: Fuel_Type
                $vehicle->Seats,            // 6: Seats
                $vehicle->status,           // 8: status
                $vehicle->Category
        );
        $data[] = $row;
    }

    $response = array(
        "draw" => intval($this->input->post('draw')),
        "recordsTotal" => count($vehicles),
        "recordsFiltered" => count($vehicles),
        "data" => $data
    );

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($response));
}


    public function loadmakes(){
         $data['makes'] = $this->Vehicle_model->loadallmakes();
    echo json_encode($data);

    }

   public function get_vehicle_by_id($id)
{
    $vehicle = $this->Vehicle_model->get_vehiclebyid($id);

    if ($vehicle) {
        echo json_encode([
            'status' => 'success',
            'data' => $vehicle
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Vehicle not found.'
        ]);
    }
}

public function delete_vehiclebyid($id)
{
    

    if ($this->Vehicle_model->delete_vehiclebyid($id)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Vehicle not found or could not be deleted.'
        ]);
    }
}
public function saveRate()
{
    // Load model if not autoloaded
    $this->load->model('Vehicle_model');

    $id = $this->input->post('id'); // optional hidden input
    $vehicleNumber = $this->input->post('vehicleNumber');
    $category = $this->input->post('category');
    $rentType = $this->input->post('rentType');
    $rate = $this->input->post('rate');

    // Validate
    if (!$vehicleNumber || !$category || !$rentType || !$rate) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'All fields are required.'
            ]));
        return;
    }

    

    // Prepare data
    $data = [
        'vehicleid' => $vehicleNumber,
        'category' => $category,
        'ratename' => $rentType,
        'rate' => $rate,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    if (!$id) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $inserted = $this->Vehicle_model->insert_rate($data);

        if ($inserted) {
            echo json_encode(['status' => 'success', 'action' => 'insert']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert rate.']);
        }
    } else {
        $updated = $this->Vehicle_model->update_rate($id, $data);

        if ($updated) {
            echo json_encode(['status' => 'success', 'action' => 'update']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update rate.']);
        }
    }
}


    public function SaveVehicle() {

    $id = $this->input->post('idvehicledetails'); // hidden input for update
    $vehicle_num = $this->input->post('Vehicle_Num');

    // Check duplicate vehicle number
    if ($this->Vehicle_model->is_vehicle_num_exists($vehicle_num, $id)) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'status' => 'error',
                 'message' => 'Vehicle number already exists.'
             ]));
        return;
    }

    $data = array(
        'Vehicle_Make'        => $this->input->post('Vehicle_Make'),
        'Vehicle_Model'       => $this->input->post('Vehicle_model'),
        'Vehicle_Num'         => $vehicle_num,
        'Chassis_No'          => $this->input->post('Chassis_No'),
        'Fuel_Type'           => $this->input->post('Fuel_Type'),
        'Seats'               => $this->input->post('Seats'),
        'Category_idCategory' => $this->input->post('Category_idCategory'),
        'status'              => $this->input->post('rentrate'),
        'updated_at'          => date('Y-m-d H:i:s')
    );

    if (!$id) {
        // INSERT
        $data['created_at'] = date('Y-m-d H:i:s');
        $inserted = $this->Vehicle_model->insert_vehicle($data);

        if ($inserted) {
            echo json_encode(['status' => 'success', 'action' => 'insert']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert vehicle.']);
        }

    } else {
        // UPDATE
        $updated = $this->Vehicle_model->update_vehicle($id, $data);

        if ($updated) {
            echo json_encode(['status' => 'success', 'action' => 'update']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update vehicle.']);
        }
    }
}


public function delete_rate() {
        // Get POST data and sanitize
        $vehicle_number = $this->input->post('vehicleid', TRUE); // TRUE enables XSS filtering
      
        // Check if required data is present
        if (!$vehicle_number) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
        }

        // Call model to delete the rate
        $deleted = $this->Vehicle_model->delete_rate($vehicle_number);

        if ($deleted) {
            $response = ['status' => 'success', 'message' => 'Rate deleted successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to delete rate'];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

public function get_rates()
{
    // If model not autoloaded, load it manually
    $this->load->model('Vehicle_model');

    $rateData = $this->Vehicle_model->get_all_rates();

    $data = [];
  
    foreach ($rateData as $row) {
        $data[] = [
           $row->id,
            $row->Vehicle_Num,
            $row->category_name,
            $row->rate,
            $row->ratename,
            
        ];
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
    exit; // prevent any extra output
}





    

}