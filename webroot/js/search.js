$(document).ready(function(){

    var Shuffle = window.Shuffle;
    var elementGrid = document.querySelector('.grid');

    var sort_filter = "any";

    var shuffleInstance = new Shuffle(elementGrid, {
        itemSelector: '.grid-item'
    });

    // for all the movie pass as parameter
    // we call the ajax script to add the data to the page
    arrayMovies.forEach(element => {
        
        if (typeof element.title == 'undefined' || element.title == '') {
            title_name = element.name;
        } else {
            title_name = element.title;
        }

        $.ajax({
            type: "GET",
            url: 'app/LoadMovie.php',
            data: { title : title_name},
            success: function(data) {
                if (data != null && data != "null") {
                    if (data.image != "N/A") {
                        var movie_image = data.image;
                    } else {
                        var movie_image = "webroot/images/no-image.jpg";
                    }
                    
                    var item =  $("<div>").attr({
                        class: 'grid-item',
                        'data-title': data.name,
                        'data-date-created': data.year,
                        'data-runtime': data.runtime,
                        'data-rating': data.rating
                    }
                    ).append(
                        $("<div>").attr({
                            class: 'card card-movie'
                        }).append(
                            $("<div>").attr({
                                class: 'card-image'
                            }).append(
                                $("<img>").attr({ src: movie_image })
                            ), 
                            $("<div>").attr({
                                class: 'card-content'
                                }).append(
                                    $("<span>").attr({
                                        class: 'card-title activator grey-text text-darken-4'
                                    }).html(data.name),
                                    $("<p>").append(
                                        $("<a>").attr({
                                            href: 'index.php?page=movie&id=' + data.name
                                        }).html("See More")
                                    )
                                )
                        )
                    );
 
                    $(".grid").append(
                        item
                    );

                    shuffleInstance.add(item);
                    shuffleInstance.update();
                    sortView();
                }               
            }
        });
    });


    
    function sortView() {
        var option;
        switch (sort_filter) {
            case "any":
                options = {
                };
                break;
            case "rating":
                options = {
                    reverse: true,
                    by: sortByRating,
                };
                break;
            case "date":
                options = {
                    reverse: true,
                    by: sortByDate,
                };
                break;
            case "name":
                options = {
                    by: sortByTitle,
                };
                break;
            default:
                break;
        }

        shuffleInstance.sort(options);
    }


    function sortByTitle(element) {
        return element.getAttribute('data-title').toLowerCase();
    }

    function sortByDate(element) {
        return element.getAttribute('data-date-created');
    }

    function sortByRating(element) {
        return element.getAttribute('data-rating');
    }


    $( "#sort-by-any" ).click(function() {        
        sort_filter = "any";
        sortView();
    });
    $( "#sort-by-name" ).click(function() {
        sort_filter = "name";
        sortView();
    });
    $( "#sort-by-date" ).click(function() {        
        sort_filter = "date";
        sortView();
    });
    $( "#sort-by-rating" ).click(function() {        
        sort_filter = "rating";
        sortView();
    });


    /*
        FILTER SECTION

    */
    // INIT THE SLIDER
    var slider_rating = document.getElementById('filter-rating');
    noUiSlider.create(slider_rating, {
        start: [ 0 ],
        step: 0.1,
        range: {
            'min': [   0 ],
            'max': [ 10 ]
        }
    });

    var slider_runtime = document.getElementById('filter-runtime');
    noUiSlider.create(slider_runtime, {
        start: [ 0 ],
        step: 1,
        range: {
            'min': [   0 ],
            'max': [ 300 ]
        },
        format: wNumb({
            decimals: 0
          })
    });

    var slider_release = document.getElementById('filter-release');
    noUiSlider.create(slider_release, {
        start: [ 0 ],
        step: 1,
        range: {
            'min': [ 1900 ],
            'max': [ 2100 ]
        },
        format: wNumb({
            decimals: 0
        })
    });

    // define base var
    var minRelease = 0;
    var maxRuntime = 300;
    var minRating = 0;


    // Onclick Listener
    $( "#filter-rating" ).click(function() {
        minRating = parseFloat(slider_rating.noUiSlider.get());
        $('#rating-value').html(minRating);
        shuffleInstance.filter(function (element) {
            return checkFilter(element);
        });
    });

    $( "#filter-runtime" ).click(function() {
        maxRuntime = parseInt(slider_runtime.noUiSlider.get());
        $('#runtime-value').html(maxRuntime);
        shuffleInstance.filter(function (element) {
            return checkFilter(element);
        });
    });

    $( "#filter-release" ).click(function() {
        minRelease = parseInt(slider_release.noUiSlider.get());
        $('#release-value').html(minRelease);
        shuffleInstance.filter(function (element) {
            return checkFilter(element);
        });
    });

    function checkFilter(element) {
        return  parseFloat(element.getAttribute('data-rating')) >= minRating 
        && parseInt(element.getAttribute('data-runtime')) <= maxRuntime
        && parseInt(element.getAttribute('data-date-created')) >= minRelease;
    }
});