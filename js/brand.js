$(document).ready(function() {

  function loadBrands() {
    $.ajax({
        url: '../../actions/fetch_brand_action.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Response received:', response); // ADD THIS
            
            if (response.status === 'success') {
                const data = response.data;
                console.log('Data:', data); // ADD THIS
                let rows = '';
                if (data.length > 0) {
                    data.forEach((brand, i) => {
                        rows += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${brand.brand_name}</td>
                                <td>${brand.cat_name}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editBtn" data-id="${brand.brand_id}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${brand.brand_id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = `<tr><td colspan="4" class="text-center">No brands found</td></tr>`;
                }
                $('#brandTable').html(rows);
            } else {
                console.error('Error response:', response); // ADD THIS
                alert(response.message || 'Error: Invalid response format.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.error('Response Text:', xhr.responseText); // ADD THIS
            console.error('Status:', status); // ADD THIS
            alert('Failed to load brands.');
        }
    });
    }

    // ✅ Call it when page loads
    loadBrands();

    // Add brand
    $('#addBrandForm').submit(function(e){
        e.preventDefault();
        const brand_name = $('#brand_name').val();
        const cat_id = $('#brand_category').val();
        if(!brand_name || !cat_id){
            alert('Please fill in all fields');
            return;
        }

        $.ajax({
            url: '../../actions/add_brand_action.php',
            type: 'POST',
            data: { brand_name, cat_id },
            dataType: 'json',
            success: function(response){
                alert(response.message);
                if(response.status === 'success'){
                    $('#brand_name').val('');
                    $('#brand_category').val('');
                    loadBrands();
                }
            },
            error: function(){
                alert('Failed to add brand.');
            }
        });
    });

    // Edit brand
    $(document).on('click', '.editBtn', function(){
        const id = $(this).data('id');
        const newName = prompt('Enter new brand name:');
        if(newName){
            $.ajax({
                url: '../../actions/update_brand_action.php',
                type: 'POST',
                data: { brand_id: id, brand_name: newName },
                dataType: 'json',
                success: function(response){
                    alert(response.message);
                    if(response.status === 'success'){
                        loadBrands();
                    }
                },
                error: function(){
                    alert('Failed to update brand.');
                }
            });
        }
    });

    // Delete brand
    $(document).on('click', '.deleteBtn', function(){
        const id = $(this).data('id');
        if(confirm('Are you sure you want to delete this brand?')){
            $.ajax({
                url: '../../actions/delete_brand_action.php',
                type: 'POST',
                data: { brand_id: id },
                dataType: 'json',
                success: function(response){
                    alert(response.message);
                    if(response.status === 'success'){
                        loadBrands();
                    }
                },
                error: function(){
                    alert('Failed to delete brand.');
                }
            });
        }
    });

});
