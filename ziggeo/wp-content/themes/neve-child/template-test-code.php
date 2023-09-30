<?php
/*Template Name: Test Code*/
get_header();
?>
<?php //echo do_shortcode("[ziggeovideowall pre_set_list='fb7e921b95e64df6e2b0b6e51e65da7f,b0654155bbe961065ddf0eaebb5d97e6,8ca00c62ee93adef3ddfc54b256abbbc' autoplay='true' wall_design='slide_wall']"); ?> 



<script type="text/javascript" class="runMe">
    jQuery(document).ready( function () {
        //Turns out we sometimes need a bit more time (needed for some integrations)
        setTimeout(function() {
            videowallszUIVideoWallShow('ziggeo_video_wall203495600016939829645');
        }, '2000');
    });
</script>
<div id="ziggeo_video_wall203495600016939829645" class="ziggeo_videoWall" style="display: block;">
    
    <div id="ziggeo_video_wall203495600016939829645_page_1" class="ziggeo_wallpage">
        <ziggeoplayer ziggeo-width="320" ziggeo-height="240" ziggeo-video="fb7e921b95e64df6e2b0b6e51e65da7f" ziggeo-autoplay="" undefined="" class="ba-commoncss-full-width" style="aspect-ratio: 640 / 480; width: 320px; height: 240px; display: inline-block;">
        </ziggeoplayer>

        <div class="ziggeo_videowall_slide_next" onclick="videowallszUIVideoWallPagedShowPage('ziggeo_video_wall203495600016939829645', 2);"></div>
    </div>

    <div id="ziggeo_video_wall203495600016939829645_page_2" class="ziggeo_wallpage" style="display:none;">
        <ziggeoplayer ziggeo-width="320" ziggeo-height="240" ziggeo-video="1b51a7a204ea887f21cfdb82035ec475" undefined="" class="ba-commoncss-full-width" style="aspect-ratio: 640 / 480; width: 320px; height: 240px; display: inline-block;">        
        </ziggeoplayer>
        <div class="ziggeo_videowall_slide_next" onclick="videowallszUIVideoWallPagedShowPage('ziggeo_video_wall203495600016939829645', 2);"></div>
    </div>   
    
</div>



<script type="text/javascript" class="runMe">
    videowallszCreateWall('ziggeo_video_wall203495600016939829645', {
        videos: {
            width: '320',
            height: '240',
            autoplay: true,
            autoplaytype: '',
            stretch: ''
        },
        indexing: {
            perPage: 1,
            status: '',
            design: 'slide_wall',
            fresh: true,
            auto_refresh: 0,
            pre_set_list: 'fb7e921b95e64df6e2b0b6e51e65da7f,1b51a7a204ea887f21cfdb82035ec475',
        },
        onNoVideos: {
            showTemplate: false,
            message: 'Currently no videos found. We do suggest recording some first',
            templateName: '',
            hideWall: false 
        },
        title: '<div class="ziggeo_wall_title" style="display:none"></div>',
        tags: '' //the tags to look the video by based on template setup
    });
</script>

<?php get_footer(); ?>