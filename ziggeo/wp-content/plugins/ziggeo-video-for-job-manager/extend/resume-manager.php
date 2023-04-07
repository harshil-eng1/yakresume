<?php

//This file contains the functionality that extends the features to provide support for Resume Manger addon


//the above might no longer be needed...
add_filter('the_candidate_video', function($link) {

	//For cases when video was not provided
	if($link === '') {
		return;
	}

	//grab the default video player template that should be used
	$code = ziggeojobmanager_get_template('video-player');

	//Make sure the code is actually the HTML not the object code..

	//Is it a link to any Ziggeo servers
	//https://embed.ziggeo.com/v1/applications/{APP_TOKEN}}/videos/{VIDEO_TOKEN}/video.mp4
	if(stripos($link, 'ziggeo')) {
		//Lets strip everything except the token
		$link = explode('/', $link);

		//The last part should really be the "video.mp4", so we know we want the second last item
		$link = ' ziggeo-video="' . $link[count($link)-2] . '" ';
		echo '<ziggeoplayer ' . $code . $link . '></ziggeoplayer>';
	}
	else {
		echo '<ziggeoplayer ' . $code . ' ziggeo-source="' . $link . '"></ziggeoplayer>';
	}

	//Since we will show the link, it is not needed for us to return it. If we do, there will be two players
	//return $link;
});

?>