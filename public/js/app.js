let apiURL = "http://localhost:8000/api";

function initListeners() {
    let token = sessionStorage.getItem("token");

  $(".link").on("click", function (e) {
    e.preventDefault();
    let href = $(this).attr("href");
    href = href.replace("#", "");
    $("#app").html(``);
    $(".header").html(``);

    switch (href) {
      case "movies":
        $(".header").html(`<h1>Movies</h1>`);
        if (token) {
          $.ajax({
            type: "GET",
            url: apiURL + "/movies",
            headers: {
              Authorization: `Bearer ${token}`,
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

              cardsHTML += "</div>"; // Close the .cards div

              // Insert the generated HTML into the app container
              $("#app").html(cardsHTML);
            },
            error: function (error) {
              console.error("Error fetching movies:", error);
            },
          });
          break;
        } else {
          $("#app").html(`<p>Unauthorized access, please login.</p>`);
          break;
        }

        case "directors":
            $(".header").html(`<h1 style="background-color: #D3D3D3">Directors</h1>
    <div class="sort-buttons" style="background-color: #D3D3D3">
        <button class="sort-btn" data-sort-by="name" data-order="ASC">Sort by Name (A-Z)</button>
        <button class="sort-btn" data-sort-by="name" data-order="DESC">Sort by Name (Z-A)</button>
        <button class="sort-btn" data-sort-by="birthDate" data-order="ASC">Sort by Birth Date (Oldest to Youngest)</button>
        <button class="sort-btn" data-sort-by="birthDate" data-order="DESC">Sort by Birth Date (Youngest to Oldest)</button>
        <button class="pagination-btn" id="previous-page-btn" style="display: none;">Previous Page</button>
        <button class="pagination-btn" id="next-page-btn">Next Page</button>
    </div>
    `);

            const url = apiURL + "/directors";
            let currentPage = 1;
            const resultsPerPage = 5;

        function fetchAndDisplayDirectors(sortBy, order) {
            if (token) {
                axios.get(url, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                }).then(async response => {
                    let directors = Object.values(response.data);

                    // Sort directors based on the selected criteria
                    directors.sort((a, b) => {
                        if (order === "ASC") {
                            if (sortBy === 'birthDate' || sortBy === 'deathDate') {
                                return new Date(a[sortBy]) - new Date(b[sortBy]);
                            }
                            return a[sortBy] > b[sortBy] ? 1 : -1;
                        } else {
                            if (sortBy === 'birthDate' || sortBy === 'deathDate') {
                                return new Date(b[sortBy]) - new Date(a[sortBy]);
                            }
                            return a[sortBy] < b[sortBy] ? 1 : -1;
                        }
                    });

                    // Pagination: slice directors for current page
                    const start = (currentPage - 1) * resultsPerPage;
                    const end = start + resultsPerPage;
                    const paginatedDirectors = directors.slice(start, end);

                    let cardsHTML = '<div class="cards">';

                    // Loop through directors to build the cards
                    for (const director of paginatedDirectors) {
                        console.log(`Processing director: ${director.id} - ${director.name}`);
                        let moviesByDirector = '<ul>';

                        try {
                            const moviesResponse = await axios.get(`${apiURL}/directors/${director.id}/movies`, {
                                headers: {
                                    Authorization: `Bearer ${token}`
                                }
                            });

                            console.log(`Movies for director ${director.id}:`, moviesResponse.data);

                            if (moviesResponse.data && typeof moviesResponse.data === 'object') {
                                for (const key in moviesResponse.data) {
                                    if (moviesResponse.data.hasOwnProperty(key)) {
                                        const movie = moviesResponse.data[key];
                                        console.log(`Movie: ${movie.movieName}`);
                                        moviesByDirector += `<li>${movie.movieName.trim()}</li>`;
                                    }
                                }
                            } else {
                                moviesByDirector += '<li>No movies found</li>';
                            }
                        } catch (error) {
                            console.error(`Error fetching movies for director ${director.id}:`, error);
                            moviesByDirector = '<ul><li>Error fetching movies</li></ul>';
                        }

                        moviesByDirector += '</ul>';

                        cardsHTML += `
                        <div class="card">
                            <div class="title">${director.name}</div>
                            <div class="studio">Born On: ${director.birthDate}</div>
                    `;
                        if (director.deathDate) {
                            cardsHTML += `<div class="r-date">Died On: ${director.deathDate}</div>`;
                        }
                        cardsHTML += `<div>Movies: ${moviesByDirector}</div>`;
                        cardsHTML += `</div>`;
                    }

                    cardsHTML += "</div>"; // Close the .cards div

                    // Insert the generated HTML into the app container
                    $("#app").html(cardsHTML);

                    // Update the visibility of pagination buttons
                    if (currentPage === 1) {
                        $("#previous-page-btn").hide();
                    } else {
                        $("#previous-page-btn").show();
                    }

                    if (directors.length <= currentPage * resultsPerPage) {
                        $("#next-page-btn").hide();
                    } else {
                        $("#next-page-btn").show();
                    }

                }).catch(error => {
                    console.error('Error fetching directors:', error);
                });
            }
        }

            // Initial fetch and display
            fetchAndDisplayDirectors("name", "ASC");

            // Add event listeners to sort buttons
            $(".sort-btn").on("click", function() {
                const sortBy = $(this).data("sort-by");
                const order = $(this).data("order");
                currentPage = 1; // Reset to page 1 on sort change
                fetchAndDisplayDirectors(sortBy, order);
            });

            // Add event listener for the next page button
            $("#next-page-btn").on("click", function() {
                currentPage++;
                fetchAndDisplayDirectors("name", "ASC"); // Fetch with the current sorting
            });

            // Add event listener for the previous page button
            $("#previous-page-btn").on("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    fetchAndDisplayDirectors("name", "ASC"); // Fetch with the current sorting
                }
            });



            break;
      case "studios":
        $(".header").html(`<h1>Studios</h1>`);
        break;
      case "genres":
        $(".header").html(`<h1>Genres</h1>`);
        break;
      case "reviews":
        $(".header").html(`<h1>Reviews</h1>`);
        break;
    case "search":
        e.preventDefault();
        let searchInput = $("#search-input").val();
        let href=`/public?q=${searchInput}`
        $('.header').html(`<h1>Search Results for "${searchInput}"</h1>`)
        
        if (token) {
          $.ajax({
            type: "GET",
            url: apiURL + "/movies",
            data: { q: searchInput },
            headers: {
              Authorization: `Bearer ${token}`,
            },
            success: function (response) {
              let cardsHTML = '<div class="cards">';
                console.log(response)
             for (const key in response) {
               if (response.hasOwnProperty(key)) {
                 const movie = response[key];
                 cardsHTML += `
                   <div class="card">
                       <div class="title">${movie.movieName}</div>
                       <div class="studio">By: Studio ${movie.studioId}</div>
                       <div class="r-date">Released On: ${movie.releaseDate}</div>
                   </div>`;
               }
             }
    
              cardsHTML += "</div>";
              $("#app").html(cardsHTML);
            },
          });
        }
      break;

      default:
        $(".header").html(``);

        $.ajax({
          type: "GET",
          url: "public/app/home.php",
          success: function (response) {
            $("#app").html(response);
          },
        });
        break;
    
}
  });

  $("#login").on("click", function (e) {
    e.preventDefault();
    $("#sign-in").css("display", "flex");
    initListeners();
  });
  $("#sign-in-close").on("click", function (e) {
    $("#sign-in").css("display", "none");
    initListeners();
  });
  $("#register").on("click", function (e) {
    e.preventDefault();
    $("#sign-up").css("display", "flex");
    initListeners();
  });
  $("#register-close").on("click", function (e) {
    $("#sign-up").css("display", "none");
    initListeners();
  });
  $("#sign-in-button").on("click", function (e) {
    let username = $("#sign-in-username").val();
    let password = $("#sign-in-password").val();
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: apiURL + "/reviewers/authBearer",
      data: {
        username: username,
        password: password,
      },
      success: function (response) {
        $("#sign-in").css("display", "none");
        sessionStorage.setItem("token", response.token);
      },
    });
  });
}

$(document).ready(function () {
  initListeners();
});