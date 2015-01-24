var request = new Object();
request.course = '';
request.course_display = 'Select Course';
request.date = '';
request.date_display = 'Select Date';
request.classmate = '';
request.classmate_display = 'Select Classmate';
request.classmate_email = '';
request.classmate_image = baseURL + '/education/img/request_person.svg';
request.loading = false;

//****************************************************************************************************
//main
//****************************************************************************************************

function request_load_main() {
	var return_content = '';

	var link_class = 'request_button';
	var submit_class = 'request_submit_button';

	if(request.course == '') {
		link_class = 'request_button_disabled';
	}
	if((request.course == '') || (request.date == '') || (request.classmate == '')) {
		submit_class = 'request_submit_button_disabled';
	}

	return_content += '<div class="request_title">Ask a classmate to record a lecture</div>';

	return_content += '<a class="request_button" href="javascript:request_load_courses();"><img src="'+baseURL+'/education/img/request_course.png"><div>'+request.course_display+'</div></a>';
	return_content += '<a class="'+link_class+'" style="border-top:none;" href="javascript:request_load_dates();"><img src="'+baseURL+'/education/img/request_calendar.png"><div>'+request.date_display+'</div></a>';
	return_content += '<a class="'+link_class+'" style="border-top:none;" href="javascript:request_load_classmates();"><img src="'+request.classmate_image+'"><div>'+request.classmate_display+'</div></a>';

	return_content += '<a class="'+submit_class+'" href="javascript:request_submit_request();">Send Request</a>';

	document.getElementById('request_container').innerHTML = return_content;
};

//load on document ready
$(request_load_main);

//****************************************************************************************************
//select course
//****************************************************************************************************

//server info
var ketchup_server = new Object();
ketchup_server.api_key = 'this_is_the_api_key';
ketchup_server.api_pw = 'this_is_the_api_pw';

function request_load_courses() {
	var return_content = '';

	var ajax = $.ajax({
		type : 'GET',
		async : false, //because we insert the content after the response
		url : baseURL + '/education/fn_api_get_courses_for_student.php',
		data : 'api_key=' + ketchup_server.api_key + '&api_pw=' + ketchup_server.api_pw + '&student=' + user.id
	});

	ajax.fail(function(jqXHR, textStatus) {
		return_content += 'The following error occurred:';
		return_content += '<br>';
		return_content += jqXHR.statusText;
		return_content += '<br>';
		return_content += '<a href="javascript:request_load_courses();">Click to retry</a>';
	});

	ajax.done(function(jqXHR, textStatus) {
		var response = JSON.parse(ajax.responseText);
		if (response.success == true) {
			var courses = response.courses;

			var course_count = courses.length;
			if (course_count == 0) {
				return_content += 'No courses were found<br><a href="javascript:request_load_main();">Back</a>';
			}
			else {
				return_content += '<div class="request_title">Select a Course</div>';
				for (var j = 0; j < course_count; j++) {
					var style = '';
					if(j > 0) {
						var style = ' style="border-top:none;"';
					}
					var course_link = '<a href="javascript:request_select_course(\'' + courses[j].course_id + '\', \'' + courses[j].course_name + '\');" class="request_button"'+style+'>';
					course_link += courses[j].course_name + '</a>';
					return_content += course_link;
				}
			}
		} else {
			return_content += response.message;
		}
	});

	document.getElementById('request_container').innerHTML = return_content;
};

function request_select_course(course_id, course_name) {
	request.course = course_id;
	request.course_display = course_name;

	//reset date and classmate
	request.date = '';
	request.date_display = 'Select Date';
	request.classmate = '';
	request.classmate_display = 'Select Classmate';
	request.classmate_email = '';
	request.classmate_image = baseURL + '/education/img/request_person.svg';

	request_load_main();
};

//****************************************************************************************************
//select date
//****************************************************************************************************

function request_load_dates() {
	if(request.course == '') {
		alert('You must select a course before selecting a date.');
	}
	else {
		var return_content = '';

		var ajax = $.ajax({
			type : 'GET',
			async : false, //because we insert the content after the response
			url : baseURL + '/education/fn_api_get_dates_for_course.php',
			data : 'api_key=' + ketchup_server.api_key + '&api_pw=' + ketchup_server.api_pw + '&course_id=' + request.course
		});

		ajax.fail(function(jqXHR, textStatus) {
			return_content += 'The following error occurred:';
			return_content += '<br>';
			return_content += jqXHR.statusText;
			return_content += '<br>';
			return_content += '<a href="javascript:request_load_dates();">Click to retry</a>';
		});

		ajax.done(function(jqXHR, textStatus) {
			var response = JSON.parse(ajax.responseText);
			if (response.success == true) {
				var dates = response.dates;

				var date_count = dates.length;
				if (date_count == 0) {
					return_content += 'No dates found<br><a href="javascript:request_load_main();">Back</a>';
				} else {
					return_content += '<div class="request_title">Select a Date</div>';

					for (var j = 0; j < date_count; j++) {
						var style = '';
						if(j > 0) {
							var style = ' style="border-top:none;"';
						}

						var date_link = '<a href="javascript:request_select_date('+dates[j].ccyymmdd+');" class="request_button"'+style+'>';
						date_link += dates[j].display_date + '</a>';
						return_content += date_link;
					}
				}
			} else {
				return_content += response.message;
			}
		});

		document.getElementById('request_container').innerHTML = return_content;
	}
}

