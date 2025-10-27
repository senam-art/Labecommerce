$(document).ready(function() {
    function loadCategories() {
        $.ajax({
            url: '../../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    let rows = '';
                    if (data.length > 0) {
                        data.forEach((cat, i) => {
                            rows += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${cat.cat_name}</td>
                                    <td>${cat.created_by}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editBtn" data-id="${cat.cat_id}">Edit</button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${cat.cat_id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = `<tr><td colspan="4" class="text-center">No categories found</td></tr>`;
                    }
                    $('#categoryTable').html(rows);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to load categories.');
            }
        });
    }

    // Initial load
    loadCategories();

    // Add category
    $('#addCategoryForm').submit(function(e) {
        e.preventDefault();

        const cat_name = $('#cat_name').val();
        if (!cat_name) {
            alert('Please enter a category name');
            return;
        }

        $.ajax({
            url: '../../actions/add_category_action.php',
            type: 'POST',
            data: { cat_name },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    $('#cat_name').val('');
                    loadCategories();
                }
            },
            error: function() {
                alert('Failed to add category.');
            }
        });
    });

    // Edit category
    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');
        const newName = prompt('Enter new category name:');
        if (newName) {
            $.ajax({
                url: '../../actions/update_category_action.php',
                type: 'POST',
                data: { cat_id: id, cat_name: newName },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    if (response.status === 'success') {
                        loadCategories();
                    }
                },
                error: function() {
                    alert('Failed to update category.');
                }
            });
        }
    });

    // Delete category
    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to DELETE this category?')) {
            $.ajax({
                url: '../../actions/delete_category_action.php',
                type: 'POST',
                data: { cat_id: id },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    if (response.status === 'success') {
                        loadCategories();
                    }
                },
                error: function() {
                    alert('Failed to delete category.');
                }
            });
        }
    });
});
