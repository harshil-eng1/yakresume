<?php
/*Template Name: View All Resume Candidate Test*/
get_header();
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<!-- Owl Stylesheets -->
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.theme.default.min.css">
<style>
    .inner-prev-btn.disabled,
    .prev-btn.disabled,
    .inner-next-btn.disabled,
    .next-btn.disabled{
        display:none !important;
    }
    
</style>

<!-- Bootstrap JS -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/popper.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>
<!-- javascript -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/owl.carousel.js"></script>

<script type="text/javascript">
    function gotopage(selval){
        var value = selval.options[selval.selectedIndex].value;
        window.location.href=value;
    }  
    /************/
    function clearFilters(){ 
        //onsole.log('ddfgfg');
        var clearUrl = jQuery('.clearbut').attr('data-clearurl');   
        console.log(clearUrl);
        window.location.href = clearUrl;
    }
</script>
<?php 
$metaquery = array();
$jobPostId = '';
if(isset($_GET['jobId'])){
    $jobPostId = $_GET['jobId'];
    $skillLang = get_post_meta( $jobPostId, '_skill_language', true );

    $metaquery['relation'] = 'OR';
    foreach($skillLang as $key => $skill){
        $metaquery[$key]['key'] = '_resume_languages';
        $metaquery[$key]['value'] = $skill;
        $metaquery[$key]['compare'] = 'LIKE';
    }   
}/*else{
    $skillLang = get_option('_transient_jmfe_fields_custom')['job']['skill_language']['options'];
}*/
/*echo "<pre>";
print_r(get_option('_transient_jmfe_fields_custom')['job']['skill_language']['options']);echo "</pre>";*/
?>
<div class="single-resume-content">
    <!-- rateCandidates Section -->
    <section id="rateCandidate">
        <div class="container">
            <div class="row">
                <div class="leftsidefilt col-2">
                    <form method="get">
                        <div class="filterRatingRe">
                            <h3>Filter Rating</h3>
                            <?php //if(isset($_GET['postseen'])){ $postseen = '&postseen='.$_GET['postseen'] ; } ?>
                            <?php //if(isset($_GET['rating'])){ $rating = '&rating='.$_GET['rating'] ; } ?>
                            <?php //if(isset($_GET['skills'])){ $skills = '&skills='.$_GET['skills'] ; } ?>
                             <input type="hidden" name="jobId" value="<?php echo $jobPostId; ?>">   

                            <select class="selRatFilt" name="rating">
                                <option value="">All</option>
                                <option class="resfiltCandid" data-rate_candidate="Notrated" value="no_ratting" <?= $_GET['rating'] == 'no_ratting'?'selected':'' ?>>Not rated</option>
                                <option class="resfiltCandid" data-rate_candidate="1" value="1" <?php if($_GET['rating'] == 1){ ?>selected <?php } ?>>1</option>
                                <option class="resfiltCandid" data-rate_candidate="2" value="2" <?php if($_GET['rating'] == 2){ ?>selected <?php } ?>>2</option>
                                <option class="resfiltCandid" data-rate_candidate="3" value="3" <?php if($_GET['rating'] == 3){ ?>selected <?php } ?>>3</option>
                                <option class="resfiltCandid" data-rate_candidate="4" value="4" <?php if($_GET['rating'] == 4){ ?>selected <?php } ?>>4</option>
                                <option class="resfiltCandid" data-rate_candidate="5" value="5" <?php if($_GET['rating'] == 5){ ?>selected <?php } ?>>5</option>
                            </select> 
                        </div>

                        <div class="filterRatingVideo">
                            <h3>Filter Seen Video</h3> 
                            <select class="selSeenVidFilt" name="postseen">
                                <option value="">All</option>
                                <option class="seenVideo" data-video_seen="seen" value="seen" <?php if($_GET['postseen'] == 'seen'){ ?>selected <?php } ?>>Seen Video</option>
                                <option class="unSeenVideo" data-video_unseen="unseen" value="unseen" <?php if($_GET['postseen'] == 'unseen'){ ?>selected <?php } ?>>Unseen Video</option>
                            </select>                                          
                        </div> 
                        <div class="filterSkillLan">
                            <h3>Filter Skill</h3>
                            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                            <select class="js-example-basic-multiple selSkillFilt" name="skills[]" multiple="multiple">
                                <option value="">All</option>    
                                <?php    
                                if(isset($_GET['skills'])) { $sk =  $_GET['skills']; }  

                                foreach($skillLang as $key1 => $skillval){
                                    if(isset($_GET['skills'])) {
                                        if(in_array($skillval, $sk)){
                                            $sel = 'selected';
                                            //$dis = 'disabled';
                                        }else{
                                            $sel = '';
                                           // $dis = '';
                                        }
                                    }
                                ?>
                                <option class="skillLang" <?php echo $sel ?> <?php echo $dis ?> data-skill-lang="<?php echo $skillval; ?>" value="<?php echo $skillval; ?>"><?php echo $skillval; ?></option>
                                    ?>"
                                <?php } ?>
                            </select>
                            <script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery('.js-example-basic-multiple').select2();

                                });                        
                            </script>
                        </div> 
                        <div class="allfilterCl">
                        <button type="submit" id="applyFilter" class="btn applyFilter">Apply</button> 
                        <div class="clearbut" data-clearurl="<?php echo home_url(); ?>/all-resume/?jobId=<?php echo $jobPostId; ?>" onclick="clearFilters()">Clear Filter</div>    
                        </div>                  
                    </form>                     
                                      
                </div>
                <div class="col-10">  
                                
                <?php //echo do_shortcode("[ziggeovideowall pre_set_list='fb7e921b95e64df6e2b0b6e51e65da7f,b0654155bbe961065ddf0eaebb5d97e6,8ca00c62ee93adef3ddfc54b256abbbc' autoplay='true' wall_design='slide_wall']"); ?>                 

                    <input type="hidden" class="demosintro" value="1">
                    <div id="" class="" >   
                        <div class="custom-slider" style="background: #f7f7f7">
                            <p class="prev-btn disabled"><img src="<?= site_url('/wp-content/uploads/2023/09/left-arrow.png')?>"></p>
                            <?php 
                            $args = array(
                                'post_type'  => 'resume',
                                'post_status' => 'publish',
                                'posts_per_page' => -1, 
                                'order'      => 'ASC',
                                'meta_query' => $metaquery,
                            );
                            $post_query = new WP_Query( $args );                            
                            $i = 1;
                            $x = 1;
                            $filterbyseen = false;
                            $filterbyrating = false;
                            $filterbyskills = false;

                            if($_GET['postseen']){
                                $filterbyseen = true;
                            }
                            if(!empty($_GET['rating']) && $_GET['rating'] != 'not_ratted'){
                                $filterbyrating = true;
                            }else if(!empty($_GET['rating']) && $_GET['rating'] == 'not_ratted'){
                                 $filterbyrating = true;
                            }
                            if(isset($_GET['skills'])){

                                $getskills = implode(",", $_GET['skills']);

                            }
                            if($getskills){
                                $filterbyskills = true;
                            }                          

                            $resumeArray = array();
                            $candidateVideoList = array();
                         
                            if ( $post_query->have_posts() ) :                                
                                while ( $post_query->have_posts() ) : $post_query->the_post();                                    

                                    $resumeLanguages = get_post_meta($post->ID,'_resume_languages', true);
                                    $cadidatevidoe =  get_post_meta($post->ID,'_candidate_video', true);

                                    $catdidatevideoArr = explode('/', $cadidatevidoe);
                                    $catdidatevideoArrIndex = count($catdidatevideoArr) - 2;

                                    $catedidatvideoid = $catdidatevideoArr[$catdidatevideoArrIndex];
                                    $candidateVideoList[] = $catedidatvideoid;
                                    /*echo ' <pre>';
                                    print_r($catdidatevideoArr);
                                    echo ' </pre>'; */                           
                            ?>

                            <?php
                            /******** Post Type 'job_application' Insert Post Custom Query *********/
                            if(isset($_GET['jobId'])){

                                $candidateEmail = get_post_meta($post->ID,'_candidate_email', true);                            
                                
                                $queryPost = $wpdb->prepare(
                                    'SELECT  ID, post_author, post_parent FROM ' . $wpdb->posts . '
                                    WHERE post_author = '.$post->post_author.'
                                    AND post_parent='.$_GET['jobId'].' AND post_type = \'job_application\'',
                                );

                                $wpdb->query($queryPost);
                                //echo $wpdb->num_rows;
                                if ($wpdb->num_rows == 0 ) {
                             
                                    $jobAppPostId = wp_insert_post(array (
                                        'post_type' => 'job_application',
                                        'post_title' => get_the_candidate_title(),
                                        'post_content' => get_the_content(),
                                        'post_parent' => $_GET['jobId'],
                                        'post_author' => $post->post_author,
                                        'post_status' => 'new',
                                        'comment_status' => 'closed',   // if you prefer
                                        'ping_status' => 'closed',      // if you prefer
                                    ));
                                    if ($jobAppPostId) {
                                        // insert post meta
                                        update_post_meta($jobAppPostId, '_job_applied_for', get_the_title($_GET['jobId']));
                                        update_post_meta($jobAppPostId, '_candidate_email', $candidateEmail);
                                        update_post_meta($jobAppPostId, '_candidate_user_id', $post->post_author);
                                        update_post_meta($jobAppPostId, '_rating', 0);
                                        update_post_meta($jobAppPostId, 'Full name', $post->post_title);
                                        update_post_meta($jobAppPostId, 'Email address', $candidateEmail);
                                        update_post_meta($jobAppPostId, '_candidateVideoSeen', 'unseen');
                                        add_post_meta($jobAppPostId, '_job_appliedID', $_GET['jobId']);
                                    }

                                }else{
                                    $resutls = $wpdb->get_results($queryPost);
                                    /*echo "<pre>"; 
                                    print_r($resutls);
                                    echo "</pre>";*/ 
                                    $jobAppPostId = $resutls[0]->ID;
                                }
                                
                            }                           
                             

                            $postseen = get_post_meta($jobAppPostId,'_candidateVideoSeen', true);
                            $postRating = get_post_meta($jobAppPostId,'_rating', true);

                            if(!empty($filterbyseen) || !empty($filterbyrating)){
                                $getPostRatting = $_GET['rating']=='no_ratting'?0:$_GET['rating'];
                                if($filterbyseen && !$filterbyrating){                                   
                                    if($postseen == $_GET['postseen']){ 
                                        $resumeArray[get_the_ID()]['post_id'] = get_the_ID();
                                        $resumeArray[get_the_ID()]['jobAppPostId'] = $jobAppPostId;
                                        $resumeArray[get_the_ID()]['catedidatvideoid'] = $catedidatvideoid;
                                        //$x++;
                                    }
                                }else if(!$filterbyseen && $filterbyrating){
                                    if($getPostRatting == $postRating){
                                        $resumeArray[get_the_ID()]['post_id'] = get_the_ID();
                                        $resumeArray[get_the_ID()]['jobAppPostId'] = $jobAppPostId;
                                        $resumeArray[get_the_ID()]['catedidatvideoid'] = $catedidatvideoid;
                                       // $x++;
                                    }                                    
                                }else if($filterbyseen && $filterbyrating){                                    
                                    if($postseen == $_GET['postseen'] && $getPostRatting == $postRating){
                                        $resumeArray[get_the_ID()]['post_id'] = get_the_ID();
                                        $resumeArray[get_the_ID()]['jobAppPostId'] = $jobAppPostId;
                                        $resumeArray[get_the_ID()]['catedidatvideoid'] = $catedidatvideoid;
                                        //$x++;
                                    }                                   
                               } 
                            }else{ 
                                $resumeArray[get_the_ID()]['post_id'] = get_the_ID();
                                $resumeArray[get_the_ID()]['jobAppPostId'] = $jobAppPostId;
                                $resumeArray[get_the_ID()]['catedidatvideoid'] = $catedidatvideoid;
                            } 
                                                        
                            $i++; endwhile;

                            else :
                            //echo 'Sorry, no posts were found.';

                            endif;
                            
                            wp_reset_postdata();

                            //$VideoTokenList = rtrim(implode(',', $candidateVideoList), ',');

                           // echo do_shortcode("[ziggeovideowall pre_set_list='".$VideoTokenList."' autoplay='true' wall_design='slide_wall']");
                                                     

                            /*echo '<pre>';
                            print_r($$resumeArrayVal);
                            echo '</pre>';*/

                            /*foreach($resumeArray as $resumeArrayVal){
                                $aaaa[] = $resumeArrayVal['catedidatvideoid'].',';
                            }
                            echo $aaaasss = rtrim(implode('', $aaaa), ',');*/
                            
                            
                            $postids = array_keys($resumeArray);
                            
                            $pids = array();
                            foreach($postids as $pid){
                                $pids[] = $pid;
                            }
                            
                            if($pids){
                            $i = 1;
                            $args1 = array(
                                'post_type'  => 'resume',
                                'post_status' => 'publish',
                                'posts_per_page' => -1, 
                                'order'      => 'ASC',
                                'post__in' => $pids,
                                'meta_query' => $metaquery,
                            );
                            $post_query1 = new WP_Query( $args1 ); 
                            if ( $post_query1->have_posts() ) :
                                while ( $post_query1->have_posts() ) : $post_query1->the_post(); 
                                    $cadidatevidoe1 =  get_post_meta($post->ID,'_candidate_video', true);
                                    $getCandidateLocation =  get_post_meta($post->ID,'_candidate_location', true);
                                    $catdidatevideoArr1 = explode('/', $cadidatevidoe1);
                                    $catdidatevideoArrIndex1 = count($catdidatevideoArr1) - 2;
                                    $catedidatvideoid1 = $catdidatevideoArr1[$catdidatevideoArrIndex1];
                                    $candidateVideoList[] = $catedidatvideoid1;                                    
                                ?>                                    
                                   
                                <div class="custom-slider-item <?= $i==1?'active':'' ?>" id="<?php echo $i ?>" style="width: 80%; background: #f7f7f7;margin:auto;text-align: center;">
                                    <div class="resume-aside">
                                       <?php if(!isset($getskills)){ ?>
                                       <?php if($i == 1){ ?>
                                       <div id="candidateVideo">
                                        <?php } ?>
                                        <?php //the_candidate_video(); ?>
                                        
                                        <ziggeoplayer ziggeo-video="<?php echo $catedidatvideoid1 ?>" <?php if($i == 1){ ?>autoplay='true' <?php } ?> id="candidate_intro_<?php echo $i ?>"  ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>
                                         <script>
                                            setTimeout(function(){
                                                var element_by_intro = document.getElementById('candidate_intro_<?php echo $i ?>');
                                                var embedding_intro = ZiggeoApi.V2.Player.findByElement(element_by_intro);
                                                embedding_intro.on("ended", function (healthy) {
                                                    console.log('aaaaa_ '+'candidate_intro_<?php echo $i ?>')
                                                    jQuery('.next-btn:not(.disabled)').click();
                                                    // var element_by_intro_next = document.getElementById('candidate_intro_<?php echo $i+1 ?>');
                                                    // var embedding_intro_next = ZiggeoApi.V2.Player.findByElement(element_by_intro_next);
                                                    // embedding_intro_next.play();
                                                   
                                                });
                                               
                                            }, 3000);
                                         </script>                                        

                                         <?php if($i == 1){ ?>
                                        </div>
                                        <?php } 
                                        }else{ ?> 

                                        <div class="inner-custom-slider" <?php echo count($pids); ?>>
                                            <p class="inner-prev-btn disabled"><img src="<?= site_url('/wp-content/uploads/2023/09/left-arrow.png')?>"></p>

                                         <div class="inner-custom-slider-item active" id="<?php echo $i ?>_p_1">
                                            <?php if($i == 1){ ?>
                                            <div id="candidateVideo">
                                            <?php } ?>
                                                <?php //the_candidate_video(); ?>
                                               <ziggeoplayer ziggeo-video="<?php echo $catedidatvideoid1 ?>" <?php if($i == 1){ ?>autoplay='true' <?php } ?> id="<?php echo $i ?>_skills_videoId_1"  ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>


                                                <script>
                                                    setTimeout(function(){
                                                        var element_by_skills1 = document.getElementById('<?php echo $i ?>_skills_videoId_1');
                                                        var embedding_skills1 = ZiggeoApi.V2.Player.findByElement(element_by_skills1);
                                                        embedding_skills1.on("ended", function (healthy) {
                                                           
                                                            //embedding_skills1.pause();
                                                                                                  
                                                            // jQuery('<?php echo "#".$i ?> > .resume-aside > .owl-carousel2 > .owl-nav > .owl-next').click();
                                                            console.log(jQuery('<?php echo "#".$i ?> > .resume-aside > .inner-custom-slider > .inner-custom-slider-item.active').next('.inner-custom-slider-item').length)

                                                            if(jQuery('<?php echo "#".$i ?> > .resume-aside > .inner-custom-slider > .inner-custom-slider-item.active').next('.inner-custom-slider-item').length){
                                                                jQuery('<?php echo "#".$i ?> > .resume-aside > .inner-custom-slider > .inner-next-btn').click();

                                                                // var getID=jQuery(".custom-slider-item.active .inner-custom-slider-item.active > ziggeoplayer").attr("id")
                                                                // console.log(getID); 
                                                                // var element_by_skills_next = document.getElementById(getID);
                                                                // var embedding_skills_next = ZiggeoApi.V2.Player.findByElement(element_by_skills_next);
                                                                // embedding_skills_next.play();
                                                            }else if(jQuery(".custom-slider-item.active").next(".custom-slider-item").length){
                                                                jQuery(".next-btn:not(.disabled)").click()
                                                                // var getID=jQuery(".custom-slider-item.active .inner-custom-slider-item.active > ziggeoplayer").attr("id")
                                                                // var element_by_skills_next = document.getElementById(getID);
                                                                // var embedding_skills_next = ZiggeoApi.V2.Player.findByElement(element_by_skills_next);
                                                                // embedding_skills_next.play();
                                                            }
                                                           
                                                           
                                                        });
                                                       
                                                    }, 2000);
                                                </script>                                               
                                            <?php if($i == 1){ ?>
                                            </div>
                                            <?php } ?>                            

                                            <div class="reratcand vvvvvvv">  
                                            <?php $candEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                            $candidateEmail = get_post_meta($post->ID,'_candidate_email', true); 
                                            $getUploadResume = get_post_meta($post->ID,'_upload_resume', true); 

                                            $ApplicatepostID = getApplicantRating($candidateEmail, $jobPostId);
                                               
                                            $rating = get_post_meta($ApplicatepostID,'_rating', true); 

                                            $jobAppPostId = $resumeArray[get_the_ID()]['jobAppPostId'];

                                            $catedidatvideoid = $resumeArray[get_the_ID()]['catedidatvideoid'];

                                            if($rating == 1){ $selected1 = 'selected'; }else{ $selected1 = ''; }
                                            if($rating == 2){ $selected2 = 'selected'; }else{  $selected2 = '' ;}
                                            if($rating == 3){ $selected3 = 'selected'; }else{  $selected3 = '';}
                                            if($rating == 4){ $selected4 = 'selected'; }else{  $selected4 = '';}
                                            if($rating == 5){ $selected5 = 'selected'; }else{  $selected5 = '';}
                                            ?>                                  
                                                <h2><?php _e( 'Rate a Candidate', 'wp-job-manager-resumes' ); ?></h2>
                                                <a href="javascript:void(0)"  class="resRateCandid <?php echo $selected1; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="1" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">1</a>
                                                <a href="javascript:void(0)" class="resRateCandid <?php echo $selected2; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="2" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">2</a>
                                                <a href="javascript:void(0)" class="resRateCandid <?php echo $selected3; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="3" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">3</a>
                                                <a href="javascript:void(0)" class="resRateCandid <?php echo $selected4; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="4" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">4</a>
                                                <a href="javascript:void(0)" class="resRateCandid <?php echo $selected5; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="5" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">5</a>
                                            
                                            </div>                                            
                                        </div> 
                                        <?php
                                        if(!empty($getUploadResume) && in_array("content-type: application/pdf", get_headers($getUploadResume))) {
                                        ?> 
                                        <!-- <div class="resume-viewer">
                                            <h2><?php _e( 'Candidate Resume ', 'wp-job-manager-resumes' ); ?></h2>
                                            <iframe src="<?=  $getUploadResume ?>" width="1000" height="600"></iframe>
                                        </div>   -->
                                        <?php   
                                        }
                                        //echo $filterbyskills .' _ssssssss';
                                        if($filterbyskills){
                                            
                                            $filterkill = strtolower($getskills);
                                            $filterkillArr = explode(",",$filterkill);

                                            $resumeLanguages = get_post_meta($post->ID,'_resume_languages', true);
                                            //print_r($resumeLanguages);
                                           // echo count($resumeLanguages) ;
                                            $j = 2;
                                            foreach ($resumeLanguages as $key => $langVideo) { 

                                               $langName = strtolower($langVideo);

                                               if(in_array($langName, $filterkillArr)){
                                                    $languageQuestionID = get_page_by_title($langName.'_video', OBJECT, 'jmfe_custom_fields');
                                                    $allQuestion=get_post_meta( $languageQuestionID->ID,'description',true);

                                                    $langVideoVal = get_post_meta($post->ID, '_'.$langName.'_video', true );
                                                    $langvideoArr = explode('/', $langVideoVal);
                                                    $langvideoArrIndex = count($langvideoArr) - 2;
                                                    $langvideoid = $langvideoArr[$langvideoArrIndex];
                                                    if($langvideoid != ''){
                                                        $allSkillQues = get_post_meta($post->ID,'_'.$langName.'_skill_question', true);
                                                    ?>
                                                    <div class="inner-custom-slider-item <?= $j==1?'active':'' ?>" id="<?php echo $i ?>_p_<?php echo $j ?>" data-qusId="<?php echo $allSkillQues; ?>">
                                                        <?php 
                                                        //$allSkillQues = get_post_meta($post->ID,'_'.$langName.'_skill_question', true);

                                                        //$pythonSkillQues = get_post_meta($post->ID,'_python_skill_question', true);
                                                        //$databricksSkillQues = get_post_meta($post->ID,'_databricks_skill_question', true);
                                                        ?>
                                                        <div class="slider-qus-video-box">
                                                            <ziggeoplayer ziggeo-video="<?php echo  $langvideoid; ?>"  ziggeo-width=100% ziggeo-theme="modern" id="<?php echo $i ?>_skills_videoId_<?php echo $j ?>" ziggeo-themecolor="red"> </ziggeoplayer>
                                                            <div class="skillLangQues"><?php echo $allQuestion; ?></div>
                                                        </div>
                                                        <script>
                                                            setTimeout(function(){
                                                                var element_by_skills12 = document.getElementById('<?php echo $i ?>_skills_videoId_<?php echo $j ?>');
                                                                var embedding_skills12 = ZiggeoApi.V2.Player.findByElement(element_by_skills12);
                                                                embedding_skills12.on("ended", function (healthy) {
                                                                    
                                                                    // <?php if($j == count($resumeLanguages)+1){ ?>
                                                                    //     jQuery('.custom-slider > .next-btn').click();
                                                                    //     var element_by_skills11_next = document.getElementById('<?php echo $i+1?>_skills_videoId_1');
                                                                    //     var embedding_skills11_next = ZiggeoApi.V2.Player.findByElement(element_by_skills11_next);
                                                                    //     embedding_skills11_next.play();
                                                                    // <?php }else{ ?>
                                                                    // jQuery('<?php echo "#".$i ?> > .resume-aside > .owl-carousel2 > .owl-nav > .owl-next').click();
                                                                    // var element_by_skills1_next = document.getElementById('<?php echo $i ?>_skills_videoId_<?php echo $j+1 ?>');
                                                                    // var embedding_skills1_next = ZiggeoApi.V2.Player.findByElement(element_by_skills1_next);
                                                                    // embedding_skills1_next.play();
                                                                    // <?php } ?>
                                                                    console.log(jQuery('<?php echo "#".$i ?> > .resume-aside > .inner-custom-slider > .inner-custom-slider-item.active').next('.inner-custom-slider-item').length)

                                                                    if(jQuery('<?php echo "#".$i ?> > .resume-aside > .inner-custom-slider > .inner-custom-slider-item.active').next('.inner-custom-slider-item').length){
                                                                        jQuery('<?php echo "#".$i ?> > .resume-aside > .inner-custom-slider > .inner-next-btn').click();

                                                                        // var getID=jQuery(".custom-slider-item.active .inner-custom-slider-item.active > ziggeoplayer").attr("id")
                                                                        // console.log(getID); 
                                                                        // var element_by_skills_next = document.getElementById(getID);
                                                                        // var embedding_skills_next = ZiggeoApi.V2.Player.findByElement(element_by_skills_next);
                                                                        // embedding_skills_next.play();
                                                                    }else if(jQuery(".custom-slider-item.active").next(".custom-slider-item").length){
                                                                        jQuery(".next-btn").click()
                                                                        // var getID=jQuery(".custom-slider-item.active .inner-custom-slider-item.active > ziggeoplayer").attr("id")
                                                                        // var element_by_skills_next = document.getElementById(getID);
                                                                        // var embedding_skills_next = ZiggeoApi.V2.Player.findByElement(element_by_skills_next);
                                                                        // embedding_skills_next.play();
                                                                    }

                                                                });
                                                               
                                                            }, 2000);
                                                        </script>


                                                    <div class="reratcand skillrated">  
                                                    <?php $candEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                                    $candidateEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                                    $ApplicatepostID = getApplicantRating($candidateEmail, $jobPostId);
                                                     

                                                    $ratingJson = get_post_meta($ApplicatepostID,'_language_ratings', true); 
                                                    $ratingArr = json_decode($ratingJson, true);

                                                    $rating = $ratingArr[strtolower($langName)];

                                                    $jobAppPostId = $resumeArray[get_the_ID()]['jobAppPostId'];

                                                    $catedidatvideoid = $resumeArray[get_the_ID()]['catedidatvideoid'];

                                                    if($rating == 1){ $selected1 = 'selected'; }else{ $selected1 = ''; }
                                                    if($rating == 2){ $selected2 = 'selected'; }else{  $selected2 = '' ;}
                                                    if($rating == 3){ $selected3 = 'selected'; }else{  $selected3 = '';}
                                                    if($rating == 4){ $selected4 = 'selected'; }else{  $selected4 = '';}
                                                    if($rating == 5){ $selected5 = 'selected'; }else{  $selected5 = '';}
                                                    ?>                                  
                                                        <h2><?php _e( 'Rate '.$langName, 'wp-job-manager-resumes' ); ?></h2>
                                                        <a href="javascript:void(0)"  class="resRateCandid <?php echo $selected1; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="1" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>" data-lang="<?php echo $langName; ?>">1</a>
                                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected2; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="2" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>" data-lang="<?php echo $langName; ?>">2</a>
                                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected3; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="3" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>" data-lang="<?php echo $langName; ?>">3</a>
                                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected4; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="4" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>" data-lang="<?php echo $langName; ?>">4</a>
                                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected5; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="5" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>" data-lang="<?php echo $langName; ?>">5</a>
                                                    
                                                    </div>
                                                    </div>
                                                    <?php  
                                                    }$j++;
                                               }
                                               
                                            }
                                        }
                                        
                                        ?>
                                         <p class="inner-next-btn"><img src="<?= site_url('/wp-content/uploads/2023/09/next-arrow.png')?>"></p>
                                        </div>
                                       
                                    <?php } ?> 
                                    </div> 
                                    <?php if(!isset($getskills)){ ?>
                                    <div class="reratcand">  
                                    <?php $candEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                    $candidateEmail = get_post_meta($post->ID,'_candidate_email', true); 
                                    $getUploadResume = get_post_meta($post->ID,'_upload_resume', true); 

                                    

                                    $ApplicatepostID = getApplicantRating($candidateEmail, $jobPostId);
                                       
                                    $rating = get_post_meta($ApplicatepostID,'_rating', true); 

                                    $jobAppPostId = $resumeArray[get_the_ID()]['jobAppPostId'];

                                    $catedidatvideoid = $resumeArray[get_the_ID()]['catedidatvideoid'];

                                    if($rating == 1){ $selected1 = 'selected'; }else{ $selected1 = ''; }
                                    if($rating == 2){ $selected2 = 'selected'; }else{  $selected2 = '' ;}
                                    if($rating == 3){ $selected3 = 'selected'; }else{  $selected3 = '';}
                                    if($rating == 4){ $selected4 = 'selected'; }else{  $selected4 = '';}
                                    if($rating == 5){ $selected5 = 'selected'; }else{  $selected5 = '';}
                                    ?>                                  
                                        <h2><?php _e( 'Rate a Candidate', 'wp-job-manager-resumes' ); ?></h2>
                                        <a href="javascript:void(0)"  class="resRateCandid <?php echo $selected1; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="1" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">1</a>
                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected2; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="2" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">2</a>
                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected3; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="3" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">3</a>
                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected4; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="4" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">4</a>
                                        <a href="javascript:void(0)" class="resRateCandid <?php echo $selected5; ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="5" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $jobPostId; ?>">5</a>
                                    
                                    </div> 
                                    <?php } 
                                    if(!empty($getUploadResume) && in_array("content-type: application/pdf", get_headers($getUploadResume))) {
                                    ?> 
                                    <div class="resume-viewer">
                                        <h2><?php _e( 'Candidate Resume ', 'wp-job-manager-resumes' ); ?></h2>
                                        <iframe src="<?=  $getUploadResume ?>" width="1000" height="600"></iframe>
                                    </div>  
                                    <?php 
                                    }

                                    if ( ( $skills = wp_get_object_terms( $post->ID, 'resume_skill', [ 'fields' => 'names' ] ) ) && is_array( $skills ) ) : ?>
                                        <h2><?php _e( 'Skills', 'wp-job-manager-resumes' ); ?></h2>
                                        <ul class="resume-manager-skills">
                                            <?php echo '<li>' . implode( '</li><li>', $skills ) . '</li>'; ?>
                                        </ul>
                                    <?php endif; ?> 

                                    <?php if ( $items = get_post_meta( $post->ID, '_candidate_education', true ) ) : ?>
                                        <h2><?php _e( 'Education', 'wp-job-manager-resumes' ); ?></h2>
                                        <dl class="resume-manager-education">
                                        <?php
                                        foreach ( $items as $item ) :
                                            ?>
                                                <dt>
                                                    <small class="date"><?php echo esc_html( $item['date'] ); ?></small>
                                                    <h3><?php printf( __( '%1$s at %2$s', 'wp-job-manager-resumes' ), '<strong class="qualification">' . esc_html( $item['qualification'] ) . '</strong>', '<strong class="location">' . esc_html( $item['location'] ) . '</strong>' ); ?></h3>
                                                </dt>
                                                <dd>
                                                    <?php echo wpautop( wptexturize( $item['notes'] ) ); ?>
                                                </dd>

                                            <?php
                                            endforeach;
                                        ?>
                                        </dl>
                                    <?php endif; ?>   
                                                                                                        
                                    <?php if ( $items = get_post_meta( $post->ID, '_candidate_experience', true ) ) : ?>
                                        <h2><?php _e( 'Experience', 'wp-job-manager-resumes' ); ?></h2>
                                        <dl class="resume-manager-experience">
                                        <?php
                                        foreach ( $items as $item ) :
                                            ?>
                                                <dt>
                                                    <small class="date"><?php echo esc_html( $item['date'] ); ?></small>
                                                    <h3><?php printf( __( '%1$s at %2$s', 'wp-job-manager-resumes' ), '<strong class="job_title">' . esc_html( $item['job_title'] ) . '</strong>', '<strong class="employer">' . esc_html( $item['employer'] ) . '</strong>' ); ?></h3>
                                                </dt>
                                                <dd>
                                                    <?php echo wpautop( wptexturize( $item['notes'] ) ); ?>
                                                </dd>

                                            <?php
                                            endforeach;
                                        ?>
                                        </dl>
                                    <?php endif; ?> 

                                    <script type="text/javascript">
                                    setTimeout(function() {
                                        var player = ZiggeoApi.V2.Player.findByElement(jQuery('[ziggeo-video="<?php echo $catedidatvideoid; ?>"]'));
                                        
                                        player.on("playing", function(){
                                            //console.log('<?php //echo $catedidatvideoid.' -------- '. $jobAppPostId; ?>');

                                            var japost_id = "<?php echo $jobAppPostId; ?>";
                                            //console.log("japost_id "+japost_id)

                                            jQuery.ajax({
                                                type : "post",
                                                //dataType : "json",
                                                url : '<?php echo admin_url( 'admin-ajax.php' );?>',
                                                data : {action: "sp_catedidatvideoseen", japost_id : japost_id},
                                                success: function(res) {
                                                    //console.log(res);
                                                    if(res){
                                                        //console.log('asdfdsf');
                                                        //alert('Candidate video seen Update Successfully');
                                                    }
                                                }
                                            }) 
                                            
                                        }); 

                                    }, 2000);          

                                    </script>

                                </div> 
                              
                         
                            
                                    <?php
                                    $i++;
                                endwhile;
                            endif;
                            //wp_reset_postdata();
                        }else{
                            echo '<span class="notavil">Not available resume</span>';
                        }
                            ?>      
                          <p class="next-btn"><img src="<?= site_url('/wp-content/uploads/2023/09/next-arrow.png')?>"></p> 
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style type="text/css">
.disabled1 {
    cursor: not-allowed;
    pointer-events: none;
}
</style>

