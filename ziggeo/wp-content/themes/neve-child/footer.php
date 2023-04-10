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

                console.log(toLowerCaseVal);  

                jQuery('.fieldset-'+toLowerCaseVal+'_video').show(); 
                jQuery('.text-'+toLowerCaseVal+'_video').hide();      

                    if(jQuery('#'+toLowerCaseVal+'_videoRec').length == 0){
                        var recorederButton = '<div class="recoButton '+toLowerCaseVal+'_videoRec11">Start Recorder</div>';
                        var recorederdiv = '<div id="'+toLowerCaseVal+'_videoRec" class="hideVideo topqueset"></div>';
                        
                        jQuery('#'+toLowerCaseVal+'_video').after(recorederdiv);
                        jQuery('#'+toLowerCaseVal+'_video').after(recorederButton);
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


                        //
                        recorder.on("verified", function(){
                            //console.log(recorder.get("video"));                           

                            var videoRecUrl = 'https://video-cdn.ziggeo.com/v1/applications/' +
                                  ziggeoGetApplicationOptions().token +
                                  '/videos/' + recorder.get("video")+'/video.mp4';  

                            jQuery('#'+toLowerCaseVal+'_video').val(videoRecUrl);
                            //console.log(videoRecUrl);
                            //alert(videoRecUrl);
                        })

                        jQuery("."+toLowerCaseVal+"_videoRec11").click(function(){                            
                            jQuery("."+toLowerCaseVal+"_videoRec11").hide(); 
                            jQuery('.mylist-'+toLowerCaseVal).show();
                        });  

                        jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(0).hide();            
                        var theCount  = <?php echo rand(1,10); ?>;
                        jQuery("."+toLowerCaseVal+"_videoRec11").click(function(){                
                            jQuery("ul.mylist-"+toLowerCaseVal+" li").hide();

                            var theLength = jQuery("ul.mylist-"+toLowerCaseVal+" li").length;
                            console.log('list '+theLength);
                            if(theCount == theLength)
                            {
                                theCount = 1;
                            }
                            else
                            {
                                theCount = theCount + 1;
                            }
                            jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).show();   
                            jQuery("."+toLowerCaseVal+"_videoRec11").next().removeClass("hideVideo"); 
                            recorder.record(); 

                            setTimeout(function(){ /*recorder.record();  jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).hide(); */   }, 30000);          
                        });

                        recorder.on("rerecord", function () {
                        //Your code goes here
                        jQuery('#'+toLowerCaseVal+'_videoRec').addClass("hideVideo");
                            var theLength = jQuery("ul.mylist-"+toLowerCaseVal+" li").length;
                            if(theCount == theLength)
                            {
                                theCount = 1;
                            }
                            else
                            {
                                theCount = theCount + 1;
                            }
                             jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).show(); 
                             jQuery("."+toLowerCaseVal+"_videoRec11").next().removeClass("hideVideo"); 
                             recorder.record(); 

                              setTimeout(function(){  /*jQuery("ul.mylist-"+toLowerCaseVal+" li").slice(theCount-1,theCount).hide();*/    }, 30000);    

                        });


                    }
                    
               }
            });

        });
    </script>
<?php } ?>
</body>

</html>
