<?php
/*Template Name: View All Resume Candidate*/
get_header();

?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<!-- Owl Stylesheets -->
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.theme.default.min.css">


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<!-- javascript -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/owl.carousel.js"></script>

<script type="text/javascript">
    function gotopage(selval){
        var value = selval.options[selval.selectedIndex].value;
        window.location.href=value;
    }    
</script>
<?php 
$metaquery = array();
$jobPostId = '';
if(isset($_GET['jobId'])){
    $jobPostId = $_GET['jobId'];
    $skillLang = get_post_meta( $jobPostId, '_skill_language', true );

    $metaquery['relation'] = 'AND';
    foreach($skillLang as $key => $skill){
        $metaquery[$key]['key'] = '_resume_languages';
        $metaquery[$key]['value'] = $skill;
        $metaquery[$key]['compare'] = 'LIKE';
    }   
}
?>
<div class="single-resume-content">
    <!-- rateCandidates Section -->
    <section id="rateCandidate">
        <div class="container">
            <div class="row">
                <div class="leftsidefilt col-3">
                    <div class="filterRatingRe">
                        <h3>Filter Rating</h3>
                        <?php if(isset($_GET['postseen'])){ $postseen = '&postseen='.$_GET['postseen'] ; } ?>
                        <?php if(isset($_GET['rating'])){ $rating = '&rating='.$_GET['rating'] ; } ?>
                        <?php if(isset($_GET['skills'])){ $skills = '&skills='.$_GET['skills'] ; } ?>

                        <select class="selRatFilt" onchange="gotopage(this)">
                            <option value="?jobId=<?php echo $jobPostId; ?><?php echo $postseen; echo $skills; ?>">All</option>
                            <option class="resfiltCandid" data-rate_candidate="Notrated" value="?jobId=<?php echo $jobPostId; ?><?php echo $postseen; echo $skills; ?>">Not rated</option>
                            <option class="resfiltCandid" data-rate_candidate="1" value="?jobId=<?php echo $jobPostId; ?>&rating=1<?php echo $postseen; echo $skills; ?>" <?php if($_GET['rating'] == 1){ ?>selected <?php } ?>>1</option>
                            <option class="resfiltCandid" data-rate_candidate="2" value="?jobId=<?php echo $jobPostId; ?>&rating=2<?php echo $postseen; echo $skills; ?>" <?php if($_GET['rating'] == 2){ ?>selected <?php } ?>>2</option>
                            <option class="resfiltCandid" data-rate_candidate="3" value="?jobId=<?php echo $jobPostId; ?>&rating=3<?php echo $postseen; echo $skills; ?>" <?php if($_GET['rating'] == 3){ ?>selected <?php } ?>>3</option>
                            <option class="resfiltCandid" data-rate_candidate="4" value="?jobId=<?php echo $jobPostId; ?>&rating=4<?php echo $postseen; echo $skills; ?>" <?php if($_GET['rating'] == 4){ ?>selected <?php } ?>>4</option>
                            <option class="resfiltCandid" data-rate_candidate="5" value="?jobId=<?php echo $jobPostId; ?>&rating=5<?php echo $postseen; echo $skills; ?>" <?php if($_GET['rating'] == 5){ ?>selected <?php } ?>>5</option>
                        </select> 
                    </div>

                    <div class="filterRatingVideo">
                        <h3>Filter Seen Video</h3> 
                        <select class="selSeenVidFilt" onchange="gotopage(this)">
                            <option value="?jobId=<?php echo $jobPostId; ?><?php echo $rating; echo $skills; ?>">All</option>
                            <option class="seenVideo" data-video_seen="seen" value="?jobId=<?php echo $jobPostId; ?>&postseen=seen<?php echo $rating; echo $skills; ?>" <?php if($_GET['postseen'] == 'seen'){ ?>selected <?php } ?>>Seen Video</option>
                            <option class="unSeenVideo" data-video_unseen="unseen" value="?jobId=<?php echo $jobPostId; ?>&postseen=unseen<?php echo $rating; echo $skills; ?>" <?php if($_GET['postseen'] == 'unseen'){ ?>selected <?php } ?>>Unseen Video</option>
                        </select>                                          
                    </div>  

                    <div class="filterSkillLan">
                        <h3>Filter Skill</h3>

                        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                        <select class="js-example-basic-multiple selSkillFilt" name="states[]" multiple="multiple">
                            <option value="">All</option>                    
                            <?php  if(isset($_GET['skills'])) { $sk = explode(',', $_GET['skills']); }  foreach($skillLang as $key1 => $skillval){
                                if(isset($_GET['skills'])) {
                                    if(in_array($skillval, $sk)){
                                        $sel = 'selected';
                                        $dis = 'disabled';
                                    }else{
                                        $sel = '';
                                        $dis = '';
                                    }
                                }
                            ?>

                                <option class="skillLang" <?php echo $sel ?> <?php echo $dis ?>  data-skill-lang="<?php echo $skillval; ?>" value="<?php echo $skillval; ?>"><?php echo $skillval; ?></option>
                                ?>"
                            <?php } ?>
                        </select>
                        <script type="text/javascript">
                            jQuery(document).ready(function() {
                                jQuery('.js-example-basic-multiple').select2();

                            });

                           
                            jQuery(".selSkillFilt").change(function () {
                                //console.log(jQuery(this).val());
                                //jQuery("#MultiSelect_Preview").val(jQuery(this).val());

                                var skilfind = jQuery(this).val();

                                //console.log(jQuery(this).select2('data')); 

                                if(skilfind == ''){
                                    var getSkill = '';
                                     var seleced = jQuery(this).select2('data');

                                    jQuery.each(seleced, function(  index, value  ) {
                                        jQuery.each(value, function(  indexs, values  ) {
                                            //console.log(indexs+'='+values);
                                            if(indexs == 'text'){
                                                getSkill +=values+',';
                                            }
                                        });
                                    });
                                    getSkill = getSkill.replace(/,$/g, '');
                                }else{
                                    var getSkill = '<?php echo $_GET['skills']; ?>';

                                    <?php if(isset($_GET['skills'])) { ?>
                                        getSkill = '<?php echo $_GET['skills']; ?>'+','+skilfind;
                                    <?php } ?>
                                }
                                if(getSkill ==''){
                                    var urlfilt= '?jobId=<?php echo $jobPostId; ?>&skills='+skilfind+'<?php echo $postseen; echo $rating; ?> ';
                                }else {
                                    var urlfilt= '?jobId=<?php echo $jobPostId; ?>&skills='+getSkill+'<?php echo $postseen; echo $rating; ?> ';
                                }
                                //console.log(urlfilt);
                                window.location.href = urlfilt;
                                

                                
                            });



                        </script> 

                    </div>     
     

                     
                </div>
                <div class="col-9">
                    <div id="rateCandidateCarousel" class="carousel slide" data-ride="carousel">   
                        <div class="owl-carousel owl-theme sp-outer-slider-box" id="firstslider" style="background: #f7f7f7">
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
                            if($_GET['rating']){
                                $filterbyrating = true;
                            }
                            if($_GET['skills']){
                                $filterbyskills = true;
                            }

                            $resumeArray = array();
                         
                            if ( $post_query->have_posts() ) :
                                while ( $post_query->have_posts() ) : $post_query->the_post();

                            $resumeLanguages = get_post_meta($post->ID,'_resume_languages', true); 

                            $cadidatevidoe =  get_post_meta($post->ID,'_candidate_video', true); 
                                

                            $catdidatevideoArr = explode('/', $cadidatevidoe);
                            $catdidatevideoArrIndex = count($catdidatevideoArr) - 2;

                            $catedidatvideoid = $catdidatevideoArr[$catdidatevideoArrIndex];
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

                            if($filterbyseen || $filterbyrating){
                                if($filterbyseen && !$filterbyrating){                                   
                                    if($postseen == $_GET['postseen']){ 
                                        $resumeArray[get_the_ID()]['post_id'] = get_the_ID();
                                        $resumeArray[get_the_ID()]['jobAppPostId'] = $jobAppPostId;
                                        $resumeArray[get_the_ID()]['catedidatvideoid'] = $catedidatvideoid;
                                        //$x++;
                                    }
                                }else if(!$filterbyseen && $filterbyrating){
                                    if($_GET['rating'] == $postRating){
                                        $resumeArray[get_the_ID()]['post_id'] = get_the_ID();
                                        $resumeArray[get_the_ID()]['jobAppPostId'] = $jobAppPostId;
                                        $resumeArray[get_the_ID()]['catedidatvideoid'] = $catedidatvideoid;
                                       // $x++;
                                    }                                    
                                }else if($filterbyseen && $filterbyrating){                                    
                                    if($postseen == $_GET['postseen'] && $_GET['rating'] == $postRating){
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


                            //echo '<pre>';
                            //print_r($resumeArray);
                            
                            $postids = array_keys($resumeArray);
                            
                            $pids = array();
                            foreach($postids as $pid){
                                $pids[] = $pid;
                            }

                            //print_r($pids);

                            //echo '</pre>';

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
                                    ?>                                    

                                <div class="item" style="width: 80%; background: #f7f7f7">
                                    <div class="resume-aside">
                                       <div class="top-bar-sec"> 
                                         <div class="top-bar-sec-left"> 
                                            <?php the_candidate_photo(); ?>
                                           <div class="sec-detail">
                                            <p class="job-title"><?php the_candidate_title(); ?></p>
                                            <p class="location"><?php the_candidate_location(); ?></p>
                                          </div>
                                        </div>
                                         <div class="top-bar-sec-right"> 
                                             <?php the_resume_links(); ?>
                                         </div>
                                       </div>
                                       <?php if(!isset($_GET['skills'])){ ?>
                                       <?php if($i == 1){ ?>
                                       <div id="candidateVideo">
                                        <?php } ?>
                                        <?php the_candidate_video(); ?>
                                         <?php if($i == 1){ ?>
                                        </div>
                                        <?php } 
                                        }else{ ?> 

                                        <div class="owl-carousel2 owl-theme">
                                         <div class="item">
                                            <?php if($i == 1){ ?>
                                            <div id="candidateVideo">
                                            <?php } ?>
                                                <?php the_candidate_video(); ?>
                                            <?php if($i == 1){ ?>
                                            </div>
                                            <?php } ?>                            

                                            <div class="reratcand">  
                                            <?php $candEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                            $candidateEmail = get_post_meta($post->ID,'_candidate_email', true); 

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

                                        if($filterbyskills){
                                            $filterkill = strtolower($_GET['skills']);
                                            $filterkillArr = explode(",",$filterkill);

                                            $resumeLanguages = get_post_meta($post->ID,'_resume_languages', true);
                                            foreach ($resumeLanguages as $key => $langVideo) { 

                                               $langName = strtolower($langVideo);

                                               if(in_array($langName, $filterkillArr)){
                                                    $langVideoVal = get_post_meta($post->ID, '_'.$langName.'_video', true );
                                                    $langvideoArr = explode('/', $langVideoVal);
                                                    $langvideoArrIndex = count($langvideoArr) - 2;
                                                    $langvideoid = $langvideoArr[$langvideoArrIndex];
                                                    if($langvideoid != ''){
                                                    ?>
                                                    <div class="item">
                                                        <ziggeoplayer ziggeo-video="<?php echo  $langvideoid; ?>" ziggeo-width=100% ziggeo-theme="modern" ziggeo-themecolor="red"> </ziggeoplayer>

                                                    <div class="reratcand">  
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
                                                    }
                                               }
                                            }
                                        }
                                        
                                        ?>
                                        </div>
                                    <?php } ?> 
                                    </div> 
                                    <?php if(!isset($_GET['skills'])){ ?>
                                    <div class="reratcand">  
                                    <?php $candEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                    $candidateEmail = get_post_meta($post->ID,'_candidate_email', true); 

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
                                    <?php } ?>     
                                    <?php if ( ( $skills = wp_get_object_terms( $post->ID, 'resume_skill', [ 'fields' => 'names' ] ) ) && is_array( $skills ) ) : ?>
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
           
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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
                alert('Rate Update Successfully');
               //jQuery("#vote_counter").html(response.vote_count)
            }
         }
      })   

   })
    /** Stop infitay loop in carousel ***/
