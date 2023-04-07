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
/*$filter = $_GET['filter'];
$args1 = array(
    'post_type' => 'resume',
    'post_status' => 'publish',
    'posts_per_page' => -1, 
    'order' => 'ASC', 
    if($filter){
        'meta_query'      => array(
            array(
              'key'         => '_candidate_title',
              'value'       => $filter,
              'compare'     => 'LIKE',
            ),
        )
    }

);
$query1 = new WP_Query( $args1 ); 

echo "<pre>";
print_r($query1);*/
?>



<div class="single-resume-content">
    <!-- rateCandidates Section -->
    <section id="rateCandidate">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div id="rateCandidateCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Slide Indicators -->
                            <?php 
                            $args1 = array(
                                'post_type' => 'resume',
                                'post_status' => 'publish',
                                'posts_per_page' => -1, 
                                'order' => 'ASC', 
                            );
                            $query1 = new WP_Query( $args1 );                            
                            $j = 0;
                            ?>                        
                            <ol class="carousel-indicators">
                                <?php while ( $query1->have_posts() ) : $query1->the_post(); ?>
                                <li data-target="#rateCandidateCarousel" data-slide-to="<?php echo $j; ?>" <?php if($i==0){ ?>class="active" <?php } ?> ></li>   
                                <?php
                                $j++; endwhile;
                                wp_reset_postdata();
                                ?>                                                                      
                            </ol>
   
                        <div class="carousel-inner" role="listbox">
                            <?php 
                            $args = array(
                                'post_type' => 'resume',
                                'post_status' => 'publish',
                                'posts_per_page' => -1, 
                                'order' => 'ASC', 
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
                                    <h2><?php _e( 'Rate a Candidate', 'wp-job-manager-resumes' ); ?></h2>
                                    <span><a href="javascript:void(0)" class="resRateCandid" data-rate_candidate="1" data-post_id="<?php echo $post->ID; ?>">1</a></span>
                                    <span><a href="javascript:void(0)" class="resRateCandid" data-rate_candidate="2" data-post_id="<?php echo $post->ID; ?>">2</a></span>
                                    <span><a href="javascript:void(0)" class="resRateCandid" data-rate_candidate="3" data-post_id="<?php echo $post->ID; ?>">3</a></span>
                                    <span><a href="javascript:void(0)" class="resRateCandid" data-rate_candidate="4" data-post_id="<?php echo $post->ID; ?>">4</a></span>
                                    <span><a href="javascript:void(0)" class="resRateCandid" data-rate_candidate="5" data-post_id="<?php echo $post->ID; ?>">5</a></span>
                                
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
      e.preventDefault(); 
      var post_id = jQuery(this).attr("data-post_id");
      var rate_candidate = jQuery(this).attr("data-rate_candidate");

      jQuery.ajax({
         type : "post",
         //dataType : "json",
         url : '<?php echo admin_url( 'admin-ajax.php' );?>',
         data : {action: "sp_resumeRateCandidate", post_id : post_id, rate_candidate: rate_candidate},
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
