let apiURL = 'http://localhost:8000/api/'


function initListeners() {$('.link').on('click', function(e) {
     let href = $(this).attr('href')
     href = href.replace('#', '')
     $("#app").html(`<h1>${href}</h1>`)
     switch (href) {
        case 'movies':
           $("#app").html(`<h1>Movies</h1>`)
           break;
      case 'directors':
           $("#app").html(`<h1>Directors</h1>`)
           break;
      case 'studios':
           $("#app").html(`<h1>Studios</h1>`)
           break;
      case 'genres':
           $("#app").html(`<h1>Genres</h1>`)
           break;
      case 'reviews':
           $("#app").html(`<h1>Reviews</h1>`)
           break;
  
        default:
          $.ajax({
              type: "GET",
              url: "public/app/home.php",
              success: function (response) {
                  $("#app").html(response);
              }
          });
           break;
  
     }
  })
  
  $('#login').on('click', function(e) {
     e.preventDefault()
     $("#sign-in").css('display', 'flex')
     initListeners();
  })
  $('#sign-in-close').on('click', function(e) {
     $("#sign-in").css('display', 'none');
     initListeners();
  })
  $('#register').on('click', function(e) {
     e.preventDefault()
     $("#sign-up").css('display', 'flex')
     initListeners();
  })
  $('#register-close').on('click', function(e) {
     $("#sign-up").css('display', 'none');
     initListeners();
  })
  $('#sign-in-button').on('click', function(e) {
     let username = $('#sign-in-username').val()
     let password = $('#sign-in-password').val()
     e.preventDefault()     
     $.ajax({
          type: "POST",
          url: apiURL + '/reviewers/authBearer',
          data: {
               username: username,
               password: password
          },
          success: function (response) {
               $("#sign-in").css('display', 'none');
               initListeners();
               sessionStorage.setItem('token', response.token)
          }
          
     })
  })
}
 
$(document).ready(function () {
initListeners();
});