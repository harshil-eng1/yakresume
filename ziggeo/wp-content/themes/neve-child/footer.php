<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "wrapper" div and all content after.
 *
 * @package Neve
 * @since   1.0.0
 */

/**
 * Executes actions before main tag is closed.
 *
 * @since 1.0.4
 */
do_action( 'neve_before_primary_end' ); ?>

</main>
<!--/.neve-main-->

<?php

/**
 * Executes actions after main tag is closed.
 *
 * @since 1.0.4
 */
do_action( 'neve_after_primary' );

/**
 * Filters the content parts.
 *
 * @since 1.0.9
 *
 * @param bool   $status Whether the component should be displayed or not.
 * @param string $context The context name.
 */
if ( apply_filters( 'neve_filter_toggle_content_parts', true, 'footer' ) === true ) {

    /**
     * Executes actions before the footer was rendered.
     *
     * @since 1.0.0
     */
    do_action( 'neve_before_footer_hook' );

    /**
     * Executes the rendering function for the footer.
     *
     * @since 1.0.0
     */
   do_action( 'neve_do_footer' );

    /**
     * Executes actions after the footer was rendered.
     *
     * @since 1.0.0
     */
    do_action( 'neve_after_footer_hook' );
}
?>

</div>
<!--/.wrapper-->
<?php

wp_footer();

/**
 * Executes actions before the body tag is closed.
 *
 * @since 2.11
 */
do_action( 'neve_body_end_before' );
?>
<script>

    jQuery("footer .hfg_footer #cb-row--footer-bottom .item--inner .component-wrap").html("<div><p>© Copyright 2023 Yakresume.</p></div>")
    let recentRecordingId = '';
    let processingVideo=processedVideo=0;

    jQuery('#custom_professional_title').select2()
    function htmlEntities(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/ /g, '&nbsp;')  // Replace space with &nbsp;
        .replace(/\n/g, '<br>'); // Replace line break with <br>
    }
    function showPreviewBtn() {
        if(processingVideo==processedVideo){
            jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'submit').removeClass('butGrayAdd').val('Preview →').show();
        }
            
        //});
    }
    jQuery(document).ready(function($) {
        if($('#ziggeojobmanager_recorder').length){
        var element = document.getElementById('ziggeojobmanager_recorder');
        var embedding = ZiggeoApi.V2.Recorder.findByElement(element);

        embedding.on("recording", function() {
            recentRecordingId = 'ziggeojobmanager_recorder';
            //$("#submit-resume-form [name='submit_resume']").attr("type", 'button').hide();
            $("#submit-resume-form [name='submit_resume']").attr("type", 'button').val('Video Still Processing…').addClass('butGrayAdd');
            // $("#submit-resume-form [name='submit_resume']").addClass('butGrayAdd');
            processingVideo++
            console.log(processingVideo);
        });

        embedding.on("processed", function() {
            processedVideo++
            setTimeout(() => {
                showPreviewBtn()
            }, 500);

        });
        }

    
        /*jQuery(document).on("click",".resume-title a:first",function(e){
            e.preventDefault()
            $(".resume-title .candidate-dashboard-actions li:first a").data
        })*/
        

    })
