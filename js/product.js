$(document).ready(function() {  
    
    // Hide form initially  
    $('#productForm').hide();  

    // Show form when button clicked  
    $('#showProductFormBtn').click(function() {  
        $(this).hide();
        $('#productForm').slideDown();
    });  

    // Hide form when cancel button clicked  
    $('#cancelProductFormBtn').click(function() {  
        $('#productForm').slideUp();
        $('#showProductFormBtn').show();
    });  

    // Load products
    function loadProducts() {  
        $.ajax({  
            url: '../../actions/fetch_products_action.php',  
            type: 'GET',  
            dataType: 'json',  
            success: function (response) {  
                let rows = '';  

                if (response.status === 'success') {  
                    const data = response.data;  
                    console.log('Data:', data);  

                    if (data.length > 0) {  
                        data.forEach((product, i) => {
                            // Get first image or use placeholder
                            let firstImage = '../../uploads/placeholder.jpg';
                            if (product.product_images) {
                                const images = product.product_images.split(',');
                                firstImage = images[0];
                            }
                            
                            rows += `  
                                <tr>  
                                    <td>${i + 1}</td>  
                                    <td>${product.product_title}</td>  
                                    <td>$${parseFloat(product.product_price).toFixed(2)}</td>  
                                    <td>${product.cat_name}</td>  
                                    <td>${product.brand_name}</td>  
                                    <td><img src="${firstImage}" alt="${product.product_title}" style="width: 50px; height: 50px; object-fit: cover;"></td>  
                                    <td>  
                                        <button class="btn btn-sm btn-warning editBtn" data-id="${product.product_id}">Edit</button>  
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${product.product_id}">Delete</button>  
                                    </td>  
                                </tr>  
                            `;  
                        });  
                    } else {  
                        rows = `<tr><td colspan="7" class="text-center">No products found</td></tr>`;  
                    }  

                    $('#productTable').html(rows);  
                } else {  
                    console.error('Error response:', response);  
                    alert(response.message || 'Error: Invalid response format.');  
                }  
            },  
            error: function (xhr, status, error) {  
                console.error('AJAX Error:', error);  
                console.error('Response Text:', xhr.responseText);  
                console.error('Status:', status);  
                alert('Failed to load products.');  
            }  
        });  
    }  

    // Load products on page load
    loadProducts();

    // Submit product form
    $('#productForm').submit(async function(e) {  
        e.preventDefault();  

        let formData = new FormData(this);

        // Get FilePond instance and files
        const pond = FilePond.find(document.querySelector('#images'));
        const files = pond.getFiles();

        // Append each file to formData manually
        files.forEach((file, index) => {
            formData.append('images[]', file.file); 
        });

        $.ajax({  
            url: '../../actions/add_product_action.php',  
            type: 'POST',  
            data: formData,  
            contentType: false,  
            processData: false,  
            dataType: 'json',  
            success: function(response) {  
                alert(response.message);  

                if (response.status === 'success') {  
                    $('#productForm')[0].reset();  
                    pond.removeFiles();
                    $('#productForm').slideUp();  
                    $('#showProductFormBtn').show();  
                    loadProducts();  
                }  
            },  
            error: function(xhr, status, error) {  
                console.error('Error adding product:', error);  
                console.error('Response Text:', xhr.responseText);  
                alert('Failed to add product.');  
            }  
        });  
    });

    // Delete product
    $(document).on('click', '.deleteBtn', function() {
        if (!confirm('Are you sure you want to delete this product?')) return;
        
        const productId = $(this).data('id');
        
        $.ajax({
            url: '../../actions/delete_product_action.php',
            type: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    loadProducts();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting product:', error);
                alert('Failed to delete product.');
            }
        });
    });
});