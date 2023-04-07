//
// INDEX
//	1. Job Manager
//		1.1. jQuery on.ready
//		1.2. ziggeojobmanagerUIFormRecorder()
//	2. Extension: Resume Manager
//		3.1. jQuery on.ready
//		2.1. ziggeojobmanagerUIResumeFormInit()
//	3. Admin
//		3.1. jQuery on.ready
//		3.1. ziggeojobmanagerShowVideoPreviewInit()
//	4. Global
//		4.1. ziggeojobmanagerUIOnVerified()




/////////////////////////////////////////////////
// 1. JOB MANAGER
/////////////////////////////////////////////////

	jQuery(document).ready( function() {
		ziggeojobmanagerUIHelperInit();
	});

	function ziggeojobmanagerUIHelperInit() {
		if(typeof ZiggeoApi !== 'undefined') {
			ziggeojobmanagerUIFormRecorder(
				document.querySelector('#submit-job-form #company_video'),
				'company_video',
				ZiggeoWP.jobmanager.show_recorder,
				ZiggeoWP.jobmanager.show_uploader,
				ZiggeoWP.jobmanager.show_combined,
				false
			);
		}
		else {
			setTimeout(function() {
				ziggeojobmanagerUIHelperInit();
			}, 200);
		}
	}

	function ziggeojobmanagerUIFormRecorder(video_field, field_id, show_recorder, show_uploader, combine_fields, hide_link_field) {

		if(video_field) {

			if(hide_link_field === true) {
				video_field.style.display = 'none';
			}

			if(combine_fields === true) {
				var recorder_button = document.createElement('span');
				recorder_button.id = 'ziggeojobmanager_combined';

				video_field.parentElement.appendChild(recorder_button);

				var recorder = new ZiggeoApi.V2.Recorder({
					element: recorder_button,
					attrs: {
						width: '100%',
						allowupload: true,
						theme: "modern",
						themecolor: "red"
					}
				});

				recorder.on('verified', function() {
					ziggeojobmanagerUIOnVerified(recorder, field_id);
				});

				recorder.activate();
			}
			else {
				if(show_recorder === true) {
					var recorder_button = document.createElement('span');
					recorder_button.id = 'ziggeojobmanager_recorder';

					//Setup the class
					recorder_button.className = 'ziggeojobmanager_button';

					if(show_uploader === false) {
						recorder_button.className += ' wide';
					}

					if(ZiggeoWP.jobmanager.design === 'icons') {
						recorder_button.className += ' icons';
					}
					else if(ZiggeoWP.jobmanager.design === 'buttons') {
						recorder_button.className += ' noicons';
					}

					video_field.parentElement.appendChild(recorder_button);

					var recorder = new ZiggeoApi.V2.Recorder({
						element: recorder_button,
						attrs: {
							width: '100%',
							allowupload: false,
							theme: "modern",
							themecolor: "red"
						}
					});

					recorder.on('verified', function() {
						ziggeojobmanagerUIOnVerified(recorder, field_id);
					});

					recorder.activate();
				}

				if(show_uploader === true) {
					var upload_button = document.createElement('span');
					upload_button.id = 'zigggeojobmanager_uploader';

					//Setup classes
					upload_button.className = 'ziggeojobmanager_button';

					if(show_recorder === false) {
						upload_button.className += ' wide';
					}

					if(ZiggeoWP.jobmanager.design === 'icons') {
						upload_button.className += ' icons';
					}
					else if(ZiggeoWP.jobmanager.design === 'buttons') {
						upload_button.className += ' noicons';
					}

					video_field.parentElement.appendChild(upload_button);

					var uploader = new ZiggeoApi.V2.Recorder({
						element: upload_button,
						attrs: {
							width: '100%',
							allowrecord: false,
							theme: "modern",
							themecolor: "red"
						}
					});

					uploader.on('verified', function() {
						ziggeojobmanagerUIOnVerified(uploader, field_id);
					});

					uploader.activate();
				}
			}

			return true;
		}

		return false;
	}




/////////////////////////////////////////////////
// EXTENSION: RESUME MANAGER
/////////////////////////////////////////////////

	jQuery(document).ready( function() {
		ziggeojobmanagerUIResumeFormInit();
	});

	//Resume submission form
	function ziggeojobmanagerUIResumeFormInit(video_field) {
		if(typeof ZiggeoApi !== 'undefined') {
			var video_field = document.querySelector('#submit-resume-form #candidate_video');

			if(video_field) {
					return ziggeojobmanagerUIFormRecorder(
						video_field,
						'candidate_video',
						ZiggeoWP.jobmanager.addons.resume_manager.show_recorder,
						ZiggeoWP.jobmanager.addons.resume_manager.show_uploader,
						ZiggeoWP.jobmanager.addons.resume_manager.show_combined,
						ZiggeoWP.jobmanager.addons.resume_manager.hide_link_field
					);
			}

			return false;
		}
		else {
			setTimeout(function() {
				ziggeojobmanagerUIResumeFormInit();
			}, 200);

			return false;
		}
	}




/////////////////////////////////////////////////
// 3. ADMIN
/////////////////////////////////////////////////

	jQuery(document).ready( function() {
		ziggeojobmanagerShowVideoPreviewInit();
	});

	function ziggeojobmanagerShowVideoPreviewInit() {
		if(typeof ZiggeoApi !== 'undefined') {
			var video_field = document.querySelector('#resume_data.postbox #_candidate_video');

			if(video_field) {
				var _preview = document.createElement('div');
				_preview.id = 'ziggeojobmanager_preview';
				_preview.className = 'button';
				_preview.innerHTML = 'View';

				_preview.addEventListener('click', function() {
					//show a popup player
					ziggeoShowOverlayWithPlayer(null, document.getElementById('_candidate_video').value);
				});

				video_field.parentElement.appendChild(_preview);
			}
		}
		else {
			setTimeout(function() {
				ziggeojobmanagerShowVideoPreviewInit();
			}, 200);
		}
	}




/////////////////////////////////////////////////
// 4. GLOBAL
/////////////////////////////////////////////////

	function ziggeojobmanagerUIOnVerified(embedding_obj, field_to_id) {

		var field = document.getElementById(field_to_id);

		//Save the token into the field
		field.value = 'https://' + embedding_obj.get('video_data.embed_video_url') + '.mp4';

		//Add additional tags to the video
		var tags = ZiggeoWP.jobmanager.custom_tags;

		if(tags) {

			var _tags = [];
			tags = tags.split(',');

			for(i = 0, c = tags.length; i < c; i++) {
				try {
					var value = document.getElementById(tags[i]).value;

					if(value.trim() !== '') {
						_tags.push(value);
					}
				}
				catch(err) {
					console.log(err);
				}
			}

			if(_tags.length > 0) {

				if(embedding_obj.get('tags') !== '' && embedding_obj.get('tags') !== null) {
					_tags.concat(embedding_obj.get('tags'));
				}

				//Create tags for the video
				ziggeo_app.videos.update(data.embedding_object.get("video"), { tags: _tags });
			}

		}
	}