</script>
<?php
if(get_the_ID() == 64){ 
?>
<style type="text/css">
.hideVideo {
    display: none !important;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery('fieldset[class*="_video"]').hide();
    jQuery('.fieldset-candidate_video').show();


    /////// Privew case video show edit page
    onResumeEditPage()
    function onResumeEditPage(){
        var languages = jQuery("#resume_languages").val();
        setTimeout(() => {
            //jQuery("#candidate_video").show()
            var candidate_video = jQuery("#candidate_video").val();
            var parts = candidate_video.split('/');
            var videoToken = parts[parts.length - 2];

            if(videoToken!='' && videoToken!= undefined){
                jQuery('.fieldset-candidate_video .field .ba-videorecorder-chooser-button-0 span').text("Redo")
                jQuery(".fieldset-candidate_video .field").prepend('<ziggeoplayer ziggeo-video="'+videoToken+'" ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>')
                //console.log(videoToken)
            }




        }, 500);      

        // console.log(languages);
        for (var i = 0; i < languages.length; i++) {
            var toLowerCaseVal = languages[i].toLowerCase();
            var getQuestion = jQuery("#_"+toLowerCaseVal+"_skill_question").val();
            jQuery('.fieldset-' + toLowerCaseVal + '_video').addClass('topQuestion');
            jQuery('.fieldset-' + toLowerCaseVal + '_video').show();
            //jQuery('.text-' + toLowerCaseVal + '_video').hide();

            var lang_video_old = jQuery("#"+ toLowerCaseVal +"_video").val();
            var langVideoSplit = lang_video_old.split('/');
            var langVideoToken = langVideoSplit[langVideoSplit.length - 2];  

            if(langVideoToken!='' && langVideoToken!= undefined){
                jQuery("#"+ toLowerCaseVal +"_video").hide()
                jQuery('<ziggeoplayer ziggeo-video="'+langVideoToken+'" ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>').insertBefore("#"+ toLowerCaseVal +"_video")  
              }
                       
              

            if (jQuery('#' + toLowerCaseVal + '_videoRec').length == 0) {

                var recoredButText = '<div class="recoButTxt ' + toLowerCaseVal +
                    '_butTxt">A question will appear on the video based on the skill selected. Please press Start Recording</div>';
                var recorederButton = '<div class="recoButton ' + toLowerCaseVal +
                    '_videoRec11" data-lang="' + toLowerCaseVal + '">Redo</div>';
                var recorederdiv = '<div id="' + toLowerCaseVal +
                    '_videoRec" class="topMagClas hideVideo topqueset"></div>';

                jQuery('#' + toLowerCaseVal + '_video').before(recoredButText);
                jQuery('#' + toLowerCaseVal + '_video').after(recorederdiv);
                jQuery('#' + toLowerCaseVal + '_video').after(recorederButton);

                jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();

            }
        
        }
    }    

    jQuery('body').on('change', '#resume_languages', function() {

        // console.log(jQuery(this).val());
        // console.log(jQuery(this).val().length);

        var languages = jQuery(this).val();

        jQuery('fieldset[class*="_video"]').hide();
        jQuery('.fieldset-candidate_video').show();

        for (var i = 0; i < languages.length; i++) {

            var toLowerCaseVal = languages[i].toLowerCase();

            jQuery('.fieldset-' + toLowerCaseVal + '_video').addClass('topQuestion');

            //console.log(toLowerCaseVal);  

            jQuery('.fieldset-' + toLowerCaseVal + '_video').show();
            jQuery('.fieldset-' + toLowerCaseVal + '_video').show();
           
            jQuery('.text-' + toLowerCaseVal + '_video').hide();

            if (jQuery('#' + toLowerCaseVal + '_videoRec').length == 0) {

                var recoredButText = '<div class="recoButTxt ' + toLowerCaseVal +
                    '_butTxt">A question will appear on the video based on the skill selected. Please press Start Recording</div>';
                var recorederButton = '<div class="recoButton ' + toLowerCaseVal +
                    '_videoRec11" data-lang="' + toLowerCaseVal + '">Start Recorder</div>';
                var recorederdiv = '<div id="' + toLowerCaseVal +
                    '_videoRec" class="topMagClas hideVideo topqueset"></div>';

                jQuery('#' + toLowerCaseVal + '_video').before(recoredButText);
                jQuery('#' + toLowerCaseVal + '_video').after(recorederdiv);
                jQuery('#' + toLowerCaseVal + '_video').after(recorederButton);

                jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();

            }

        }
    });

    /********* On click Function ********/
    jQuery("body").on('click', '.recoButton', function() {
        var toLowerCaseVal = jQuery(this).attr('data-lang');
        //console.log('ssss '+toLowerCaseVal);

        jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();

           
        if(!jQuery(this).closest(".field").hasClass('inner-video-field')){
            jQuery(this).closest(".field").addClass('inner-video-field')
        }
        recentRecordingId = toLowerCaseVal + "_videoRec";

        var recorder = new ZiggeoApi.V2.Recorder({
            element: document.getElementById(toLowerCaseVal + "_videoRec"),
            attrs: {
                theme: "modern",
                themecolor: "red",
                timelimit: "30",
                allowscreen: true,
                allowupload: false,
                allowscreen: false,
                countdown: 30,
                flipscreen : false,
                picksnashots : true,
            }
        });
        recorder.activate();

        var recorder = ZiggeoApi.V2.Recorder.findByElement(jQuery('#' + toLowerCaseVal + '_videoRec'));

        recorder.on("verified", function() {
            //console.log(recorder.get("video"));
            var videoRecUrl = 'https://video-cdn.ziggeo.com/v1/applications/' +
                ziggeoGetApplicationOptions().token +
                '/videos/' + recorder.get("video") + '/video.mp4';

            jQuery('#' + toLowerCaseVal + '_video').val(videoRecUrl);
            //console.log(videoRecUrl);
            //alert(videoRecUrl);

            /** Question addd hidden filed**/
            var res = jQuery('.text-' + toLowerCaseVal + '_video-description .mylist-' +
                toLowerCaseVal + ' li[style=""]').attr('id');
            console.log(res)
            jQuery('#' + toLowerCaseVal + '_skill_question').val(res);


        });

        // Resume Editing Time Hide the preview button on the form submit when video processing
        recorder.on("recording", function() {
            console.log('recording startedddd');
            //jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'button').hide();
            //jQuery("#submit-resume-form [name='submit_resume']").val('Video Still Processing…');
            jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'button').val('Video Still Processing…').addClass('butGrayAdd');
            //jQuery("#submit-resume-form [name='submit_resume']").addClass('butGrayAdd');
            processingVideo++
            
        });

        recorder.on("processed", function() {
            console.log('processedddd');
            processedVideo++
            setTimeout(() => {       
                showPreviewBtn()         
              //  jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'submit').removeClass('butGrayAdd').val('Preview →').show();                
            }, 500);
        });


        jQuery("." + toLowerCaseVal + "_butTxt").hide();
        jQuery("." + toLowerCaseVal + "_videoRec11").hide();

        var quesLength = jQuery("ul.mylist-" + toLowerCaseVal + " li").length;

        var theCount = Math.floor((Math.random() * quesLength) + 1);
        //console.log(theCount+'aaaaaaMain')

        recorder.record();

        jQuery('#' + toLowerCaseVal + '_videoRec').addClass("hideVideo");
        // var theLength = jQuery("ul.mylist-"+toLowerCaseVal+" li").length;
        /*if(theCount == theLength){
            theCount = 1;
        }else{
            theCount = theCount + 1;
        }*/
        //jQuery("ul.mylist-"+toLowerCaseVal+" li").show();
        jQuery("ul.mylist-" + toLowerCaseVal + " li:nth-child(" + theCount + ")").show();

        jQuery("." + toLowerCaseVal + "_videoRec11").next().removeClass("hideVideo");

        recorder.on("rerecord", function() {

            var theCount1 = getRandomNumber(quesLength);
            // console.log(theCount+'aaaaaa');

            jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();


            //var theCount  = Math.floor((Math.random() * quesLength) + 1);
            recorder.record();
            jQuery('#' + toLowerCaseVal + '_videoRec').addClass("hideVideo");
            jQuery("ul.mylist-" + toLowerCaseVal + " li:nth-child(" + theCount1 + ")").show();
            jQuery("." + toLowerCaseVal + "_videoRec11").next().removeClass("hideVideo");

            //console.log('asdfsdf');
            //Your code goes here
            // setTimeout(function(){  /*jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).hide();*/    }, 5000);

        });
    });




});

