
$('.link').on('click', function(e) {
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
            url: "./app/home.php",
            success: function (response) {
                $("#app").html(response);
            }
        });
         break;

   }
})