$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); 
        
        let email = $('#email').val().trim();
        let password = $('#password').val();
        
        
        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all fields',
            });
            return;
        }
        
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Logging in...');
        
       
        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            timeout: 10000, 
            success: function (response) {
                console.log('AJAX success:', response);
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '../index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                console.error('Response text:', xhr.responseText);
                
                
                let errorMessage = 'An error occurred during login.';
                if (status === 'timeout') {
                    errorMessage = 'Request timed out. Please try again.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Login service not found.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: errorMessage,
                });
            },
            complete: function() {
              
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
});