function getRandomNumber(quesLength) {
    var checkv = localStorage.getItem('lastValue');
    console.log(checkv + 'checkv');
    var random = Math.floor((Math.random() * quesLength) + 1);
    console.log(random + 'random');
    if (random === checkv) {
        getRandomNumber(quesLength);
    } else if (typeof random === "undefined") {
        getRandomNumber(quesLength);
    } else {
        localStorage.setItem('lastValue', random);
        return random;
    }
}
</script>
<?php }else if(get_the_ID() == 65){ ?>
    <style>
    .topQuestion .field small.description{
        margin-top: 95px;
    }
    .page-id-65 .topQuestion small.description{
        position: unset;

    }

    .topMagClas{
        margin-top : 0px;
    }
    .topQuestion .field small.description{
        margin-top: 0px;
    }
    
    </style>
    <?php
    $resumeId=$_GET['resume_id'];
    $language=get_post_meta( $resumeId, '_resume_languages',true);
    $getCustomProfessionalTitle=json_encode(get_post_meta( $resumeId, '_custom_professional_title',true));
    //$getCustomProfessionalTitle;

    // echo '<ziggeoplayer ziggeo-video="cc0e885aa20445f92ce134373e51d713" ziggeo-width=320 ziggeo-height=180 ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>'; 

    foreach($language as $lang){
        $key = '_'.strtolower($lang).'_skill_question';
        $question =get_post_meta( $resumeId, $key,true);
        echo '<input type="hidden" id="'.$key.'" value="'.$question.'" class="aaaaaaaaaa">';
    }
    ?>

    <script>
    jQuery(document).ready(function() {
        let getCustomProfessionalTitle = '<?= $getCustomProfessionalTitle?>'

        let getCustomTitleArr = JSON.parse(getCustomProfessionalTitle);
        
        jQuery('fieldset[class*="_video"]').hide();
        jQuery('.fieldset-candidate_video').show();
        jQuery('#custom_professional_title').val(getCustomTitleArr).trigger('change');

        onResumeEditPage()
        function onResumeEditPage(){
            var languages = jQuery("#resume_languages").val();
            setTimeout(() => {
                //jQuery("#candidate_video").show()
                var candidate_video = jQuery("#candidate_video").val();
                var parts = candidate_video.split('/');
                var videoToken = parts[parts.length - 2];

                jQuery('.fieldset-candidate_video .field .ba-videorecorder-chooser-button-0 span').text("Redo")
                jQuery(".fieldset-candidate_video .field").prepend('<ziggeoplayer ziggeo-video="'+videoToken+'" ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>')
                //console.log(videoToken)
            }, 500);
           

            console.log(languages);
            for (var i = 0; i < languages.length; i++) {
                var toLowerCaseVal = languages[i].toLowerCase();

                var getQuestion = jQuery("#_"+toLowerCaseVal+"_skill_question").val();
                jQuery('.fieldset-' + toLowerCaseVal + '_video').addClass('topQuestion');
                jQuery('.fieldset-' + toLowerCaseVal + '_video').show();
                //jQuery('.text-' + toLowerCaseVal + '_video').hide();
                //console.log(getQuestion)
                var lang_video_old = jQuery("#"+ toLowerCaseVal +"_video").val();
                var langVideoSplit = lang_video_old.split('/');
                var langVideoToken = langVideoSplit[langVideoSplit.length - 2];    
                jQuery("#"+ toLowerCaseVal +"_video").hide()

                var getQuestionHtml = jQuery("#"+getQuestion).html()
                console.log(getQuestionHtml);

                jQuery('<div class="preview-old-question-video"><small class="old-preview-question">'+getQuestionHtml+'</small><ziggeoplayer class="preview-old-video" ziggeo-video="'+langVideoToken+'" ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer></div>').insertBefore("#"+ toLowerCaseVal +"_video")
                 
                // console.log(langVideoToken);      
                  console.log('#' + toLowerCaseVal + '_videoRec');  
                  
                  
                
                  setTimeout(() => {
                    var questionHtml=jQuery(".text-"+toLowerCaseVal+"_video-description").clone()
                     jQuery(".text-"+toLowerCaseVal+"_video-description").remove()
                    jQuery(questionHtml).insertBefore("#"+ toLowerCaseVal +"_videoRec")
                  }, 1500);
                 // console.log(questionHtml)
                 // jQuery("#"+ toLowerCaseVal +"_videoRec").prepend(questionHtml)

                if (jQuery('#' + toLowerCaseVal + '_videoRec').length == 0) {

                    var recoredButText = '<div class="recoButTxt ' + toLowerCaseVal +
                        '_butTxt">A question will appear on the video based on the skill selected. Please press Start Recording</div>';
                    var recorederButton = '<div class="recoButton ' + toLowerCaseVal +
                        '_videoRec11" data-lang="' + toLowerCaseVal + '">Redo</div>';
                    var recorederdiv = '<div id="' + toLowerCaseVal +
                        '_videoRec" class="topMagClas hideVideo topqueset"></div>';

                    jQuery('#' + toLowerCaseVal + '_video').before(recoredButText);
                    jQuery('#' + toLowerCaseVal + '_video').after(recorederdiv);
                    jQuery('#' + toLowerCaseVal + '_video').after(recorederButton);

                    jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();

                }
            
            }
        }

        jQuery('body').on('change', '#resume_languages', function() {

            // console.log(jQuery(this).val());
            // console.log(jQuery(this).val().length);

            var languages = jQuery(this).val();

            jQuery('fieldset[class*="_video"]').hide();
            jQuery('.fieldset-candidate_video').show();

            for (var i = 0; i < languages.length; i++) {

                var toLowerCaseVal = languages[i].toLowerCase();

                jQuery('.fieldset-' + toLowerCaseVal + '_video').addClass('topQuestion');

                //console.log(toLowerCaseVal);  

                jQuery('.fieldset-' + toLowerCaseVal + '_video').show();
                jQuery('.text-' + toLowerCaseVal + '_video').hide();

                setTimeout(() => {
                    var questionHtml=jQuery(".text-"+toLowerCaseVal+"_video-description").clone()
                    jQuery(".text-"+toLowerCaseVal+"_video-description").remove()
                    jQuery(questionHtml).insertBefore("#"+ toLowerCaseVal +"_videoRec")
                }, 500);
              

                if (jQuery('#' + toLowerCaseVal + '_videoRec').length == 0) {

                    var recoredButText = '<div class="recoButTxt ' + toLowerCaseVal +
                        '_butTxt">A question will appear on the video based on the skill selected. Please press Start Recording</div>';
                    var recorederButton = '<div class="recoButton ' + toLowerCaseVal +
                        '_videoRec11" data-lang="' + toLowerCaseVal + '">Start Recorder</div>';
                    var recorederdiv = '<div id="' + toLowerCaseVal +
                        '_videoRec" class="topMagClas hideVideo topqueset"></div>';

                    jQuery('#' + toLowerCaseVal + '_video').before(recoredButText);
                    jQuery('#' + toLowerCaseVal + '_video').after(recorederdiv);
                    jQuery('#' + toLowerCaseVal + '_video').after(recorederButton);

                    jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();

                }

            }
        });

        /********* On click Function ********/
        jQuery("body").on('click', '.recoButton', function() {

            console.log("recoding stated");
            var toLowerCaseVal = jQuery(this).attr('data-lang');
            jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();
             if(!jQuery(this).closest(".field").hasClass('inner-video-field')){
                jQuery(this).closest(".field").addClass('inner-video-field')
            }
            recentRecordingId = toLowerCaseVal + "_videoRec";

            var recorder = new ZiggeoApi.V2.Recorder({
                element: document.getElementById(toLowerCaseVal + "_videoRec"),
                attrs: {
                    theme: "modern",
                    themecolor: "red",
                    timelimit: "30",
                    allowscreen: true,
                    allowupload: false,
                    allowscreen: false,
                    countdown: 30,
                    flipscreen : false,
                    picksnashots : true,
                }
            });
            recorder.activate();

            var recorder = ZiggeoApi.V2.Recorder.findByElement(jQuery('#' + toLowerCaseVal + '_videoRec'));

            recorder.on("verified", function() {
                //console.log(recorder.get("video"));
                var videoRecUrl = 'https://video-cdn.ziggeo.com/v1/applications/' +
                    ziggeoGetApplicationOptions().token +
                    '/videos/' + recorder.get("video") + '/video.mp4';

                jQuery('#' + toLowerCaseVal + '_video').val(videoRecUrl);
                //console.log(videoRecUrl);
                //alert(videoRecUrl);

                /** Question addd hidden filed**/
                var res = jQuery('.text-' + toLowerCaseVal + '_video-description .mylist-' +
                    toLowerCaseVal + ' li[style=""]').attr('id');
                console.log(res)
                jQuery('#' + toLowerCaseVal + '_skill_question').val(res);


            });

            // Resume Add time Hide the Save Changes button on the form submit when video processing
            recorder.on("recording", function() {
                console.log('recording started111');
                //jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'button').hide();
                jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'button').val('Video Still Processing…').addClass('butGrayAdd');
                processingVideo++
            });

            recorder.on("processed", function() {
                processedVideo++
                setTimeout(() => {
                    //jQuery("#submit-resume-form [name='submit_resume']").attr("type",'submit').show();
                    showPreviewBtn()
                  //  jQuery("#submit-resume-form [name='submit_resume']").attr("type", 'submit').removeClass('butGrayAdd').val('Save changes').show();  
                }, 500);
            });


            jQuery("." + toLowerCaseVal + "_butTxt").hide();
            jQuery("." + toLowerCaseVal + "_videoRec11").hide();

            var quesLength = jQuery("ul.mylist-" + toLowerCaseVal + " li").length;

            var theCount = Math.floor((Math.random() * quesLength) + 1);
            //console.log(theCount+'aaaaaaMain')

            recorder.record();

            jQuery('#' + toLowerCaseVal + '_videoRec').addClass("hideVideo");
            // var theLength = jQuery("ul.mylist-"+toLowerCaseVal+" li").length;
            /*if(theCount == theLength){
                theCount = 1;
            }else{
                theCount = theCount + 1;
            }*/
            //jQuery("ul.mylist-"+toLowerCaseVal+" li").show();

            // var getQuestion = jQuery("#_"+toLowerCaseVal+"_skill_question").val();
            // if(getQuestion!='' && getQuestion != undefined){
            //     jQuery("ul.mylist-" + toLowerCaseVal).html('<li>'+getQuestion+'</li>');
            // }else{
            //     jQuery("ul.mylist-" + toLowerCaseVal + " li:nth-child(" + theCount + ")").show();
            // }
            jQuery("ul.mylist-" + toLowerCaseVal + " li:nth-child(" + theCount + ")").show();

            jQuery("." + toLowerCaseVal + "_videoRec11").next().removeClass("hideVideo");

            recorder.on("rerecord", function() {

                var theCount1 = getRandomNumber(quesLength);
                // console.log(theCount+'aaaaaa');

                jQuery("ul.mylist-" + toLowerCaseVal + " li").hide();


                //var theCount  = Math.floor((Math.random() * quesLength) + 1);
                recorder.record();
                jQuery('#' + toLowerCaseVal + '_videoRec').addClass("hideVideo");
                jQuery("ul.mylist-" + toLowerCaseVal + " li:nth-child(" + theCount1 + ")").show();
                jQuery("." + toLowerCaseVal + "_videoRec11").next().removeClass("hideVideo");

                //console.log('asdfsdf');
                //Your code goes here
                // setTimeout(function(){  /*jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).hide();*/    }, 5000);

            });
        });
    });

    function getRandomNumber(quesLength) {
        var checkv = localStorage.getItem('lastValue');
        console.log(checkv + 'checkv');
        var random = Math.floor((Math.random() * quesLength) + 1);
        console.log(random + 'random');
        if (random === checkv) {
            getRandomNumber(quesLength);
        } else if (typeof random === "undefined") {
            getRandomNumber(quesLength);
        } else {
            localStorage.setItem('lastValue', random);
            return random;
        }
    }
    </script>

<?php } ?>


<!-- Hide the preview button on the form submit when video processing  -->
<script>

</script>
</body>

</html>