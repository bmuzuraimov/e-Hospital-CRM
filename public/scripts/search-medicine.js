$(document).ready(function(){
	$("#nsuggest").keyup(function(){
		$.get("/doctor/searchmedicine", {medicine: $(this).val(), type:"neutralize"}, function(data){
			$("#neutrallist").empty();
			$("#neutrallist").html(data);
		});
	});
	$("#psuggest").keyup(function(){
		$.get("/doctor/searchmedicine", {medicine: $(this).val(), type:"pneumonia"}, function(data){
			$("#pneumonialist").empty();
			$("#pneumonialist").html(data);
		});
	});
	$("#ssuggest").keyup(function(){
		$.get("/doctor/searchmedicine", {medicine: $(this).val(), type:"symptoms"}, function(data){
			$("#symptomslist").empty();
			$("#symptomslist").html(data);
		});
	});
	$("#isuggest").keyup(function(){
		$.get("/doctor/searchmedicine", {medicine: $(this).val(), type:"immune"}, function(data){
			$("#immunelist").empty();
			$("#immunelist").html(data);
		});
	});
});
function updateTextInput(val) 
{
   	document.getElementById('bar').value=val;
    document.getElementById("temp").innerHTML = val;
}
function appendText(id, val) 
{
	$('#'+id).val($('#'+id).val() + ' мг');
}