function request_select_date(date) {
	request.date = date;
	var date_displaystr = "" + date;
	var year = date_displaystr.substring(0,4);
	var month = date_displaystr.substring(4,6);
	var day = date_displaystr.substring(6,8);
	var date = new Date(year, month-1, day);
	request.date_display = date.toDateString();

	request_load_main();
};

//****************************************************************************************************
//select classmate
//****************************************************************************************************

function request_load_classmates() {
	if(request.course == '') {
		alert('You must select a course before selecting a classmate.');
	}
	else {
		var return_content = '';

		var ajax = $.ajax({
			type : 'GET',
			url : baseURL + '/education/fn_api_get_students_for_course.php',
			data : 'api_key=' + ketchup_server.api_key + '&api_pw=' + ketchup_server.api_pw + '&student_id=' + user.id + '&course_id=' + request.course
		});

		ajax.fail(function(jqXHR, textStatus) {
			return_content += 'The following error occurred:';
			return_content += '<br>';
			return_content += jqXHR.statusText;
			return_content += '<br>';
			return_content += '<a href="javascript:request_load_dates();">Click to retry</a>';
		});

		ajax.done(function(jqXHR, textStatus) {
			var response = JSON.parse(ajax.responseText);
			if (response.success == true) {
				var classmates = response.students;

				var classmate_count = classmates.length;
				if (classmate_count == 0) {
					return_content += 'No students found<br><a href="javascript:request_load_main();">Back</a>';
				} else {
					return_content += '<div class="request_title">Select a Classmate</div>';

					for (var j = 0; j < classmate_count; j++) {
						var style = '';
						if(j > 0) {
							var style = ' style="border-top:none;"';
						}

						var img_url = '';
						if (classmates[j].img_url) {
							img_url = classmates[j].img_url;
						}

						var student_link = '<a href="javascript:request_select_classmate(\'' + classmates[j].id + '\', \'' + classmates[j].name + '\', \'' + classmates[j].email + '\', \''+img_url+'\');" class="request_button"'+style+'>';
						if (img_url != '') {
							student_link += '<img src="' + classmates[j].img_url + '">';
						} else {
							student_link += '<span>&nbsp;</span>';
						}
						student_link += '<div>' + classmates[j].name + '</div>';
						student_link += '</a>';
						return_content += student_link;
					}
				}
			} else {
				return_content += response.message;
			}

			document.getElementById('request_container').innerHTML = return_content;
		});
	}
};

function request_select_classmate(classmate, classmate_display, classmate_email, classmate_image) {
	request.classmate = classmate;
	request.classmate_display = classmate_display;
	request.classmate_email = classmate_email;
	request.classmate_image = classmate_image;
	
	request_load_main();
};

//****************************************************************************************************
//submit request
//****************************************************************************************************


function request_submit_request() {
	if(request.course == '') {
		alert('You must select a course before submitting a request.');
	}
	else if(request.date == '') {
		alert('You must select a date before submitting a request.');
	}
	else if(request.classmate == '') {
		alert('You must select a classmate before submitting a request.');
	}
	else {
		if(request.loading == true) {
			alert('Your request is being submitted');
		}
		else {
			//show_loading();

			var ajax = $.ajax({
				type : 'GET',
				url : baseURL + '/education/fn_api_send_record_request.php',
				data : 'api_key=' + ketchup_server.api_key + '&api_pw=' + ketchup_server.api_pw + '&student_id=' + encodeURIComponent(user.id) + '&student_email=' + encodeURIComponent(user.email) + '&student_name=' + encodeURIComponent(user.name) + '&course_id=' + encodeURIComponent(request.course) + '&course_name=' + encodeURIComponent(request.course_display) + '&date= ' + encodeURIComponent(request.date) + '&classmate_id=' + encodeURIComponent(request.classmate) + '&classmate_email=' + encodeURIComponent(request.classmate_email) + '&classmate_name=' + encodeURIComponent(request.classmate_display) + '&source=web'
			});

			ajax.fail(function(jqXHR, textStatus) {
				//hide_loading();
				alert('Error connecting to the KetchUp server. Please send your request again');
			});
			
			ajax.done(function(jqXHR, textStatus) {
				//hide_loading();
				var response = JSON.parse(ajax.responseText);
				if (response.success == true) {
					request.course = '';
					request.course_display = 'Select Course';
					request.date = '';
					request.date_display = 'Select Date';
					request.classmate = '';
					request.classmate_display = 'Select Classmate';
					request.classmate_email = '';
					request.classmate_image = 'img/person.svg';
					request.loading = false;

					request_load_main();

					alert('Your request was sent!');
				}
				else {
					alert('An error occurred. Please try again');
				}
			});
		}
	}
};