<script type="text/javascript">    
jQuery(document).ready( function() {
   jQuery(".resRateCandid").click( function(e) {

    var rID = jQuery(this).attr('data-post_id');
    //console.log(rID);
    jQuery(".resume_"+rID).each(function( index ) {
        if(jQuery(this).hasClass("selected")){
            jQuery(this).removeClass("selected");
        }
    });

        if(jQuery(this).hasClass( "selected" )){

        }else{
            jQuery(this).addClass('selected');
        }
        
      e.preventDefault(); 
      var post_id = jQuery(this).attr("data-post_id");
      var rate_candidate = jQuery(this).attr("data-rate_candidate");
      var email_candidate = jQuery(this).attr("data-email_candidate");
      var jobId_candidate = jQuery(this).attr("data-jobId_candidate");
      var data_lang = jQuery(this).attr("data-lang");

      jQuery.ajax({
         type : "post",
         //dataType : "json",
         url : '<?php echo admin_url( 'admin-ajax.php' );?>',
         data : {action: "sp_resumeRateCandidate", post_id : post_id, rate_candidate: rate_candidate, email_candidate : email_candidate, jobId_candidate: jobId_candidate, data_lang: data_lang},
         success: function(res) {
            //console.log(res);
            if(res){
                //console.log('asdfdsf');
               // alert('Rate Update Successfully');
               //jQuery("#vote_counter").html(response.vote_count)
            }
         }
      })   

   })  

});    
</script>

