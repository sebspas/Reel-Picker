$(document).ready(function(){
    var Shuffle = window.Shuffle;
    var element = document.querySelector('.grid');

    var shuffleInstance = new Shuffle(element, {
        itemSelector: '.grid-item'
    });

    function sortByTitle(element) {
        return element.getAttribute('data-title').toLowerCase();
    }

    function sortByDate(element) {
        return element.getAttribute('data-date-created');
    }

    function sortByRating(element) {
        return element.getAttribute('data-rating');
    }


    $( "#sort-by-name" ).click(function() {
        var options = {
            by: sortByTitle,
        };
        
        shuffleInstance.sort(options);
      });


    $( "#sort-by-date" ).click(function() {        
        var options = {
            reverse: true,
            by: sortByDate,
        };
        
        shuffleInstance.sort(options);
    });


    $( "#sort-by-any" ).click(function() {        
        var options = {
        };
        
        shuffleInstance.sort(options);
    });

    $( "#sort-by-rating" ).click(function() {        
        var options = {
            by: sortByRating,
        };
        
        shuffleInstance.sort(options);
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
        minRating = slider_rating.noUiSlider.get();
        $('#rating-value').html(minRating);
        shuffleInstance.filter(function (element) {
            return checkFilter(element);
            //return element.getAttribute('data-rating') >= minRating;
        });
    });

    $( "#filter-runtime" ).click(function() {
        maxRuntime = parseInt(slider_runtime.noUiSlider.get());
        $('#runtime-value').html(maxRuntime);
        shuffleInstance.filter(function (element) {
            return checkFilter(element);
            //return  parseInt(element.getAttribute('data-runtime')) <= maxRuntime;
        });
    });

    $( "#filter-release" ).click(function() {
        minRelease = parseInt(slider_release.noUiSlider.get());
        $('#release-value').html(minRelease);
        shuffleInstance.filter(function (element) {
            return checkFilter(element);
            //return  parseInt(element.getAttribute('data-date-created')) >= minRelease;
        });
    });


    function checkFilter(element) {
        return element.getAttribute('data-rating') >= minRating 
        && parseInt(element.getAttribute('data-runtime')) <= maxRuntime
        && parseInt(element.getAttribute('data-date-created')) >= minRelease;
    }
});