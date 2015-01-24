//**********************************************************************************
//set baseURL
//**********************************************************************************

baseURL = window.location.protocol+'//'+window.location.hostname;

//**********************************************************************************

function get_query_param(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function sign_in() {
	var email = $('#si_email').val();
	var pw = $('#si_pw').val();
	var url = $('#si_url').val();

	var post = $.post(
		'fn_signin.php',
		'email='+email+'&pw='+pw+'&url='+url,
		function() {
			var json_response = JSON.parse(post.responseText);

			location.href = baseURL+'/'+json_response.url;
		}
	);
}

function sign_out() {
	var ajax = $.ajax({
		type : 'GET',
		async : false, //because we insert the content after the response
		url : baseURL+'/education/fn_sign_out.php'
	});

	ajax.done(function(jqXHR, textStatus) {
		location.reload(true);
	});
}

//************************************************
//add load event
//************************************************

function add_load_event(func) {   
	var oldonload = window.onload;   
	if(typeof window.onload != 'function') {   
		window.onload = func;   
	}
	else {   
		window.onload = function() {   
			if(oldonload) {   
				oldonload();   
			}   
			func();   
		}   
	}   
}