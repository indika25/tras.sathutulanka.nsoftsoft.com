<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function __construct() {
        parent::__construct();
        $this->load->model('User_model');  
		$this->load->model('Vehicle_model');
        $this->load->model('Driver_model');        
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');

         $this->output
         ->set_header('Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0')
         ->set_header('Pragma: no-cache');

    // Redirect to login if not logged in
    if (!$this->session->userdata('is_logged_in')) {
        redirect('login');
    }
    }
	 public function index()
    {
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/dashboard', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }
	public function vehicledetails() {
         // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        $data['title'] = "Vehicle Details";
        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/vehicle/vehicledetails', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }
    public function driverdetails() {
         $data['vehicles'] = $this->Driver_model->get_all_drivers();
        $data['title'] = "Driver Details";
        $data['content'] = $this->load->view('dashboard/driver/driverdetails', $data, TRUE);
 $this->load->view('layouts/main', $data);
    }

    public function vehicleparts() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        $data['title'] = "Vehicle Details";
        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/vehicle/vehicleparts', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }
     public function addcustomer() {
        // Load the Vehicle model
       

        // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        $data['title'] = "Vehicle Details";
        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/customer/addcustomer', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }


    public function partsDamage() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        $data['title'] = "Vehicle Details";
        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/vehicle/part_damage', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

    public function RentVehicle() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        $data['title'] = "Vehicle Details";
        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/vehicle/vehiclerent', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

    public function partsreport() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/reports/partsreport', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

public function damagepartsreport() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/reports/damagepart', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

    
public function vehiclerentreport() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/reports/vehiclerentreport', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

public function vehicle_expense_report() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/reports/vehicle_expense_report', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }
    
    public function averageFuelView() {
    $this->load->view('dashboard/reports/average_fuel_report'); // your view file
}

public function monthlysumery() {
            // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/reports/monthlysumery', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

    
public function dash() {

     if (!$this->session->userdata('is_logged_in')) {
        // Redirect to login page if not logged in
        redirect('Login'); // Change 'auth/login' to your login route
    }else{
      // Get all vehicles from DB
        $data['vehicles'] = $this->Vehicle_model->get_all_vehicles();

        // Load vehicle_manage.php into the main layout
        $data['content'] = $this->load->view('dashboard/dashboard', $data, TRUE);

        $this->load->view('layouts/main', $data);

    }

      
    }


}
