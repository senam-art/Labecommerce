$(document).ready(function() {
    // // Show/hide product form
    // $('#showProductFormBtn').click(function() {
    //     $('#productForm').removeClass('hidden').show();
    //     $(this).hide();
    // });

    // Modal is handled by Bootstrap (data attributes); no manual open/close needed here.

    // Load products on page load
    loadProducts();

    // Handle product form submission
    $('#productForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Get keywords from Tagify
        if (window.keywordTagify) {
            // Get array of tag values
            const keywordsArray = window.keywordTagify.value.map(tag => tag.value.trim()).filter(Boolean);
            
            // Send as comma-separated string, or JSON if your backend expects it
            formData.set('keywords', keywordsArray.join(',')); 
            // OR for JSON: formData.set('keywords', JSON.stringify(keywordsArray));
        } else {
            // If Tagify instance is not found, send empty string
            formData.set('keywords', '');
        }
    
        //--- FILEPOND HANDLING ---

        const pond = FilePond.find(document.querySelector('#images'));
        if (pond) {
            const files = pond.getFiles();

            // Remove any existing image entries
            formData.delete('images[]');

            // Add each file from FilePond
            files.forEach(fileItem => {
                formData.append('images[]', fileItem.file);
            });
        } else {
            // Optional: log a warning if FilePond is not initialized
            console.warn('FilePond instance not found, skipping file upload');
        }

        $.ajax({
            url: '../../actions/add_product_action.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if (data.status === 'success') {
                        alert('Product added successfully!');
                        loadProducts();
                        resetProductForm();
                        // Hide bootstrap modal after success
                        const modalEl = document.getElementById('productModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modalInstance.hide();
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (err) {
                    console.error('Parse error:', err);
                    alert('An error occurred while processing the response');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('An error occurred while adding the product');
            }
        });
    });

    // Load products into table
    function loadProducts() {
        $.ajax({
            url: '../../actions/fetch_products_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const products = response.data || response;
                let html = '';
                
                if (products.length === 0) {
                    html = '<tr><td colspan="7" class="text-center">No products found</td></tr>';
                } else {
                    products.forEach((product, index) => {
                        // Get first image or use placeholder
                        const imageUrl = product.images && product.images.length > 0 
                            ? product.images[0] 
                            : 'placeholder.jpg';
                        
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${escapeHtml(product.product_title)}</td>
                                <td>$${parseFloat(product.product_price).toFixed(2)}</td>
                                <td>${escapeHtml(product.cat_name || 'N/A')}</td>
                                <td>${escapeHtml(product.brand_name || 'N/A')}</td>
                                <td><img src="${imageUrl}" width="50" height="50" alt="Product"></td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-product" data-id="${product.product_id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-product" data-id="${product.product_id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                $('#productTable').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Error loading products:', error);
                $('#productTable').html('<tr><td colspan="7" class="text-center text-danger">Error loading products</td></tr>');
            }
        });
    }

    // Reset product form
    function resetProductForm() {
        $('#productForm')[0].reset();
        
        // Reset FilePond
        const pond = FilePond.find(document.querySelector('#images'));
        if (pond) {
            pond.removeFiles();
        }
        
        // Reset Tagify
        if (window.keywordTagify) {
            window.keywordTagify.removeAllTags();
        }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    }

    // Handle delete product
    $(document).on('click', '.delete-product', function() {
        const productId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: '../../actions/delete_product_action.php',
                method: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Product deleted successfully!');
                        loadProducts();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the product');
                }
            });
        }
    });

    // Handle edit product (implement as needed)
    $(document).on('click', '.edit-product', function() {
        const productId = $(this).data('id');
        // Implement edit functionality
        alert('Edit functionality for product ID: ' + productId);
    });
});