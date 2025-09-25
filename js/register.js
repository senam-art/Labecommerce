
$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();

        // Collect form values
        let name = $('#fullname').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let confirmPassword = $('#confirmPassword').val();
        let phone_number = $('#contact').val();
        let country = $('#country').val();
        let city = $('#city').val();

        // Check if all fields are filled
        if (name == '' || email == '' || password == '' || country == '' || phone_number == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        }

        // Check if password matches confirm password
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!',
            });
            return;
        }

        // Check password strength
        if (password.length < 6 || !password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number!',
            });
            return;
        }

        // AJAX request
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: {
                name: name,
                email: email,
                password: password,
                phone_number: phone_number,
                country: country,
                city: city,
            },
            dataType: 'json', // parse response as JSON
            success: function(response) {
                console.log('AJAX success response:', response); // TEMP: log response

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message || 'Unknown server error',
                    });
                }
            },
            error: function(xhr, status, error) {
                // TEMP: log full error details
                console.error('AJAX error:', status, error);
                console.error('Response text:', xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Request Failed',
                    text: `Status: ${status}\nError: ${error}\nResponse: ${xhr.responseText}`,
                });
            }
        });
    });
});
