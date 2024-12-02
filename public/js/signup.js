const baseUrl_API = 'http://localhost:8000/api';

$(document).ready(function() {
    // Use event delegation and prevent multiple bindings
    $(document).on('submit', 'form.signup-form', function(e) {
        e.preventDefault();
        console.log("Signup");

        let name = $('#signup-name').val();
        let username = $('#signup-username').val();
        let password = $('#signup-password').val();

        if (!name || !username || !password) {
            alert("All fields are required.");
            return;
        }

        const url = baseUrl_API + '/reviewers';

        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                name: name,
                username: username,
                password: password
            }),
            success: function (response) {
                console.log('Signup successful', response);
                alert('Thanks for signing up. Your account has been created.');

                // Hide signup modal
                $("#sign-up").css('display', 'none');

                // Reset form fields
                $('#signup-name').val('');
                $('#signup-username').val('');
                $('#signup-password').val('');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Signup Error', textStatus, errorThrown);

                // Check if there's a specific error message from the server
                let errorMessage = jqXHR.responseJSON
                    ? (jqXHR.responseJSON.message || 'Signup failed')
                    : 'Signup failed. Please try again.';

                alert(errorMessage);
            }
        });
    });
});