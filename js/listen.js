//load on document ready
$(function() {
	$f('player', baseURL+'/education/flowplayer/flowplayer.commercial-3.2.18.swf', {
		plugins: {
			secure: {
				url: baseURL+'/education/flowplayer/flowplayer.securestreaming-3.2.9-ketchup.swf',
				timestampUrl: baseURL+'/education/fn_get_time.php'
			},
			controls: {
				height:40,
				url: baseURL+'/education/flowplayer/flowplayer.controls-3.2.16.swf',
				autoHide: false,
				fullscreen: false,
				backgroundGradient: 'none',
				backgroundColor: '#dddddd',
				
				buttonColor: '#ffffff',

				sliderColor: '#a5a5a5',
				
				timeColor: '#ffffff',
				durationColor: '#555555',

				volumeColor: '#a5a5a5',
				volumeSliderColor: '#a5a5a5'

			}
		},
		clip: {
			autoPlay: false,
			baseUrl: baseURL+'/education/recordings',
			url: default_recording,
			urlResolvers: 'secure'
		},

		//methods
		onStart: function() {
			var clip = $f('player').getClip();

			$('#row-'+recording_row+' .status').html('<img src="'+baseURL+'/education/img/listen_playing.png">');
		},
		onResume: function() {
			$('#row-'+recording_row+' .status').html('<img src="'+baseURL+'/education/img/listen_playing.png">');
		},
		onPause: function() {
			$('#row-'+recording_row+' .status').html('<img src="'+baseURL+'/education/img/listen_paused.png">');
		},
		onFinish: function() {
			$('#row-'+recording_row+' .status').html('');
		},
		onLoad: function() {
			$('#player').css('visibility', 'visible')
			$('#recordings_info').css('visibility', 'visible')
			$('#listen_table').css('visibility', 'visible')
		}
	});
});



function load_audio(file, row_id) {
	recording_row = row_id;

	$('.status').html('');
	$('#row-'+row_id+' .status').html('<img src="'+baseURL+'/education/img/loading.gif">');

	play_audio(file, row_id);
}

function play_audio(file, row_id) {
	$f('player').stop();
	var valid_file = validate_file(file);
	if(valid_file == true) {
		$f('player').setClip(file);
		$f('player').play();
	}
	else {
		alert('This file is corrupt and could not be played.')
		$('#row-'+row_id+' .status').html('');
	}
}

function validate_file(file) {
	var valid_file = false;

	var ajax = $.ajax({
		type : 'GET',
		async : false, //because we determine what to do after the response
		url : baseURL + '/education/fn_get_recording_size.php',
		data : 'media_file='+file
	});

	ajax.fail(function(jqXHR, textStatus) {
		//do nothing - array already empty
	});

	ajax.done(function(jqXHR, textStatus) {
		var size = ajax.responseText;
		if (size > 1024) {
			valid_file = true;
		}
	});

	return valid_file;
}

function row_over(row_id) {
	$('#row-'+row_id).addClass('hover_row');
}
function row_out(row_id) {
	$('#row-'+row_id).removeClass('hover_row');
}