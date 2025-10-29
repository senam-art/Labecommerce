$(document).ready(function() {  
    
    // Hide form initially  
    $('#productForm').hide();  

    // Show form when button clicked  
    $('#showProductFormBtn').click(function() {  
        $(this).hide(); // hide the button  
        $('#productForm').slideDown(); // show the form  
    });  

    // Hide form when cancel button clicked  
    $('#cancelProductFormBtn').click(function() {  
        $('#productForm').slideUp(); // hide the form  
        $('#showProductFormBtn').show(); // show the button again  
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
                            rows += `  
                                <tr>  
                                    <td>${i + 1}</td>  
                                    <td>${product.product_title}</td>  
                                    <td>${product.cat_name}</td>  
                                    <td>${product.brand_name}</td>  
                                    <td>${product.product_price}</td>  
                                    <td>${product.product_desc}</td>  
                                    <td><img src="${product.product_image}" width="60" height="60" alt="Product"></td>  
                                    <td>${product.product_keywords}</td>  
                                    <td>  
                                        <button class="btn btn-sm btn-warning editBtn" data-id="${product.product_id}">Edit</button>  
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${product.product_id}">Delete</button>  
                                    </td>  
                                </tr>  
                            `;  
                        });  
                    } else {  
                        rows = `<tr><td colspan="9" class="text-center">No products found</td></tr>`;  
                    }  

                    // ✅ FIX: Missing # in selector  
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

    // ✅ Load products on page load
    loadProducts();

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
                    pond.removeFiles(); // clear FilePond
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
});
