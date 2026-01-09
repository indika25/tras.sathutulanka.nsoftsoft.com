<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleParts_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

  public function loadallbody_parts() {
    $this->db->select('body_parts.*, vehicledetails.Vehicle_Num,driver.first_name');
    $this->db->from('body_parts');
    $this->db->join('vehicledetails', 'body_parts.vehicle_num = vehicledetails.idvehicledetails', 'left');
 $this->db->join('driver', 'body_parts.Driver = driver.id', 'left');

    $query = $this->db->get();
    return $query->result();
}

public function getFilteredParts($filters = []) {
    $this->db->select('vp.id, vp.installdate, v.Vehicle_Num,v.idvehicledetails AS Vid,d.id AS Did, vp.name, vp.price, vp.p_condition, vp.mileage, vp.remarks, vp.part_no, d.first_name');
    $this->db->from('body_parts vp');
    $this->db->join('vehicledetails v', 'v.idvehicledetails = vp.vehicle_num', 'left');
    $this->db->join('driver d', 'd.id = vp.Driver', 'left');

    // Filter by vehicle number
    if (!empty($filters['vehicleNum'])) {
        $this->db->where('vp.vehicle_num', $filters['vehicleNum']);
    }

    // Date filters on created_at
    if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
        $this->db->where('DATE(vp.created_at) >=', $filters['startDate']);
        $this->db->where('DATE(vp.created_at) <=', $filters['endDate']);
    } elseif (!empty($filters['startDate'])) {
        // Exact match if only one date is given
        $this->db->where('DATE(vp.created_at)', $filters['startDate']);
    } elseif (!empty($filters['endDate'])) {
        // Exact match if only end date given
        $this->db->where('DATE(vp.created_at)', $filters['endDate']);
    }

    $query = $this->db->get();
    return $query->result_array();
}
public function getRentedVehicles($filters = []) {
    $this->db->select('
        vr.HireNo,
        vr.hire_location,
        vr.end_location,
        c.customer_name AS renter_name,
        vr.agreement_no,
        vr.rent_start_date,
        vr.rent_type,
        vr.duration,
        vr.rent_amount,
        d.first_name AS driver_name,
        d.salary_percent,
        vr.remarks,
        vr.created_at,
        vr.oillevel,
        vr.Difference_Milage AS total_mileage,
        SUM(he.Amount) AS total_expenses,
        SUM(CASE WHEN e.expense_name IN ("Bocker","HighWay") THEN he.Amount ELSE 0 END) AS parking_highway_expenses
    ');
    $this->db->from('vehicle_rentals vr');
    $this->db->join('vehicledetails v', 'v.idvehicledetails = vr.vehicle_number', 'left');
    $this->db->join('customers c', 'c.id = vr.renter_name', 'left');
    $this->db->join('driver d', 'd.id = vr.driver_id', 'left');
    $this->db->join('hirenoexpenses he', 'he.HireNo = vr.HireNo', 'left'); 
    $this->db->join('expenses e', 'e.id = he.ExpenseName', 'left');

    if (!empty($filters['vehicleNum'])) {
        $this->db->where('vr.vehicle_number', $filters['vehicleNum']);
    }

    if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
        $this->db->where('DATE(vr.rent_start_date) >=', $filters['startDate']);
        $this->db->where('DATE(vr.rent_start_date) <=', $filters['endDate']);
    } elseif (!empty($filters['startDate'])) {
        $this->db->where('DATE(vr.rent_start_date)', $filters['startDate']);
    } elseif (!empty($filters['endDate'])) {
        $this->db->where('DATE(vr.rent_start_date)', $filters['endDate']);
    }

    $this->db->group_by('vr.HireNo');
    $query = $this->db->get();
    return $query->result_array();
}

// Aggregated expenses
public function getAggregatedExpenses($vehicleNum=null, $startDate=null, $endDate=null)
{
    $this->db->select('e.expense_name AS ExpenseName, SUM(h.Amount) AS Amount');
    $this->db->from('hirenoexpenses h');
    $this->db->join('expenses e', 'e.id = h.ExpenseName', 'left');

    // Optional: join with hire table to filter vehicle/date
    if($vehicleNum || $startDate || $endDate){
        $this->db->join('hire_table ht', 'ht.HireNo = h.HireNo', 'left'); // replace hire_table
        if($vehicleNum) $this->db->where('ht.VehicleNum', $vehicleNum);
        if($startDate) $this->db->where('ht.HireDate >=', $startDate);
        if($endDate) $this->db->where('ht.HireDate <=', $endDate);
    }

    $this->db->group_by('e.expense_name');
    $query = $this->db->get();
    return $query->result_array();
}

