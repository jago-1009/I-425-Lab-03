//This function get called when the signup hash is clicked.
function signup() {
    $('.img-loading, main, .form-signin, #li-signin').hide();
    $('.form-signup, #li-signup').show();

    $('.img-loading').show();
    e.preventDefault();
    let name = $('#signup-name').val();
    let username = $('#signup-username').val();
    let email = $('#signup-email').val();
    let password = $('#signup-password').val();
    const url = baseUrl_API + '/users';
    $.ajax({
        url: url,
        method: 'post',
        dataType: 'json',
        data: {name: name, username: username, email: email, password:
            password}
    }).done(function () {
        $('.img-loading').hide();
//show a message after a sussessful login
        showMessage('Signup Message',
            'Thanks for signing up. Your account has been created.');
        $('li#li-signin').show();
        $('li#li-signout').hide();
    }).fail(function (jqXHR, textStatus) {
        showMessage('Signup Error', JSON.stringify(jqXHR.responseJSON, null,
            4));
    }).always(function () {
        console.log('Signup has Completed.');
    });

    //window.location.hash = 'signup';
}

//submit the form to create a user account
$('form.form-signup').submit(function (e) {

});