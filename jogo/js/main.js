$(function(){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
});
$(function() {
    
    $('#login-form-link').click(function(e) {
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

});


/*
$("#LoadingImage").show();
      $.ajax({ 
        type: "GET", 
        url: surl, 
        dataType: "jsonp", 
        cache : false, 
        jsonp : "onJSONPLoad", 
        jsonpCallback: "newarticlescallback", 
        crossDomain: "true", 
        success: function(response) { 
          $("#LoadingImage").hide();
          alert("Success"); 
        }, 
        error: function (xhr, status) {  
          $("#LoadingImage").hide();
          alert('Unknown error ' + status); 
        }    
      });  
    }*/
