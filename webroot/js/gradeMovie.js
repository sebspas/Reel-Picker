$(document).ready(function(){
    // booleand to assure the form is post only once
    posted = true;

    // submit the form when we click on a star
    $('.stars').on('click',function(){
        $('form#ratingsForm').submit();
    });

    // on submit of the grade form
    $("form#ratingsForm").submit(function(event) {
        event.preventDefault();
        if (!posted) {
            $.ajax({
                type: $(this).attr("method"),
                url: $(this).attr("action"),
                data: $(this).serialize(),
                success: function(){
                    posted = true;
                }
            });
        } else {
            posted = false;
        }
       
    });
});