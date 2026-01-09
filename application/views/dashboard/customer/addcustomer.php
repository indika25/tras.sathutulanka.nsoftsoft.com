<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
 <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap JS (Fixes .modal is not a function) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

 <style>
    .dataTables_wrapper {
        overflow-x: auto;
    }
   
     #customersTable th,
    #customersTable td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 4px 8px; /* ↓ Reduced padding here */
        font-size: 14px; /* Optional: also reduces height visually */
    }

/* #partsTable th,
#partsTable td {
    white-space: nowrap;
    vertical-align: middle;
} */

.dt-buttons-container {
    display: flex;
    gap: 4px;
    justify-content: center;
}
</style>
</head>
<body class="bg-light">
 
<div class="container-fluid mt-5">
   

    <!-- DataTable for customers -->
 <div class="card shadow">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0">
        <i class="fas fa-users mr-2"></i> Customer List
    </h4>
    <button id="addcusbtn" type="button" class="btn btn-success" >
        <i class="fas fa-user-plus"></i> Add Customer
    </button>
</div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="customersTable" class="table table-bordered table-hover" >
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Customer Name</th>
                        <th>Address</th>
                        <th>Mobile</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filled by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addCustomerModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> Add Customer
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php echo form_open('Customer/saveCustomer', ['id' => 'customerForm']); ?>

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="title">Title</label>
                        <select class="form-control" name="title" id="title" required>
                            <option value="">- Select -</option>
                            <option>Mr.</option>
                            <option>Mrs.</option>
                            <option>Miss</option>
                            <option>Dr.</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Ex: Kamal Jayasinghe" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Display Name</label>
                        <input type="text" name="display_name" class="form-control" placeholder="Display Name">
                    </div>


                    <div class="col-md-3 mb-3">
                        <label>Customer Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="EX: 0785092933">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>REMARKS</label>
                        <input type="text" name="phone" class="form-control" placeholder="">
                    </div>

                  
 <div class="col-md-6 mb-3">
                        <label>Primary Address</label>
                        <textarea name="primary_address" class="form-control" placeholder="Enter customer address"></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Customer Email</label>
                        <input type="email" name="email" class="form-control" placeholder="abc@gmail.com">
                    </div>

                    
                   

                    <!-- <div class="col-md-3 mb-3">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">- Select -</option>
                            <option>Cash</option>
                            <option>Credit</option>
                            <option>Bank Transfer</option>
                        </select>
                    </div> -->
 <div class="col-md-3 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Is Active?</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                    </div>

                    
                </div>

                <div class="d-flex justify-content-end">
                    <button id="savecustomer" type="submit" class="btn btn-dark">Save Customer</button>
                </div>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>
</div>

<script>
var $j = jQuery.noConflict();

$j(document).ready(function() {

    // Initialize DataTable
  var table = $j('#customersTable').DataTable({
    searchable :true,
    ajax: {
        url: '<?php echo base_url("Customer/loadcustomers"); ?>',
        dataSrc: 'customers'
    },
     
    columns: [
        { data: 'id' },
        { data: 'title' },
        { data: 'customer_name' },
        { data: 'primary_address' },
        { data: 'mobile' },
        { data: 'phone' },

        {
            data: null,
            orderable: false,
            searchable: false,
          render: function(data, type, row) {
    return `
        <div style="white-space: nowrap;">
            <button class="btn btn-sm btn-primary editBtn" data-id="${row.id}" style="margin-right:5px;">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;
}


        }
    ],
    columnDefs: [
        { targets: 0, width: '', visible:false },       // ID
        
        
    ],
   responsive: true,       // Disable responsive to avoid conflicts with scrolling fixed header
   
});

 

    // On form submit: save customer
    $j('#customerForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo base_url("Customer/saveCustomer"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',

            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Customer added successfully!'
                    });

                    $j('#customerForm')[0].reset();
                    table.ajax.reload();  // Reload the datatable to show new data

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to add customer.'
                    });
                }
            },

            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong!'
                });
            }
        });
    });
    
$('#addcusbtn').on('click', function() {
   $j('#customerForm')[0].reset(); // ✅ Correct


      $j('#addCustomerModal').modal('show');
    });


    // Handle Edit button click
    $j('#customersTable tbody').on('click', '.editBtn', function() {
        var id = $j(this).data('id');

        // Fetch the customer details by ID via AJAX (create your getCustomerById endpoint)
        $.ajax({
            url: '<?php echo base_url("Customer/getCustomerById"); ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data) {
                    // Populate form fields with customer data for editing
                    $j('[name="title"]').val(data.title);
                    $j('[name="customer_name"]').val(data.customer_name);
                    $j('[name="display_name"]').val(data.display_name);
                    $j('[name="customer_no"]').val(data.customer_no);
                    $j('[name="primary_address"]').val(data.primary_address);
                    $j('[name="mobile"]').val(data.mobile);
                    $j('[name="phone"]').val(data.phone);
                    $j('[name="work_phone"]').val(data.work_phone);
                    $j('[name="email"]').val(data.email);
                    $j('[name="contact_person"]').val(data.contact_person);
                    $j('[name="contact_phone"]').val(data.contact_phone);
                    $j('[name="is_active"]').prop('checked', data.is_active == 1);
                    $j('[name="payment_method"]').val(data.payment_method);
                    $j('[name="remarks"]').val(data.remarks);

                    // Add hidden input to track id for update
                    if($j('#customerForm input[name="id"]').length) {
                        $j('#customerForm input[name="id"]').val(data.id);
                    } else {
                        $j('#customerForm').append('<input type="hidden" name="id" value="'+data.id+'">');
                    }
  $j('#addCustomerModal').modal('show');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    Swal.fire('Error', 'Failed to fetch customer data', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong while fetching data', 'error');
            }
        });
    });


    // Handle Delete button click
    $j('#customersTable tbody').on('click', '.deleteBtn', function() {
        var id = $j(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete AJAX call
                $.ajax({
                    url: '<?php echo base_url("Customer/deleteCustomer"); ?>/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        if(data.success) {
                            Swal.fire('Deleted!', data.message || 'Customer has been deleted.', 'success');
                            table.ajax.reload();  // Reload the table after deletion
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete customer.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong while deleting.', 'error');
                    }
                });
            }
        });
    });

});
</script>

</body>
</html>
