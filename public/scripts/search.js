$(document).ready(function(){
	$("#search_referal").keyup(function(){
		var ref_name = $(this).val();
		$.get("/admin/suggest", {name: ref_name}, function(data){
			$("#referal_list").empty();
			$("#referal_list").html(data);
		});
	});
});