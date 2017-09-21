$(document).ready(function(){
    $("#statstable").hide(); //hide the table before the csv files have been added to the database.
    $("button").click(function(){ //when the button is clicked, run the parse scripts and show the table.
        $.ajax({
            type: 'POST',
            url: 'parse.php',
            success: function(data) {
                alert(data);
                $("p").text(data);

            }
        });
        $("#statstable").show();
    });
});
