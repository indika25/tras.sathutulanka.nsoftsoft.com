<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Rent Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <style>
        .filter-section { background-color: #f8f9fa; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .text-right { text-align: right !important; }
        .dataTables_wrapper { overflow-x: auto; }
        #profitDiv { font-size: 18px; font-weight: bold; margin-top: 20px; }
    </style>
</head>

<body class="bg-light">
<div class="container-fluid mt-4">

    <!-- FILTER -->
    <div class="card filter-section">
        <h5 class="mb-3">Vehicle Expenses Report</h5>
        <form id="reportFilterForm" class="form-inline flex-wrap">
            <div class="form-group mr-3 mb-2">
                <label for="vehicle_num" class="mr-2">Vehicle Number:</label>
                <select id="vehicle_num" name="vehicle_num" class="form-control"></select>
            </div>
            <div class="form-group mr-3 mb-2">
                <label for="startDate" class="mr-2">Start Date:</label>
                <input type="date" id="startDate" name="startDate" class="form-control">
            </div>
            <div class="form-group mr-3 mb-2">
                <label for="endDate" class="mr-2">End Date:</label>
                <input type="date" id="endDate" name="endDate" class="form-control">
            </div>
            <button type="button" id="filterBtn" class="btn btn-primary mb-2">Search</button>
            <button type="button" id="printBtn" class="btn btn-warning mb-2 ml-2">Print</button>
        </form>
    </div>

    <!-- RENTED VEHICLES TABLE -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="rentedVehiclesTable" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Hire No</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Milage (KM)</th>
                            <th>Hire Payment</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total Hire Payment :</th>
                            <th class="text-right" id="totalHirePayment">0.00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- EXPENSES TABLE -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="expensesTable" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Expense Name</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-right">Total Expenses:</th>
                            <th class="text-right" id="expensesTotal">0.00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- INSTALLED PARTS TABLE -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="partsTable" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Install Date</th>
                            <th>Vehicle Num</th>
                            <th>Part Name</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total Services:</th>
                            <th id="total_price">0.00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- PROFIT -->
    <h3><div id="profitDiv" class="text-right">Profit: 0.00</div></h3>

</div>

<script>
var $j = jQuery.noConflict();
$j(document).ready(function(){

    // LOAD VEHICLE DROPDOWN
    $j.ajax({
        url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response){
            if(response.data && Array.isArray(response.data)){
                let select = $j('#vehicle_num');
                select.find('option:not(:first)').remove();
                let addedIds = new Set();
                $j.each(response.data,function(i,vehicle){
                    let id = vehicle[0];
                    let vehicleNum = vehicle[4];
                    if(!addedIds.has(id)){
                        select.append(`<option value="${id}">${vehicleNum}</option>`);
                        addedIds.add(id);
                    }
                });
            }
        }
    });

    // RENTED VEHICLES DATATABLE
    var rentedTable = $j('#rentedVehiclesTable').DataTable({
        processing:true,
        serverSide:false,
        ajax:{
            url:'<?php echo base_url("VehicleParts/getRentedVehicles"); ?>',
            type:'POST',
            data:function(d){
                d.vehicleNum = $j('#vehicle_num').val();
                d.startDate = $j('#startDate').val();
                d.endDate = $j('#endDate').val();
            },
            dataSrc:function(json){ return json.data || []; }
        },
        columns:[
            {data:'rent_start_date'},
            {data:'HireNo'},
            {data:'hire_location'},
            {data:'end_location'},
            {data:'total_mileage'},
            {data:'rent_amount'}
        ],
        footerCallback:function(row,data){
            var api=this.api();
            var totalHirePayment = api.column(5).data().reduce((a,b)=>parseFloat(a||0)+parseFloat(b||0),0);
            $j('#totalHirePayment').html(totalHirePayment.toFixed(2));
            updateProfit();
        }
    });

    // EXPENSES DATATABLE (depends on hired vehicles)
    var expensesTable = $j('#expensesTable').DataTable({
        processing:true,
        serverSide:false,
        ajax:{
            url:'<?php echo base_url("VehicleParts/getExpensesByHires"); ?>',
            type:'POST',
            data:function(d){
                d.hireNos = [];
            },
            dataSrc:function(json){ return json.data || []; }
        },
        columns:[
            {data:'ExpenseName'},
            {data:'Amount', className:'text-right', render:function(val){ return parseFloat(val||0).toFixed(2); }}
        ],
        footerCallback:function(row,data){
            var api=this.api();
            var totalExpenses = api.column(1).data().reduce((a,b)=>parseFloat(a||0)+parseFloat(b||0),0);
            $j('#expensesTotal').html(totalExpenses.toFixed(2));
            updateProfit();
        }
    });

    // INSTALLED PARTS DATATABLE
    var partsTable = $j('#partsTable').DataTable({
        processing:true,
        serverSide:false,
        ajax:{
            url:'<?php echo base_url("VehicleParts/getAllParts2"); ?>',
            type:'POST',
            data:function(d){
                d.vehicleNum = $j('#vehicle_num').val();
                d.startDate = $j('#startDate').val();
                d.endDate = $j('#endDate').val();
            },
            dataSrc:function(json){ return json.data || []; }
        },
        columns:[
            {data:null, render:function(data,type,row,meta){ return meta.row+1; }},
            {data:'installdate'},
            {data:'Vehicle_Num'},
            {data:'name'},
            {data:'price'}
        ],
        footerCallback:function(row,data){
            var api=this.api();
            var totalServices = api.column(4).data().reduce((a,b)=>parseFloat(a||0)+parseFloat(b||0),0);
            $j('#total_price').html(totalServices.toFixed(2));
            updateProfit();
        }
    });

    // FILTER BUTTON CLICK
    $j('#filterBtn').on('click', function(){

        rentedTable.ajax.reload(function() {
            var hireNos = rentedTable.rows({search:'applied'}).data().map(function(row){
                return row.HireNo;
            }).toArray();

            expensesTable.settings()[0].ajax.data = function(d){
                d.hireNos = hireNos;
            };
            expensesTable.ajax.reload();
        });

        partsTable.ajax.reload();
    });

    // UPDATE PROFIT
    function updateProfit() {
        var hire = parseFloat($j('#totalHirePayment').text()) || 0;
        var expense = parseFloat($j('#expensesTotal').text()) || 0;
        var services = parseFloat($j('#total_price').text()) || 0;
        var profit = hire - (expense + services);
        $j('#profitDiv').text('Profit: ' + profit.toFixed(2));
    }

    // PRINT BUTTON
    $j('#printBtn').on('click',function(){
        let rentedHtml = $j('#rentedVehiclesTable').clone();
        let expensesHtml = $j('#expensesTable').clone();
        let partsHtml = $j('#partsTable').clone();

        var style = `<style>
            @media print{
                table{width:100%;border-collapse:collapse;}
                th,td{border:1px solid #000;padding:6px;text-align:left;}
                th{background-color:#343a40;color:#fff;}
                .text-right{text-align:right;}
            }
        </style>`;

        var newWin = window.open('','_blank');
        newWin.document.write('<html><head><title>Print Report</title>'+style+'</head><body>');
        newWin.document.write('<h4>Rented Vehicles</h4>'+rentedHtml.prop('outerHTML'));
        newWin.document.write('<h4>Expenses</h4>'+expensesHtml.prop('outerHTML'));
        newWin.document.write('<h4>Installed Parts</h4>'+partsHtml.prop('outerHTML'));
        newWin.document.write('<h4 id="profitDiv">Profit: '+$j('#profitDiv').text().replace('Profit: ','')+'</h4>');
        newWin.document.write('</body></html>');
        newWin.document.close();
        newWin.focus();
        newWin.print();
        newWin.close();
    });

});
</script>
</body>
</html>
