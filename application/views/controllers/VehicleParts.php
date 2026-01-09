<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleParts extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('VehicleParts_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
  public function getAllParts2() {
    // Get filter inputs from POST
    $vehicleNum = $this->input->post('vehicleNum');
    $startDate = $this->input->post('startDate'); // e.g. 2025-10-01
    $endDate   = $this->input->post('endDate');   // e.g. 2025-10-03

    // Prepare filters array
    $filters = [
        'vehicleNum' => $vehicleNum,
        'startDate' => $startDate,
        'endDate'   => $endDate,
    ];

    // Fetch filtered parts
    $parts = $this->VehicleParts_model->getFilteredParts($filters);

    // Return data
    $response = [
        "data" => $parts
    ];
    echo json_encode($response);
}
public function getHireDetails() {
    $hireNo = $this->input->post('hireNo');

    
    $materials = $this->VehicleParts_model->getMaterialsByHireNo($hireNo);
    $expenses = $this->VehicleParts_model->getExpensesByHireNo($hireNo);

    echo json_encode([
        'materials' => $materials,
        'expenses' => $expenses
    ]);
}

public function getExpenses()
{
    $vehicleNum = $this->input->post('vehicleNum');
    $startDate = $this->input->post('startDate');
    $endDate   = $this->input->post('endDate');

    $data = $this->VehicleParts_model->getAggregatedExpenses($vehicleNum, $startDate, $endDate);

    echo json_encode(['data'=>$data]);
}

public function getExpensesByHires() {
    $hireNos = $this->input->post('hireNos');

    if(empty($hireNos)) { echo json_encode(['data'=>[]]); return; }

    $data = $this->VehicleParts_model->getExpensesByHireNos($hireNos);
    echo json_encode(['data'=>$data]);
}


public function getRentedVehicles() {
  
    // Get filter inputs from POST
    $vehicleNum = $this->input->post('vehicleNum');
    $startDate  = $this->input->post('startDate'); // Format: YYYY-MM-DD
    $endDate    = $this->input->post('endDate');   // Format: YYYY-MM-DD

    // Prepare filters array
    $filters = [
        'vehicleNum' => $vehicleNum,
        'startDate'  => $startDate,
        'endDate'    => $endDate,
    ];

    // Fetch filtered rental data
    $rentals = $this->VehicleParts_model->getRentedVehicles($filters);

    // Return JSON response
    $response = [
        "data" => $rentals
    ];

    echo json_encode($response);
}


// Fetch Average Fuel Report
public function averageFuelView()
{
    $this->load->view('average_fuel_report');
}

public function averageFuelReport()
{
    $filters = [
        'vehicleNum' => $this->input->post('vehicleNum'),
        'startDate'  => $this->input->post('startDate'),
        'endDate'    => $this->input->post('endDate'),
        'hireNo'     => $this->input->post('hireNo'),
    ];

    $data = $this->VehicleParts_model->getAverageFuelReport($filters);

    echo json_encode(['data' => $data]);
}


// Fetch HireNos based on vehicle/date
public function getHireNosByVehicleDate() {
    $vehicleNum = $this->input->post('vehicleNum');
    $startDate  = $this->input->post('startDate');
    $endDate    = $this->input->post('endDate');

    $this->db->select('HireNo');
    $this->db->from('vehicle_rentals');
    if($vehicleNum) $this->db->where('vehicle_number', $vehicleNum);
    if($startDate)  $this->db->where('DATE(rent_start_date) >=', $startDate);
    if($endDate)    $this->db->where('DATE(rent_start_date) <=', $endDate);
    $this->db->group_by('HireNo');

    $query = $this->db->get();
    echo json_encode(['data'=>$query->result_array()]);
}


public function getDateWiseDetails() {
  
    // Get filter inputs from POST
    $vehicleNum = $this->input->post('vehicleNum');

   
    $startDate  = $this->input->post('startDate'); 
    $endDate    = $this->input->post('endDate');   

 
    // Prepare filters array
    $filters = [
        'vehicleNum' => $vehicleNum,
        'startDate'  => $startDate,
        'endDate'    => $endDate,
    ];

    // Fetch filtered rental data
    $rentals = $this->VehicleParts_model->getDateWiseDetails($filters);

    // Return JSON response
    $response = [
        "data" => $rentals
    ];
  $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($response));
   
}

public function getAllParts(){
   $parts = $this->VehicleParts_model->loadallbody_parts();

    // Wrap in 'data' for DataTables
    echo json_encode(['data' => $parts]);



}
public function autocomplete_partnames() {
    $term = $this->input->get('term', TRUE);

    if (!$term) {
        echo json_encode([]); // No input = empty array
        return;
    }

    
    $parts = $this->VehicleParts_model->get_partnames_like($term);

    // Extract only names and remove duplicates
    $names = array_map(function($row) {
        return $row->name;
    }, $parts);

    $uniqueNames = array_values(array_unique($names));
    echo json_encode($uniqueNames); // e.g., ["Oil Filter", "Air Filter"]
}

public function save_part() {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('vehicle_num', 'Vehicle Number', 'required');
    $this->form_validation->set_rules('partname', 'Part Name', 'required');
   
    $this->form_validation->set_rules('vehicle_condition', 'Condition', 'required');
    $this->form_validation->set_rules('last_checked', 'Install Date', 'required');
    $this->form_validation->set_rules('price', 'Price', 'required|numeric');
    $this->form_validation->set_rules('Driver', 'Driver', 'required|numeric');
    $this->form_validation->set_rules('mileage', 'mileage', 'required|numeric');
    
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode([
            'status' => 'error',
            'message' => validation_errors()
        ]);
        return; 
    }

    $data = [
        'vehicle_num'   => $this->input->post('vehicle_num'),
        'name'          => $this->input->post('partname'),
        'part_no'       => $this->input->post('partno'),
        'p_condition'   => $this->input->post('vehicle_condition'),
        'price'         => $this->input->post('price'),
        'remarks'       => $this->input->post('remarks'),
        'installdate'   => $this->input->post('last_checked'),
        'Driver'   => $this->input->post('Driver'),
        'mileage'   => $this->input->post('mileage'),
        
        
    ];

    
    $id = $this->input->post('id');

    if ($id) {
        $updated = $this->VehicleParts_model->update_part($id, $data);
       if ($updated) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Part updated successfully.'
            ]);
        } else {
            $db_error = $this->db->error();
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update part. ' . $db_error['message']
            ]);
        }
   } else {
   date_default_timezone_set('Asia/Colombo'); 
    $today = date('Y-m-d');

    
    $this->db->where('vehicle_num', $data['vehicle_num']);
    $this->db->where('LOWER(name)', strtolower($data['name']));
    $this->db->where("DATE(created_at)", $today);  
    // $existing = $this->db->get('body_parts')->row();

    // if ($existing) {
    //     echo json_encode([
    //         'status' => 'error',
    //         'message' => 'This part already exists for the selected vehicle.'
    //     ]);
    //     return;
    // }

   
    $inserted = $this->VehicleParts_model->insert_part($data);

    if ($inserted) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Part added successfully.'
        ]);
    } else {
        $db_error = $this->db->error();
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add part. ' . $db_error['message']
        ]);
    }
}


}

 public function deletePart() {
        $id = $this->input->post('id');
        if ($id && $this->VehicleParts_model->delete_part($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Part deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete part']);
        }
    }



}