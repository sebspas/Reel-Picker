$(document).ready(function(){

    var Shuffle = window.Shuffle;
    var element = document.querySelector('.grid');

    var shuffleInstance = new Shuffle(element, {
        itemSelector: '.grid-item'
    });

    function sortByTitle(element) {
        return element.getAttribute('data-title').toLowerCase();
      }

    $( "#sort-by-name" ).click(function() {
        alert("aaaaa");
        var options = {
            by: sortByTitle,
        };
        
        shuffleInstance.sort(options);
      });


});