public function getExpensesByHireNos($hireNos){
    $this->db->select('e.expense_name AS ExpenseName, SUM(h.Amount) AS Amount');
    $this->db->from('hirenoexpenses h');
    $this->db->join('expenses e', 'e.id = h.ExpenseName', 'left');
    $this->db->where_in('h.HireNo', $hireNos);
    $this->db->group_by('e.expense_name');
    $query = $this->db->get();
    return $query->result_array();
}

// Average fuel report from vehicle_rentals table
public function getAverageFuelFromRentals($filters = []) {
    $this->db->select('vehicle_number, HireNo, Difference_Milage AS km_gone, oillevel AS fuel_liters');
    $this->db->from('vehicle_rentals');

    if(!empty($filters['hireNo'])) $this->db->where('HireNo', $filters['hireNo']);
    if(!empty($filters['vehicleNum'])) $this->db->where('vehicle_number', $filters['vehicleNum']);
    if(!empty($filters['startDate'])) $this->db->where('DATE(rent_start_date) >=', $filters['startDate']);
    if(!empty($filters['endDate'])) $this->db->where('DATE(rent_start_date) <=', $filters['endDate']);

    $query = $this->db->get();
    $results = $query->result_array();

    foreach($results as &$row){
        $liters = floatval($row['fuel_liters']);
        $km     = floatval($row['km_gone']);
        $row['average'] = $liters>0 ? round($km/$liters,2) : 0;
    }

    return $results;
}



    // public function getAverageFuelReport($filters)
    // {
    //     $this->db->select("
    //         vr.vehicle_number,
    //         vr.HireNo,
    //         vr.oillevel                         AS liter_price,
    //         vd.status                           AS liter_per_km,
    //         vr.Difference_Milage               AS gone_km,

    //         IFNULL(SUM(
    //             CASE 
    //                 WHEN he.ExpenseName = 6 THEN he.Amount 
    //                 ELSE 0 
    //             END
    //         ),0) AS pumped_amount
    //     ");

    //     $this->db->from('vehicle_rentals vr');

    //     $this->db->join(
    //         'vehicledetails vd',
    //         'vd.idvehicledetails = vr.vehicle_number',
    //         'LEFT'
    //     );

    //     $this->db->join(
    //         'hirenoexpenses he',
    //         'he.HireNo = vr.HireNo',
    //         'LEFT'
    //     );

    //     // 🔹 Filters
    //     if (!empty($filters['vehicleNum'])) {
    //         $this->db->where('vr.vehicle_number', $filters['vehicleNum']);
    //     }

    //     if (!empty($filters['hireNo'])) {
    //         $this->db->where('vr.HireNo', $filters['hireNo']);
    //     }

    //     if (!empty($filters['startDate'])) {
    //         $this->db->where('vr.RentalDate >=', $filters['startDate']);
    //     }

    //     if (!empty($filters['endDate'])) {
    //         $this->db->where('vr.RentalDate <=', $filters['endDate']);
    //     }

    //     $this->db->group_by('vr.HireNo');

    //     $query = $this->db->get()->result_array();

    //     // 🔹 Calculations
    //     foreach ($query as &$r) {

    //         $literPrice  = (float)$r['liter_price'];
    //         $literPerKm  = (float)$r['liter_per_km'];
    //         $pumpedAmt   = (float)$r['pumped_amount'];
    //         $goneKm      = (float)$r['gone_km'];

    //         $pumpedLiters = $literPrice > 0 ? ($pumpedAmt / $literPrice) : 0;
    //         $plannedKm    = $pumpedLiters * $literPerKm;
    //         $profitKm     = $goneKm - $plannedKm;

    //         $r['pumped_liters'] = round($pumpedLiters, 2);
    //         $r['planned_km']    = round($plannedKm, 2);
    //         $r['profit_km']     = round($profitKm, 2);
    //     }

    //     return $query;
    // }

     // Get all active vehicles
    public function get_vehicles() {
        return $this->db->select('*')
                        ->from('vehicledetails')
                        ->get()
                        ->result();
    }

    // Get Hire Nos filtered by vehicle and optional date
    public function get_hireNos($vehicleNum = '', $startDate = '', $endDate = '') {
        $this->db->select('vr.HireNo');
        $this->db->from('vehicle_rentals vr');

        if($vehicleNum != '') $this->db->where('vr.vehicle_number', $vehicleNum);
        if($startDate != '')  $this->db->where('vr.rent_start_date >=', $startDate);
        if($endDate != '')    $this->db->where('vr.rent_start_date <=', $endDate);

        $this->db->group_by('vr.HireNo');

        return $this->db->get()->result();
    }

