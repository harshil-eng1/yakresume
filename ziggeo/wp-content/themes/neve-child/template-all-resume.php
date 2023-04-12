<?php
/*Template Name: View All Resume Candidate*/
get_header();
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<?php 
$metaquery = array();
if($_GET['jobId']){
    $jobPostId = $_GET['jobId'];
    $skillLang = get_post_meta( $jobPostId, '_skill_language', true );
    
    $metaquery['relation'] = 'OR';
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

                <div class="col-12">
                    <div id="rateCandidateCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Slide Indicators -->             
                        <div class="carousel-inner" role="listbox">
                            <?php 
                            $args = array(
                                'post_type'  => 'resume',
                                'post_status' => 'publish',
                                'posts_per_page' => -1, 
                                'order'      => 'ASC',
                                'meta_query' => $metaquery,
                            );
                            $query = new WP_Query( $args );                            
                            $i = 1;
                            while ( $query->have_posts() ) : $query->the_post(); ?>
                            <!-- Slide -->

                            <div class="carousel-item <?php if($i==1){ ?>active<?php } ?>">
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
                                    <?php the_candidate_video(); ?>
                                </div>

                                <div class="reratcand">  
                                <?php $candEmail = get_post_meta($post->ID,'_candidate_email', true); 

                                $candidateEmail = get_post_meta($post->ID,'_candidate_email', true); 
                                $ApplicatepostID = getApplicantRating($candidateEmail, $_GET['jobId']);
                                   
                                $rating = get_post_meta($ApplicatepostID,'_rating', true); 

                                if($rating == 1){ $selected1 = 'selected'; }else{ $selected1 = ''; }
                                if($rating == 2){ $selected2 = 'selected'; }else{  $selected2 = '' ;}
                                if($rating == 3){ $selected3 = 'selected'; }else{  $selected3 = '';}
                                if($rating == 4){ $selected4 = 'selected'; }else{  $selected4 = '';}
                                if($rating == 5){ $selected5 = 'selected'; }else{  $selected5 = '';}
                                ?>                                  
                                    <h2><?php _e( 'Rate a Candidate', 'wp-job-manager-resumes' ); ?></h2>
                                    <a href="javascript:void(0)"  class="resRateCandid <?php echo $selected1 ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="1" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $_GET['jobId']; ?>">1</a>
                                    <a href="javascript:void(0)" class="resRateCandid <?php echo $selected2 ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="2" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $_GET['jobId']; ?>">2</a>
                                    <a href="javascript:void(0)" class="resRateCandid <?php echo $selected3 ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="3" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $_GET['jobId']; ?>">3</a>
                                    <a href="javascript:void(0)" class="resRateCandid <?php echo $selected4 ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="4" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $_GET['jobId']; ?>">4</a>
                                    <a href="javascript:void(0)" class="resRateCandid <?php echo $selected5 ?> resume_<?php echo $post->ID; ?>" data-rate_candidate="5" data-post_id="<?php echo $post->ID; ?>" data-email_candidate="<?php echo $candEmail; ?>" data-jobId_candidate="<?php echo $_GET['jobId']; ?>">5</a>
                                
                                </div>  

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

                                    
                            </div>
                            <?php
                            /******** Post Type 'job_application' Insert Post Custom Query *********/
                            if($_GET['jobId']){                               
                                
                                $queryPost = $wpdb->prepare(
                                    'SELECT post_author, post_parent FROM ' . $wpdb->posts . '
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
                                        add_post_meta($jobAppPostId, '_job_appliedID', $_GET['jobId']);
                                    }

                                }
                            }

                            $i++; endwhile;
                            wp_reset_postdata();
                            ?>      
           
                            <!-- Slider pre and next arrow -->
                            <div class="bottom-arrow">
                                <a class="carousel-control-prev text-white" href="#rateCandidateCarousel" role="button" data-slide="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <a class="carousel-control-next text-white" href="#rateCandidateCarousel" role="button" data-slide="next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                           </div>
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
    console.log(rID);
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

      jQuery.ajax({
         type : "post",
         //dataType : "json",
         url : '<?php echo admin_url( 'admin-ajax.php' );?>',
         data : {action: "sp_resumeRateCandidate", post_id : post_id, rate_candidate: rate_candidate, email_candidate : email_candidate, jobId_candidate: jobId_candidate},
         success: function(res) {
            //console.log(res);
            if(res){
                //console.log('asdfdsf');
                alert('Rate a Candidate Update Successfully');
               //jQuery("#vote_counter").html(response.vote_count)
            }
         }
      })   

   })

});    
</script>
<?php get_footer(); ?>
