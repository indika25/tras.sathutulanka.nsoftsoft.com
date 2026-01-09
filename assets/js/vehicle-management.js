var $j = jQuery.noConflict();
$j(document).ready(function () {

 if (typeof $j.fn.DataTable === 'undefined') {
        console.error('DataTables is not loaded.');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'DataTables library failed to load. Please check your internet connection or CDN.'
        });
        return;
    }
 $('#modalCloseBtn').on('click', function () {
      $('#vehicleModal').modal('hide');
    });


$j('#rateTable').DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    autoWidth: false,
    ajax: {
        url: BASE_URL + 'Vehicle/get_rates',
        type: 'POST',
        dataSrc: function (json) {
            console.log('details',json);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            console.error('DataTables AJAX error:', xhr, error, thrown);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load vehicle rates. Please try again.'
            });
        }
    },
   columns: [
    { data: 0 }, // #
    { data: 1 }, // Vehicle Number
    { data: 2 }, // Category
    { data: 3 }, // Rate
    { data: 4 }, // Rate
    
    {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
    return `
        <button class="btn btn-sm btn-primary editRateBtn">Edit</button>
        <button class="btn btn-sm btn-danger deleteRateBtn">Delete</button>
    `;
}

    }
]

});


  $j('#vehicleTable').DataTable({
    processing: true,
    serverSide: false,
    responsive: true, // Optional; remove if it interferes
    autoWidth: false, // Enable automatic column width
    ajax: {
        url: BASE_URL + 'Vehicle/get_vehicles_ajax',
        type: 'POST',
        dataSrc: function (json) {
            console.log('AJAX response:', json);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            console.error('DataTables AJAX error:', xhr, error, thrown);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load vehicle data. Please try again.'
            });
        }
    },
    columns: [
        { data: 0, visible: false },
        { data: 1 },
        { data: 2 },
        { data: 3, className: 'wrap-text' },
        { data: 4 },
        { data: 5 },
        { data: 6 },
        { data: 7 },
        { data: 8 },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                    <div class="dt-buttons-container">
                        <button class="btn btn-sm btn-primary editBtn" data-id="${row[0]}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${row[0]}">Delete</button>
                    </div>
                `;
            }
        }
    ],
    initComplete: function () {
        // Recalculate column widths after table is initialized
        this.api().columns().every(function () {
            this.autoSize && this.autoSize(); // Not always necessary, but good for edge cases
        });
    }
});


    if (typeof Swal !== 'undefined' && VEHICLE_SUCCESS_MESSAGE) {
        Swal.fire({
    icon: 'success',
    title: 'Success',
    text: VEHICLE_SUCCESS_MESSAGE,
    width: 300  // Default is 500px
});

    }
$j('#rateTable tbody').on('click', '.editRateBtn', function () {
    const table = $j('#rateTable').DataTable();
    const row = $j(this).closest('tr');
    const rowData = table.row(row).data();

    if (!rowData) {
        console.error('No row data found');
        return;
    }

    console.log('Editing rowData:', rowData);
 const id = rowData[0]; // hidden ID column

    const vehicleText = rowData[1]?.trim();   // e.g. "GX-5914"
    const categoryText = rowData[2]?.trim();  // e.g. "Car"
    const rate = rowData[3]?.trim();          // e.g. "500"
    const rentTypeText = rowData[4]?.trim();  // e.g. "Hourly"

    // ðŸ”¹ Vehicle dropdown - match by label/text
    const vehicleOption = $j('#vehicleNumberRate option').filter(function () {
        return $j(this).text().trim() === vehicleText;
    }).first();

    if (vehicleOption.length > 0) {
        $j('#vehicleNumberRate').val(vehicleOption.val()).trigger('change');
    } else {
        console.warn('Vehicle not found in dropdown:', vehicleText);
        $j('#vehicleNumberRate').val('').trigger('change');
    }

    // ðŸ”¹ Category dropdown - match by label/text
    const categoryOption = $j('#vehicleCategoryRate option').filter(function () {
        return $j(this).text().trim() === categoryText;
    }).first();

    if (categoryOption.length > 0) {
        $j('#vehicleCategoryRate').val(categoryOption.val()).trigger('change');
    } else {
        console.warn('Category not found in dropdown:', categoryText);
        $j('#vehicleCategoryRate').val('').trigger('change');
    }

    // ðŸ”¹ Rent Type dropdown - match by value directly
    // Rent type values ARE the labels ("Hourly", "Daily", etc.)
    const rentTypeSelect = $j('select[name="rent_type"]');
    const rentTypeOption = rentTypeSelect.find('option').filter(function () {
        return $j(this).val().trim() === rentTypeText;
    }).first();

    if (rentTypeOption.length > 0) {
        rentTypeSelect.val(rentTypeOption.val()).trigger('change');
    } else {
        console.warn('Rent type not found in dropdown:', rentTypeText);
        rentTypeSelect.val('').trigger('change');
    }
  $j('#rateId').val(id);

    // ðŸ”¹ Set the rate input
    $j('#vehicleRateInput').val(rate);

    // ðŸ”¹ Update modal title
    $j('#addRateModalLabel').text('Edit Vehicle Rate');

    // ðŸ”¹ Store row index (for update)
    const rowIndex = table.row(row).index();
    $j('#addRateToTable').data('edit-index', rowIndex);

    // ðŸ”¹ Show modal
    $j('#addRateModal').modal('show');

});

$j('#addnewBtn').on('click', function () {
    $j('#vehicleForm')[0].reset(); // clear all inputs
    $j('#vehicleForm').find('input[name="idvehicledetails"]').remove(); // remove hidden ID if any
    $j('#vehicle_model').html('<option value="">-- Select Model --</option>'); // reset model
    $j('#vehicleSubmitBtn').text('Save Vehicle'); // reset button label
});

$j('#rateTable tbody').on('click', '.deleteRateBtn', function () {
    const table = $j('#rateTable').DataTable();
    const row = $j(this).closest('tr');
    const rowData = table.row(row).data();

    if (!rowData) {
        Swal.fire('Error', 'Failed to get row data.', 'error');
        return;
    }

    // Assuming your id is in the first column or somewhere in the rowData array/object
    // Adjust this based on your actual data structure:
    const rateId = rowData[0]; // or rowData.id if it's an object

    Swal.fire({
        title: 'Delete Rate?',
        text: `Are you sure you want to delete the rate for vehicle: ${rowData[1]}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $j.ajax({
                url: BASE_URL + 'Vehicle/delete_rate',
                type: 'POST',
                data: { vehicleid: rateId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.message, 'success');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to delete rate.', 'error');
                }
            });
        }
    });
});


    // Load Makes
    $.ajax({
        url: BASE_URL + 'VehicleMake/loadmakes',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.makes && Array.isArray(response.makes)) {
                let select = $('select[name="Vehicle_Make"]');
                select.find('option:not(:first)').remove();
                $.each(response.makes, function (index, make) {
                    select.append(`<option value="${make.make_id}">${make.make}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading makes:', error);
        }
    });

    // Load Categories
    $.ajax({
        url: BASE_URL + 'Category/loadcategory',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.categories && Array.isArray(response.categories)) {
                let select = $('select[name="Category_idCategory"]');
                let select2 = $('select[name="vehicleCategoryRate"]');
                
select.find('option:not(:first)').remove();
                $.each(response.categories, function (index, cat) {
                    select.append(`<option value="${cat.idCategory}">${cat.Category}</option>`);
                });

                 select2.find('option:not(:first)').remove();
                $.each(response.categories, function (index, cat) {
                    select2.append(`<option value="${cat.idCategory}">${cat.Category}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading categories:', error);
        }
    });

    // Load Fuel Types
    $.ajax({
        url: BASE_URL + 'FuelType/loadfueltypes',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.fueltypes && Array.isArray(response.fueltypes)) {
                let select = $('select[name="Fuel_Type"]');
                select.find('option:not(:first)').remove();
                $.each(response.fueltypes, function (index, ftype) {
                    select.append(`<option value="${ftype.id}">${ftype.typename}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading fuel types:', error);
        }
    });

    // Edit Button
    $j('#vehicleTable').on('click', '.editBtn', function () {
        let id = $j(this).data('id');
        $.ajax({
            url: BASE_URL + 'Vehicle/get_vehicle_by_id/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const data = response.data;
                   
                   
                    

                    $j('[name="Vehicle_Make"]').val(data.Vehicle_Make);
                    //load models to make
                  
                    $('#vehicle_model').html('<option value="">-- Select Model --</option>');

                    loadModelToMake(data);

                   
                    $j('[name="Chassis_No"]').val(data.Chassis_No);
                    $j('[name="Vehicle_Num"]').val(data.Vehicle_Num);
                    $j('[name="Fuel_Type"]').val(data.Fuel_Type);
                    $j('[name="Seats"]').val(data.Seats);
                    $j('[name="rentrate"]').val(data.status);
                    $j('[name="Category_idCategory"]').val(data.Category_idCategory);
                    $j('[name="duration_unit"]').val(data.ratetype);
                    // $j('[name="rentrate"]').val(data.rentrate);
                    let hiddenIdInput = $j('#vehicleForm').find('input[name="idvehicledetails"]');
                    if (hiddenIdInput.length) {
                        hiddenIdInput.val(data.idvehicledetails);
                    } else {
                        $j('#vehicleForm').append(`<input type="hidden" name="idvehicledetails" value="${data.idvehicledetails}">`);
                    }
                    $j('#vehicleModal').modal('show');


                    // $j('#vehicleSubmitBtn').text('Update Vehicle');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: response.message || 'Vehicle not found',
                        width: 300
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch vehicle data.',
                    width: 300
                });
            }
        });
    });

    function loadModelToMake(data){
        
        
         $.ajax({
                url: BASE_URL + 'VehicleModel/loadmodels/' + data.Vehicle_Make,
                type: 'GET',
                dataType: 'json',
                success: function (models) {
                    $.each(models, function (index, model) {
                        $('#vehicle_model').append(
                            $('<option>', {
                                value: model.model_id,
                                text: model.model
                            })
                        );
                    });
                
        
                     $j('[name="Vehicle_model"]').val(data.Vehicle_Model);
                },
                error: function () {
                    alert('Failed to load models');
                }
            });
    }
 $('#addRateToTable').on('click', function () {
    var id = $('#rateId').val();  // <-- get hidden id input
    var vehicleNumber = $('#vehicleNumberRate').val();
    var category = $('#vehicleCategoryRate').val();
    var rentType = $('select[name="rent_type"]').val();
    var rate = $('#vehicleRateInput').val();

    if (!vehicleNumber || !category || !rentType || !rate) {
        showToast('warning', 'Please fill in all fields.');
        return;
    }

    $.ajax({
        url: BASE_URL + 'Vehicle/saveRate',
        method: 'POST',
        data: {
            id: id,  // <-- include id here for update
            vehicleNumber: vehicleNumber,
            category: category,
            rentType: rentType,
            rate: rate
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                showToast('success', 'Rate ' + response.action + ' successful');
                $('#rateForm')[0].reset();
                $('#rateId').val('');  // clear hidden input after success
                 $j('#rateTable').DataTable().ajax.reload();
            } else {
                showToast('error', response.message);
            }
        },
        error: function () {
            showToast('error', 'Server error occurred.');
        }
    });
});



    // Toggle Make Input Section
    $('#btn_make').on('click', function () {
        $('#inputSection').slideToggle();
    });
$j('#addratebtn').on('click', function () {
  $j('#addRateModal').modal('show');
});
// Toggle Make Input Section
  $j('#addvehiclebtn').on('click', function () {
    $j('#vehicleForm')[0].reset();

    // Remove hidden field if exists (means we're not editing)
    $j('#vehicleForm').find('input[name="idvehicledetails"]').remove();

    // Reset model dropdown
    $j('#vehicle_model').html('<option value="">-- Select Model --</option>');

    // Reset submit button label (optional)
    $j('#vehicleSubmitBtn').text('Save Vehicle');

    // Show the modal
    $j('#vehicleModal').modal('show');
});


$('#btn_addMake').on('click', function () {
    let newMake = $('#newMake').val().trim();

    if (newMake === '') {
        Swal.fire({
            icon: 'error',
            title: 'Empty Value',
            text: 'Please enter a vehicle make.',
            width: 300
        });
        return;
    }

    $.ajax({
        url: BASE_URL + 'VehicleMake/addMake',
        method: 'POST',
        data: { make: newMake },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Make inserted successfully.',
                    width: 300
                });

                // Reload make list
                $('select[name="Vehicle_Make"]').append(
                    `<option value="${response.make_id}">${newMake}</option>`
                );

                $('#newMake').val(''); // clear input
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Insert Failed',
                    text: response.message || 'Could not insert make.',
                    width: 300
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'AJAX request failed.',
                width: 300
            });
        }
    });
});

$.ajax({
     url: BASE_URL + 'Vehicle/get_vehicles_ajax',
  
    type: 'POST',
    success: function(response) {
    console.log('Vehicle Data:', response);
    let select = $('select[name="vehicleNumberRate"]');
    select.empty();
    select.append('<option value="">-- No Vehicle Assigned --</option>');

    let addedIds = new Set(); // To track unique vehicle IDs

    response.data.forEach(function(v) {
        let id = v[0];         // idvehicledetails
        let vehicleNum = v[4]; // vehicle number

        if (!addedIds.has(id)) {
            select.append('<option value="' + id + '">' + vehicleNum + '</option>');
            addedIds.add(id);
        }
    });
}
,
    error: function(xhr, status, error) {
        console.error('Failed to fetch vehicles:', error);
        Swal.fire('Error', 'Could not load vehicle list', 'error');
    }
});


  $('#btn_model').on('click', function () {
        $('#inputSection2').slideToggle();
    });
$('#btn_addModel').on('click', function () {
    let newModel = $('#newModel').val().trim();
    let make = $('#vehicleMake').val();
   

    if (newModel === ''|| make==='') {
        Swal.fire({
            icon: 'error',
            title: 'Empty Value',
            text: 'Please enter a vehicle make.',
            width: 300
        });
        return;
    }

    $.ajax({
        url: BASE_URL + 'VehicleModel/addnewModel',
        method: 'POST',
        data: { newModel: newModel, makeid: make },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Make inserted successfully.',
                    width: 300
                });

                // Reload make list
                $('select[name="Vehicle_Make"]').append(
                    `<option value="${response.make_id}">${newModel}</option>`
                );

                $('#newModel').val(''); // clear input
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Insert Failed',
                    text: response.message || 'Could not insert make.',
                    width: 300
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'AJAX request failed.',
                width: 300
            });
        }
    });
});


    // Delete Button
    $j(document).on('click', '.deleteBtn', function () {
        const id = $j(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This vehicle will be permanently deleted!',
            icon: 'warning',
            width: 300,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE_URL + 'Vehicle/delete_vehiclebyid/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Vehicle has been deleted.',
                                width: 300
                            });
                            $j('#vehicleTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete vehicle.',
                                width: 300
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Could not delete vehicle.',
                            width: 300
                        });
                    }
                });
            }
        });
    });

    
    $('#vehicleMake').on('change', function () {
        var makeId = $(this).val();
        $('#vehicle_model').html('<option value="">-- Select Model --</option>');

        if (makeId !== '') {
            $.ajax({
                url: BASE_URL + 'VehicleModel/loadmodels/' + makeId,
                type: 'GET',
                dataType: 'json',
                success: function (models) {
                    $.each(models, function (index, model) {
                        $('#vehicle_model').append(
                            $('<option>', {
                                value: model.model_id,
                                text: model.model
                            })
                        );
                    });
                },
                error: function () {
                    alert('Failed to load models');
                }
            });
        }
    });

    // Submit Form
 $j('#vehicleForm').on('submit', function (e) {
    e.preventDefault();
    var isUpdate = $j(this).find('input[name="idvehicledetails"]').length > 0;
    var formData = $j(this).serialize();

    $j.ajax({
        url: $j(this).attr('action'),
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Success response:', response);
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: isUpdate ? 'Updated' : 'Inserted',
                    text: isUpdate ? 'Vehicle successfully updated.' : 'Vehicle successfully inserted.',
                    width: 300
                });
               
                $j('#vehicleTable').DataTable().ajax.reload(null, false);
             
            } else {
                console.log('Server returned error:', response);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Something went wrong.',
                    width: 300
                });
            }
        },
        error: function (xhr, status, error) {
            console.log('AJAX error response:', xhr.responseText);

            let errorMessage = 'An unexpected error occurred.';
            try {
                const json = JSON.parse(xhr.responseText);
                errorMessage = json.message || errorMessage;
            } catch (e) {
                console.warn('Response is not valid JSON');
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                width: 300
            });
        }
    });
});


});
