//
// INDEX
//	1. Settings
//		* jQuery on.ready
//		* ziggeojobmanagerHandleTagsInit
//		* ziggeojobmanagerHandleTags




/////////////////////////////////////////////////
// 1. SETTINGS
/////////////////////////////////////////////////


	jQuery(document).ready( function() {
		if(document.getElementById('ziggeojobmanager_submission_form_video_record')) {
			ziggeojobmanagerHandleTagsInit();
		}
	});

	function ziggeojobmanagerHandleTagsInit() {
		var checkboxes = document.getElementById('ziggeojobmanager_custom_tags_placeholder').getElementsByTagName('input');

		for(i = 0, l = checkboxes.length; i < l; i++) {
			checkboxes[i].addEventListener('change', function() {
				//Set events
				ziggeojobmanagerHandleTags();
			});
		}
	}

	function ziggeojobmanagerHandleTags() {
		var checkboxes = document.getElementById('ziggeojobmanager_custom_tags_placeholder').getElementsByTagName('input');
		var tags = document.getElementById('ziggeojobmanager_custom_tags');
		var tmp_tags = [];

		for(i = 0, l = checkboxes.length; i < l; i++) {
			if(checkboxes[i].checked) {
				tmp_tags.push(checkboxes[i].value);
			}
		}

		tags.value = tmp_tags.join();
	}
