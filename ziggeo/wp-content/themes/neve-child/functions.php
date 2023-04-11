<?php
/**
 * Extra files & functions are hooked here.
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package Avada
 * @subpackage Core
 * @since 1.0
 */
 /* enqueue scripts and style from parent theme */

/*function neve_child_styles() {
    wp_enqueue_style( 'Neve-child-style', get_stylesheet_uri(),
    array( 'Neve' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'neve_child_styles');*/

add_action('wp_enqueue_scripts', 'neve_child_theme');
function neve_child_theme()
{
      wp_enqueue_style('parent-stylesheet', get_stylesheet_uri(),'/neve/style.css');
      wp_enqueue_style('child-stylesheet', get_stylesheet_uri(),'/neve-child/style.css');
      //wp_enqueue_script('child-scripts', '/neve-child/js/view.js', array('jquery'), '6.1.1', true);
}

/*********** Custom RSPL ***********/

function wpdocs_theme_name_scripts() {       
    wp_localize_script( 'custom_script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

/**
 * Add custom meta box Resume 
 * 
 */
add_action( 'add_meta_boxes', 'sp_resume_rate_candidate_meta_box' ); 
function sp_resume_rate_candidate_meta_box(){	 
	add_meta_box( 'rate-candidate-meta-box', __( 'Resume Rate Candidate', 'neve' ), 'resume_rate_candidate_metabox','resume', 'normal',  'low' );   
} 

/**
 * resume_rate_candidate_metabox Metabox
 */
function resume_rate_candidate_metabox(){ 
	global $post;  
	$resume_rate_candidate = get_post_meta($post->ID,'_resume_rate_candidate', true); 
	wp_nonce_field( 'sp_nonce', 'sp_nonce_field' ); 
	?> 
	<table class="WSMetabox"> 
		<tr><th><?php _e('Resume Rate Candidate','neve');?></th></tr> 
		<tr>
	    	<td>
	    		 <input type="number" name="resume_rate_candidate"  value="<?php echo $resume_rate_candidate ?>" />   
	    	</td>
		</tr>
	</table> 
	<?php  
}

/**
 * Save product_public_year_metabox metabox
 * 
 */
add_action( 'save_post', 'sp_resume_rate_candidate_save_meta_box' );
function sp_resume_rate_candidate_save_meta_box($post_id){ 

    $post_types = array('resume');  

    $post_type = get_post_type($post_id);

    if(!isset($_POST['sp_nonce_field']) || !wp_verify_nonce($_POST[ 'sp_nonce_field' ], 'sp_nonce')  || !current_user_can( 'edit_post', $post_id ) || !in_array($post_type,$post_types) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ){
        return;
    } 

    if(isset($_POST['resume_rate_candidate']) && $post_type=='resume'){ 
       update_post_meta($post_id,'_resume_rate_candidate',$_POST['resume_rate_candidate']); 
    } 
    
}

/****** Custom Post type update resume******/

add_action("wp_ajax_sp_resumeRateCandidate", "sp_resumeRateCandidate");
add_action("wp_ajax_nopriv_sp_resumeRateCandidate", "sp_resumeRateCandidate");

function sp_resumeRateCandidate() {
	$res_post_id = $_POST['post_id'];
	$rateCandidVal = $_POST['rate_candidate'];
	$emailCandid = $_POST['email_candidate'];
	$candidJobId = $_POST['jobId_candidate'];

	if($candidJobId && $rateCandidVal){

		$args = array(
		    'post_type'  => 'job_application',
		    'post_status' => 'new',
		    'meta_query' => array(
		        'relation' => 'AND', // "OR" or "AND" (default)
		        array(
		            'key' => '_candidate_email',
		            'value' => $emailCandid,
		        ),
		        array(
		            'key' => '_job_appliedID',
		            'value' => $candidJobId,
		            'compare' => 'IN',
		        )
		    )
		);
		$argsQuery = new WP_Query( $args );

		foreach ($argsQuery->posts as $key => $value) {			
			update_post_meta($value->ID,'_rating',$rateCandidVal);
		}
	}

	if($res_post_id && $rateCandidVal ){		
		update_post_meta($res_post_id,'_resume_rate_candidate',$rateCandidVal);
		echo 'success';
	}
	die;
}

function getApplicantRating($emailCandid, $candidJobId){
		$args = array(
		    'post_type'  => 'job_application',
		    'post_status' => 'new',
		    'meta_query' => array(
		        'relation' => 'AND', // "OR" or "AND" (default)
		        array(
		            'key' => '_candidate_email',
		            'value' => $emailCandid,
		        ),
		        array(
		            'key' => '_job_appliedID',
		            'value' => $candidJobId,
		            'compare' => 'IN',
		        )
		    )
		);
		$argsQuery = new WP_Query( $args );

		foreach ($argsQuery->posts as $key => $value) {			
			return $value->ID;
		}

}
?>