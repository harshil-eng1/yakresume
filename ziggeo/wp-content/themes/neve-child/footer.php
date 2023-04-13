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

</main><!--/.neve-main-->

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

</div><!--/.wrapper-->
<?php

wp_footer();

/**
 * Executes actions before the body tag is closed.
 *
 * @since 2.11
 */
do_action( 'neve_body_end_before' );

if(get_the_ID() == 64){ 
?> 
<style type="text/css">.hideVideo{display: none !important;}</style>
    <script type="text/javascript">
        jQuery(document).ready(function(){

            jQuery('fieldset[class*="_video"]').hide();
            jQuery('.fieldset-candidate_video').show();
            
            jQuery('body').on('change', '#resume_languages', function(){                

                //console.log(jQuery(this).val());
                //console.log(jQuery(this).val().length);

                var languages = jQuery(this).val();

                jQuery('fieldset[class*="_video"]').hide();
                jQuery('.fieldset-candidate_video').show();
               
               for (var i=0; i < languages.length; i++) {                                   

                var toLowerCaseVal = languages[i].toLowerCase();

                jQuery('.fieldset-'+toLowerCaseVal+'_video').addClass('topQuestion');

                //console.log(toLowerCaseVal);  

                jQuery('.fieldset-'+toLowerCaseVal+'_video').show(); 
                jQuery('.text-'+toLowerCaseVal+'_video').hide();      

                    if(jQuery('#'+toLowerCaseVal+'_videoRec').length == 0){

                        var recoredButText = '<div class="recoButTxt '+toLowerCaseVal+'_butTxt">A question will appear on the video based on the skill selected. Please press Start Recording</div>';
                        var recorederButton = '<div class="recoButton '+toLowerCaseVal+'_videoRec11" data-lang="'+toLowerCaseVal+'">Start Recorder</div>';
                        var recorederdiv = '<div id="'+toLowerCaseVal+'_videoRec" class="topMagClas hideVideo topqueset"></div>';
                        
                        jQuery('#'+toLowerCaseVal+'_video').before(recoredButText);
                        jQuery('#'+toLowerCaseVal+'_video').after(recorederdiv);
                        jQuery('#'+toLowerCaseVal+'_video').after(recorederButton);

                        jQuery("ul.mylist-"+toLowerCaseVal+" li").hide(); 

                    }
                    
               }
            });
      
            /********* On click Function ********/
            jQuery("body").on('click', '.recoButton', function(){
                var toLowerCaseVal = jQuery(this).attr('data-lang');
                //console.log('ssss '+toLowerCaseVal);

                jQuery("ul.mylist-"+toLowerCaseVal+" li").hide(); 

                var recorder = new ZiggeoApi.V2.Recorder({
                    element: document.getElementById(toLowerCaseVal+"_videoRec"),
                    attrs: {
                        theme: "modern",
                        themecolor: "red",
                        timelimit:"30",
                        allowscreen: true,
                        allowupload : false,
                        allowscreen:false,
                        countdown : 30,
                    }
                });
                recorder.activate();                

                var recorder = ZiggeoApi.V2.Recorder.findByElement(jQuery('#'+toLowerCaseVal+'_videoRec'));
                
                recorder.on("verified", function(){
                    //console.log(recorder.get("video"));
                    var videoRecUrl = 'https://video-cdn.ziggeo.com/v1/applications/' +
                          ziggeoGetApplicationOptions().token +
                          '/videos/' + recorder.get("video")+'/video.mp4';  

                    jQuery('#'+toLowerCaseVal+'_video').val(videoRecUrl);
                    //console.log(videoRecUrl);
                    //alert(videoRecUrl);
                });             

                jQuery("."+toLowerCaseVal+"_butTxt").hide(); 
                jQuery("."+toLowerCaseVal+"_videoRec11").hide(); 

                var quesLength = jQuery("ul.mylist-"+toLowerCaseVal+" li").length;                
                           
                var theCount  = Math.floor((Math.random() * quesLength) + 1);
                
                recorder.record(); 

                jQuery('#'+toLowerCaseVal+'_videoRec').addClass("hideVideo");
                var theLength = jQuery("ul.mylist-"+toLowerCaseVal+" li").length;
                /*if(theCount == theLength){
                    theCount = 1;
                }else{
                    theCount = theCount + 1;
                }*/
                //jQuery("ul.mylist-"+toLowerCaseVal+" li").show();
                jQuery("ul.mylist-"+toLowerCaseVal+" li:nth-child("+theCount+")").show(); 
                
                jQuery("."+toLowerCaseVal+"_videoRec11").next().removeClass("hideVideo"); 
                
                recorder.on("rerecord", function () {
                    //console.log('asdfsdf');
                    //Your code goes here
                    setTimeout(function(){  /*jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).hide();*/    }, 5000);    

                });
            });

        });
    </script>
<?php } ?>
</body>

</html>