public function getAverageFuelData($vehicleNum, $startDate, $endDate, $hireNo)
{
    $this->db->select("
        vr.vehicle_number,
        vr.HireNo,

        vr.oillevel AS liter_price,
        vd.status AS liter_per_km,
 he.Amount AS pumped_amount,
        IFNULL(he.Amount,0) / NULLIF(vr.oillevel,0) AS pumped_liters,
        (IFNULL(he.Amount,0) / NULLIF(vr.oillevel,0)) * vd.status AS planned_km,

        vr.Difference_Milage AS gone_km,
        vr.Difference_Milage - 
        ((IFNULL(he.Amount,0) / NULLIF(vr.oillevel,0)) * vd.status) AS profit_km
    ");

    $this->db->from('vehicle_rentals vr');

    $this->db->join(
        'vehicledetails vd',
        'vd.idvehicledetails = vr.vehicle_number',
        'LEFT'
    );

    $this->db->join(
        'hirenoexpenses he',
        "he.HireNo = vr.HireNo AND he.ExpenseName = 6",
        'LEFT'
    );

    /* ---------------- FILTERS ---------------- */

    // ✅ Vehicle filter
    if (!empty($vehicleNum)) {
        $this->db->where('vr.vehicle_number', $vehicleNum);
    }

    // ✅ Date filter
    if (!empty($startDate)) {
        $this->db->where('vr.rent_start_date >=', $startDate);
    }

    if (!empty($endDate)) {
        $this->db->where('vr.rent_start_date <=', $endDate);
    }

    // ✅ Hire No filter
    if (!empty($hireNo)) {
        $this->db->where('vr.HireNo', $hireNo);
    }

    $query = $this->db->get();
    return $query->result_array();
}


public function getDateWiseDetails($filters = [])
{
   
    $this->db->select('
        DATE_FORMAT(vr.rent_start_date, "%Y-%m") AS month,
        COUNT(DISTINCT vr.HireNo) AS total_hires,
        SUM(vr.rent_amount) AS total_hire_amount
    ');
    $this->db->from('vehicle_rentals vr');

    if (!empty($filters['vehicleNum'])) {
        $this->db->where('vr.vehicle_number', $filters['vehicleNum']);
    }
    if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
        $this->db->where('DATE(vr.rent_start_date) >=', $filters['startDate']);
        $this->db->where('DATE(vr.rent_start_date) <=', $filters['endDate']);
    }

    $this->db->group_by('month');
    $rentals = $this->db->get()->result_array();

    if (empty($rentals)) {
        return [];
    }

    // 2️⃣ Get all expense names
    $expenseList = $this->db
        ->select('expense_name')
        ->from('expenses')
        ->get()
        ->result_array();

    $expenseNames = array_column($expenseList, 'expense_name');

    // 3️⃣ Get expenses per month and type
    $this->db->select('
        DATE_FORMAT(vr.rent_start_date, "%Y-%m") AS month,
        e.expense_name,
        SUM(IFNULL(he.Amount,0)) AS expense_amount
    ');
    $this->db->from('vehicle_rentals vr');
    $this->db->join('hirenoexpenses he', 'he.HireNo = vr.HireNo', 'left');
    $this->db->join('expenses e', 'e.id = he.ExpenseName', 'left');

    if (!empty($filters['vehicleNum'])) {
        $this->db->where('vr.vehicle_number', $filters['vehicleNum']);
    }
    if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
        $this->db->where('DATE(vr.rent_start_date) >=', $filters['startDate']);
        $this->db->where('DATE(vr.rent_start_date) <=', $filters['endDate']);
    }

    $this->db->group_by(['month', 'e.expense_name']);
    $expenses = $this->db->get()->result_array();

    $expenseMap = [];
    foreach ($expenses as $exp) {
        if (!empty($exp['expense_name'])) {
            $expenseMap[$exp['month']][$exp['expense_name']] = (float)$exp['expense_amount'];
        }
    }

    // 4️⃣ Calculate driver salary using subquery to avoid nested SUM()
    $expenseSubQuery = '
        SELECT 
            he.HireNo,
            SUM(CASE 
                WHEN e.expense_name IN ("Highway","Bocker") 
                THEN he.Amount 
                ELSE 0 
            END) AS hw_broker_total
        FROM hirenoexpenses he
        LEFT JOIN expenses e ON e.id = he.ExpenseName
        GROUP BY he.HireNo
    ';

    $this->db->select('
        DATE_FORMAT(vr.rent_start_date, "%Y-%m") AS month,
        SUM(
            (vr.rent_amount - IFNULL(exp.hw_broker_total, 0)) 
            * (d.salary_percent / 100)
        ) AS driver_salary
    ', false);

    $this->db->from('vehicle_rentals vr');
    $this->db->join("($expenseSubQuery) exp", 'exp.HireNo = vr.HireNo', 'left');
    $this->db->join('driver d', 'd.id = vr.driver_id', 'left');

    if (!empty($filters['vehicleNum'])) {
        $this->db->where('vr.vehicle_number', $filters['vehicleNum']);
    }
    if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
        $this->db->where('DATE(vr.rent_start_date) >=', $filters['startDate']);
        $this->db->where('DATE(vr.rent_start_date) <=', $filters['endDate']);
    }

    $this->db->group_by('month');
    $driverSalaries = $this->db->get()->result_array();

    // Map driver salary per month
    $salaryMap = [];
    foreach ($driverSalaries as $ds) {
        $salaryMap[$ds['month']] = (float)$ds['driver_salary'];
    }

    // 5️⃣ Combine rentals, expenses, driver salary, total expense, profit
    foreach ($rentals as &$r) {

        $month = $r['month'];
        $r['expenses'] = [];

        // All expenses (even if no record → 0)
        foreach ($expenseNames as $expName) {
            $r['expenses'][] = [
                'name'   => $expName,
                'amount' => isset($expenseMap[$month][$expName])
                            ? $expenseMap[$month][$expName]
                            : 0
            ];
        }

        // Driver salary as expense
        $r['expenses'][] = [
            'name'   => 'Driver Salary',
            'amount' => isset($salaryMap[$month])
                        ? $salaryMap[$month]
                        : 0
        ];

        // Total expense
        $r['total_expense'] = array_sum(array_column($r['expenses'], 'amount'));

        // Profit
        $r['profit'] = $r['total_hire_amount'] - $r['total_expense'];
    }

    return $rentals;
}










public function getMaterialsByHireNo($hireNo) {
        $this->db->select('*');
        $this->db->from('hirenomaterials');
        $this->db->where('HireNo', $hireNo);

        $query = $this->db->get();
        return $query->result_array();
    }

    // Get expenses for a specific HireNo
   public function getExpensesByHireNo($hireNo) {
    $this->db->select('e.expense_name AS ExpenseName, he.Amount');
    $this->db->from('hirenoexpenses he');
    $this->db->join('expenses e', 'e.id = he.ExpenseName', 'left');
    $this->db->where('he.HireNo', $hireNo);

    $query = $this->db->get();
    return $query->result_array();
}


public function get_partnames_like($term) {
    $this->db->select('name');
    $this->db->from('body_parts');
    
    // Case-insensitive like match
    $this->db->like('LOWER(name)', strtolower($term));
    
    // Optional: prioritize names that start with the term
    $this->db->order_by("CASE 
        WHEN LOWER(name) LIKE " . $this->db->escape(strtolower($term) . '%') . " THEN 1
        WHEN LOWER(name) LIKE " . $this->db->escape('%' . strtolower($term) . '%') . " THEN 2
        ELSE 3
    END", 'ASC');

    $this->db->limit(10);
    $query = $this->db->get();
    return $query->result();
}


    public function insert_part($data){
        return $this->db->insert('body_parts',$data);
    }
    
     public function delete_part($id) {
        $this->db->where('id', $id);
        return $this->db->delete('body_parts');
    }
     public function update_part($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('body_parts', $data);
    }
}