/*    jQuery('.carousel').carousel({
        wrap: false
    });  
*/
    /*** Next slide auto play video ***/
/*    jQuery('.carousel').on('slide.bs.carousel', function () {
        setTimeout(function() {
            var vid = jQuery('.carousel-item.active').find('ziggeoplayer').attr('ziggeo-video');
            //console.log(vid);
            jQuery(".ba-player-playbutton-button").trigger('click');      

        },1000);
    })*/
    /*** Firt time auto play video ***/
    setTimeout(function() {
        jQuery("#candidateVideo .ba-player-playbutton-button").trigger('click');
        jQuery("#candidateVideo .ba-player-theme-modern-button-inner").trigger('click');
    },2000); 

});    
</script>

<script>
    jQuery(document).ready(function() {
        var owl = jQuery('#firstslider');
        owl.owlCarousel({
            margin: 10,
            nav: true,
            loop: false,
            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });

        var owl2 = jQuery('.owl-carousel2');
        owl2.owlCarousel({
            margin: 10,
            nav: true,
            loop: false,
            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })

        
        /*jQuery('.owl-carousel2 .owl-next').click(function() {
             //console.log('ddddddddddd'); /            
                  
             setTimeout(function() {
                var vid = jQuery('.owl-item.active').find('ziggeoplayer').attr('ziggeo-video');
                console.log(vid);
                if(vid != ''){   
                    jQuery(".owl-item.active .ba-player-playbutton-button").trigger('click');
                }
            },1000);
            
        });  */ 

        /*owl2.on('click', '.owl-next', function() {
          owl2.trigger('next.owl.carousel', [300]);
        }); 
*/
        jQuery('body').on('click', '.owl-next', function(){
            //console.log('ffffffffff');
            $this = jQuery(this);

            setTimeout(function() {
                if($this.parent().parent().hasClass('owl-carousel')){
                    var vid = $this.parent().parent().find('.owl-item.active:first-child ziggeoplayer').attr('ziggeo-video')
                    console.log(vid);
                    if(vid != ''){   
                        $this.parent().parent().find('.owl-item.active:first-child .ba-player-playbutton-button').trigger('click');
                    }
                }else{
                    var vid = $this.parent().parent().find('.owl-item.active ziggeoplayer').attr('ziggeo-video')
                    console.log(vid);
                    if(vid != ''){   
                        $this.parent().parent().find('.owl-item.active .ba-player-playbutton-button').trigger('click');
                    }
                }
                
            },1000);
        })


    })
</script>
<?php get_footer(); ?>