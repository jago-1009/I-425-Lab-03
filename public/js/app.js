let apiURL = 'http://localhost:8000/api';


function initListeners() {$('.link').on('click', function(e) {
    let href = $(this).attr('href')
    href = href.replace('#', '')
    $("#app").html(``)
    $(".header").html(``)

    switch (href) {
        case 'movies':
          let token = sessionStorage.getItem('token')
            $(".header").html(
                `<h1>Movies</h1>`)
            if (token) {
                $.ajax({
                    type: "GET",
                    url: apiURL + "/movies",
                    headers: {
                        "Authorization": `Bearer ${token}`
                    },
                    success: function (response) {
                        let cardsHTML = '<div class="cards">';

                        for (const key in response.data) {
                            if (response.data.hasOwnProperty(key)) {
                                const movie = response.data[key];
                                cardsHTML += `
                    <div class="card">
                        <div class="title">${movie.movieName}</div>
                        <div class="studio">By: Studio ${movie.studioId}</div>
                        <div class="r-date">Released On: ${movie.releaseDate}</div>
                    </div>`;
                            }
                        }

                        cardsHTML += '</div>'; // Close the .cards div

                        // Insert the generated HTML into the app container
                        $("#app").html(cardsHTML);
                    },
                    error: function (error) {
                        console.error("Error fetching movies:", error);
                    }
                });
                break;
            } else {
                $("#app").html(
                    `<p>Unauthorized access, please login.</p>`
            )
                break;
            }


        case 'directors':
            $(".header").html(`<h1>Directors</h1>`)
            break;
        case 'studios':
            $(".header").html(`<h1>Studios</h1>`)
            break;
        case 'genres':
            $(".header").html(`<h1>Genres</h1>`)
            break;
        case 'reviews':
            $(".header").html(`<h1>Reviews</h1>`)
            break;

        default:
            $(".header").html(``)

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
                sessionStorage.setItem('token', response.token)
            }

        })
    })
}

$(document).ready(function () {
    initListeners();
});