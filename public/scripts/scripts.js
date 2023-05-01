$(document).ready(function() {
    $('.menu-toggle').on('click', function() {
        $('.nav').toggleClass('showing');
        $('.nav ul').toggleClass('showing');
    });
    $("#feedback-btn").click(function(){
        $.post($("#feedback-form").attr("action"), $("#feedback-form :input").serializeArray(), function(info){
            $("#msg").html(info);
            $(".contact-input").val("");
        });
    });
    $("#feedback-form").submit(function(){
        return false;
    });
});
function updateTextInput(val) 
{
   	document.getElementById('bar').value=val;
    document.getElementById("temp").innerHTML = val;
}