<script>

    jQuery(document).ready(function($){
        nextPrevHideShow()
        function nextPrevHideShow(){
            var length= $(".custom-slider-item.active .inner-custom-slider .inner-custom-slider-item").length;
            var outerLength= $(".custom-slider .custom-slider-item.active").next('.custom-slider-item').length;

            if(outerLength<=0){
                $(".next-btn").addClass('disabled')
            }

           if(length<2){
            $(".custom-slider-item.active .inner-custom-slider .inner-prev-btn").hide()
            $(".custom-slider-item.active .inner-custom-slider .inner-next-btn").hide()
           }else{
            setTimeout(function(){
                $(".custom-slider-item.active .inner-custom-slider .inner-prev-btn").show()
                $(".custom-slider-item.active .inner-custom-slider .inner-next-btn").show()
            },2000);
           }
        }
        function addClassOnInnerNextBtn(){

            $(".skillLangQues ul li").hide();
            var qusId = $(".custom-slider-item.active .inner-custom-slider .inner-custom-slider-item.active").attr('data-qusId')
            $(".custom-slider-item.active .inner-custom-slider .inner-custom-slider-item.active .skillLangQues ul li#"+qusId).show()

            $(".inner-next-btn").removeClass('change-btn-postion')
            if($(".inner-custom-slider-item.active .slider-qus-video-box").length){
                $(".inner-custom-slider-item.active").closest(".inner-custom-slider").find(".inner-next-btn").addClass('change-btn-postion')
            }
        }

        $(document).on("click", ".next-btn", function (e) {
            
            var that = $(this);
            that.hide();
            var preBtn = $(".prev-btn");
            preBtn.hide();   
            $('.inner-prev-btn').hide();         
            $('.inner-next-btn').hide();         
            setTimeout(function(){
                that.show();
                preBtn.show();
                $('.inner-prev-btn').show();         
                $('.inner-next-btn').show(); 

            },2000);

            var current = that.closest('.custom-slider').find('.custom-slider-item.active');
            var cuurentPlayViedeo=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')
            //console.log('bbbbbbbb '+cuurentPlayViedeo);
        
            if (current.next('.custom-slider-item').length) {
                current.next('.custom-slider-item').addClass('active');
                current.removeClass('active');
                if( current.next('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-custom-slider-item:first").length){
                    current.find(".inner-custom-slider-item").removeClass("active")
                     current.next('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-custom-slider-item:first").addClass("active")
                     current.next('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-prev-btn").addClass('disabled')
                     current.next('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-next-btn").removeClass('disabled')
                }

                nextPrevHideShow()
            }
            $(".prev-btn").removeClass('disabled')
            if(that.closest('.custom-slider').find(".custom-slider-item.active").next('.custom-slider-item').length){
                $(".next-btn").removeClass('disabled')
            } else{
                $(".next-btn").addClass('disabled')
            }  
            addClassOnInnerNextBtn()
           // console.log("click trigger");
            
            setTimeout(function(){        
            var mouse_click = !(e.originalEvent === undefined); 
            //console.log(mouse_click);
                // if (mouse_click) {
                    //console.log(jQuery('.custom-slider > .custom-slider-item.active').attr('id'));

                    var ids = parseInt(jQuery('.custom-slider > .custom-slider-item.active').attr('id'))-1;

                    //console.log('custom-slider'+ids);

                    var element_by_intro1 = document.getElementById('candidate_intro_'+ids);
                    //console.log(element_by_intro1)
                    if(element_by_intro1==null || element_by_intro1==undefined){
                        element_by_intro1 = document.getElementById(cuurentPlayViedeo);

                        console.log('next box '+element_by_intro1+'ID='+cuurentPlayViedeo);
                    }
                    var embedding_intro1 = ZiggeoApi.V2.Player.findByElement(element_by_intro1);
                    embedding_intro1.stop();
                    setTimeout(function(){   

                        if(element_by_intro1==null || element_by_intro1==undefined){
                            //console.log('1111111111');
                            var cuurentPlayViedeo1=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')
                            var element_by_intro_current = document.getElementById(cuurentPlayViedeo1);
                            var embedding_intro_current = ZiggeoApi.V2.Player.findByElement(element_by_intro_current);
                            embedding_intro_current.play();
                        }else{
                            //console.log('22222222222222');
                            var cuurentPlayViedeo1=$('.custom-slider .custom-slider-item.active .resume-aside ziggeoplayer').attr('id')
                            var element_by_intro_current = document.getElementById(cuurentPlayViedeo1);
                            var embedding_intro_current = ZiggeoApi.V2.Player.findByElement(element_by_intro_current);
                            embedding_intro_current.play();
                        }

                    },300); 

                // }                 

            },500); 



        });

        function stopAllVideo(){
            $( "ziggeoplayer" ).each(function( index ) {
                var element_by_intro1 = document.getElementById($(this).attr("id"));
                var embedding_intro1 = ZiggeoApi.V2.Player.findByElement(element_by_intro1);
                embedding_intro1.stop();

            });
        }

        

        $(document).on("click", ".prev-btn", function (e) {

            var that = $(this);
            var nxtBtn = $(".next-btn");
            that.hide();
            nxtBtn.hide(); 
            $('.inner-prev-btn').hide();         
            $('.inner-next-btn').hide();            
            setTimeout(function(){
                that.show();
                nxtBtn.show(); 
                $('.inner-prev-btn').show();         
                $('.inner-next-btn').show();                
            },2000);

            var current = that.closest('.custom-slider').find('.custom-slider-item.active');
            var cuurentPlayViedeo=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id');
            if (current.prev('.custom-slider-item').length) {
                current.prev('.custom-slider-item').addClass('active');
                current.removeClass('active');

               if( current.prev('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-custom-slider-item:first").length){
                    current.find(".inner-custom-slider-item").removeClass("active")
                    current.prev('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-custom-slider-item:first").addClass("active")
                    current.prev('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-prev-btn").addClass('disabled')
                    current.prev('.custom-slider-item').find(".resume-aside .inner-custom-slider .inner-next-btn").removeClass('disabled')
                }
                nextPrevHideShow()
            }
            $(".next-btn").removeClass('disabled')
            if(that.closest('.custom-slider').find(".custom-slider-item.active").prev('.custom-slider-item').length){
                $(".prev-btn").removeClass('disabled')
            } else{
                $(".prev-btn").addClass('disabled')
            }  
            addClassOnInnerNextBtn()

            setTimeout(function(){      
                var mouse_click = !(e.originalEvent === undefined);           
                    if (mouse_click) {
                        
                        // console.log(jQuery('.custom-slider > .custom-slider-item.active').attr('id'));
                        
                        var ids = parseInt(jQuery('.custom-slider > .custom-slider-item.active').attr('id'))+1;                  
                       // console.log(ids);

                        var element_by_intro1 = document.getElementById('candidate_intro_'+ids);
                        if(element_by_intro1==null || element_by_intro1==undefined){
                            element_by_intro1 = document.getElementById(cuurentPlayViedeo);
                            console.log('next box '+element_by_intro1+'ID='+cuurentPlayViedeo);
                        }
                        var embedding_intro1 = ZiggeoApi.V2.Player.findByElement(element_by_intro1);
                        embedding_intro1.stop();

                        // stopAllVideo()

                        // console.log('showing all video');

                        setTimeout(function(){  

                            if(element_by_intro1==null || element_by_intro1==undefined){
                                var cuurentPlayViedeo1=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')
                                var element_by_intro_current = document.getElementById(cuurentPlayViedeo1);
                                var embedding_intro_current = ZiggeoApi.V2.Player.findByElement(element_by_intro_current);
                                embedding_intro_current.play();
                            }else{
                                //console.log('444444444');
                                var cuurentPlayViedeo1=$('.custom-slider .custom-slider-item.active .resume-aside ziggeoplayer').attr('id')
                                var element_by_intro_current = document.getElementById(cuurentPlayViedeo1);
                                var embedding_intro_current = ZiggeoApi.V2.Player.findByElement(element_by_intro_current);
                                embedding_intro_current.play();
                            }

                        },300); 


                    } 
            },500);   
        });

        $(document).on("click", ".inner-next-btn", function (e) {
            var that = $(this);

            
            var innerPreBtn = $(".inner-prev-btn");
            that.hide()
            innerPreBtn.hide();
            $(".prev-btn").hide()
            $(".next-btn").hide()
            setTimeout(function(){
                that.show();
                innerPreBtn.show();
                $(".prev-btn").show();
                $(".next-btn").show();

            },2000);

            var current = that.closest('.inner-custom-slider').find('.inner-custom-slider-item.active');
            var cuurentPlayViedeo=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')
            if (current.next('.inner-custom-slider-item').length) {
                current.next('.inner-custom-slider-item').addClass('active');
                current.removeClass('active');
            }
            that.closest(".inner-custom-slider").find(".inner-prev-btn").removeClass('disabled')
            if(that.closest('.inner-custom-slider').find(".inner-custom-slider-item.active").next('.inner-custom-slider-item').length){
                that.closest(".inner-custom-slider").find(".inner-next-btn").removeClass('disabled')
            } else{
                that.closest(".inner-custom-slider").find(".inner-next-btn").addClass('disabled')
            }  
            addClassOnInnerNextBtn()
            setTimeout(function(){
            var mouse_click = !(e.originalEvent === undefined); 
              //  if (mouse_click) {
                   
                    // console.log(jQuery('.custom-slider > .custom-slider-item.active > .inner-custom-slider > .inner-custom-slider-item.active').attr('id'));

                    var ids = parseInt(jQuery('.custom-slider > .custom-slider-item.active > .inner-custom-slider > .inner-custom-slider-item.active').attr('id'))-1;

                    //console.log('custom-slider'+ids);

                    var element_by_intro1 = document.getElementById('candidate_intro_'+ids);
                    if(element_by_intro1==null || element_by_intro1==undefined){
                        
                        //console.log('pppppp '+cuurentPlayViedeo);

                        element_by_intro1 = document.getElementById(cuurentPlayViedeo);
                    }
                    var embedding_intro1 = ZiggeoApi.V2.Player.findByElement(element_by_intro1);
                    embedding_intro1.stop();

                    setTimeout(function(){   
                        var cuurentPlayViedeo1=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')
                        var element_by_intro_current = document.getElementById(cuurentPlayViedeo1);
                        var embedding_intro_current = ZiggeoApi.V2.Player.findByElement(element_by_intro_current);
                        embedding_intro_current.play();
                    },300); 
               // }            

            },500); 
        });
        $(document).on("click", ".inner-prev-btn", function (e) {
            var that = $(this);

            var nextPreBtn = $(".inner-next-btn");
            that.hide();
            nextPreBtn.hide();
            $(".prev-btn").hide();
            $(".next-btn").hide();
            setTimeout(function(){
                    that.show();
                    nextPreBtn.show();
                    $(".prev-btn").show();
                    $(".next-btn").show();
            },2000);

            var current = that.closest('.inner-custom-slider').find('.inner-custom-slider-item.active');
            var cuurentPlayViedeo=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')

            if (current.prev('.inner-custom-slider-item').length) {
                current.prev('.inner-custom-slider-item').addClass('active');
                current.removeClass('active');
            }
            that.closest(".inner-custom-slider").find(".inner-next-btn").removeClass('disabled')
            if(that.closest('.inner-custom-slider').find(".inner-custom-slider-item.active").prev('.inner-custom-slider-item').length){
                that.closest(".inner-custom-slider").find(".inner-prev-btn").removeClass('disabled')
            } else{
                that.closest(".inner-custom-slider").find(".inner-prev-btn").addClass('disabled')
            }
            addClassOnInnerNextBtn() 

            setTimeout(function(){      
                var mouse_click = !(e.originalEvent === undefined);           
                    if (mouse_click) {
                        
                        // console.log(jQuery('.custom-slider > .custom-slider-item.active > .inner-custom-slider > .inner-custom-slider-item.active').attr('id'));
                        
                        var ids = parseInt(jQuery('.custom-slider > .custom-slider-item.active > .inner-custom-slider > .inner-custom-slider-item.active').attr('id'))+1;

                        //console.log('custom-slider'+ids);

                        var element_by_intro1 = document.getElementById('candidate_intro_'+ids);
                            if(element_by_intro1==null || element_by_intro1==undefined){
                                element_by_intro1 = document.getElementById(cuurentPlayViedeo);
                                //console.log('cccccccccc '+element_by_intro1);
                            }
                        var embedding_intro1 = ZiggeoApi.V2.Player.findByElement(element_by_intro1);
                        embedding_intro1.stop();
                        
                        setTimeout(function(){                            
                            var cuurentPlayViedeo1=$('.custom-slider .custom-slider-item.active .inner-custom-slider-item.active ziggeoplayer').attr('id')
                            var element_by_intro_current = document.getElementById(cuurentPlayViedeo1);
                            var embedding_intro_current = ZiggeoApi.V2.Player.findByElement(element_by_intro_current);
                            embedding_intro_current.play();
                        },300); 
                    } 
            },500);   
        });


        $(document).on("change",".selRatFilt",function(){
            $(this).closest("form").submit()
        })
    })
</script>
<?php get_footer(); ?>