
//twitter bootstrap script
	$("button#submit").click(function(){
		   	$.ajax({
    		   	type: "POST",
			url: "login.php",
			data: $('form#login').serialize(),
        		success: function(msg){
 	          		  $("#thanks").html(msg)
 		        	$("#form-content").modal('hide');	
 		        },
			error: function(){
				alert("failure");
				}
      			});
	});
