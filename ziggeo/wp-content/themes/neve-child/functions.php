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
 * Save sp_resume_rate_candidate_save_meta_box metabox
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

/****** Custom Post type 'Resume' update Rating ******/

add_action("wp_ajax_sp_resumeRateCandidate", "sp_resumeRateCandidate");
add_action("wp_ajax_nopriv_sp_resumeRateCandidate", "sp_resumeRateCandidate");
function sp_resumeRateCandidate() {
	$res_post_id = $_POST['post_id'];
	$rateCandidVal = $_POST['rate_candidate'];
	$emailCandid = $_POST['email_candidate'];
	$candidJobId = $_POST['jobId_candidate'];

	//echo $res_post_id." res_post_id".' === '. $rateCandidVal." rateCandidVal".' === '.$emailCandid." emailCandid".' === '. $candidJobId." candidJobId".' === ';
 	$data_lang = $_POST['data_lang'];

	if($candidJobId && $rateCandidVal){
		$appliID = getApplicantRating($emailCandid, $candidJobId);
		if($data_lang){
				$data_langl = strtolower($data_lang);
				$language_ratings = get_post_meta($appliID, '_language_ratings' , true);
				$language_ratingsArr = json_decode($language_ratings, true);
				$language_ratingsArr[$data_langl] = $rateCandidVal;

				$language_ratingsJ = json_encode($language_ratingsArr);
				update_post_meta($appliID,'_language_ratings', $language_ratingsJ);
				echo 'success';
		}else{
				update_post_meta($appliID,'_rating',$rateCandidVal);
			if($res_post_id && $rateCandidVal ){		
				update_post_meta($res_post_id,'_resume_rate_candidate',$rateCandidVal);
				echo 'success';
			}

		}
	}
	die;
}


/****** Custom Post type 'job_application' Candidate video seen update ******/

add_action("wp_ajax_sp_catedidatvideoseen", "sp_catedidateVideoSeen");
add_action("wp_ajax_nopriv_sp_catedidatvideoseen", "sp_catedidateVideoSeen");

function sp_catedidateVideoSeen() {
	$jobApp_post_id = $_POST['japost_id'];
	if($jobApp_post_id){		
		update_post_meta($jobApp_post_id,'_candidateVideoSeen', 'seen');
		echo 'success';
	}
	die;
}

/******* Get job application Post Id ********/
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
// hide_menu_for_candidate
add_action( 'wp','hide_menu_for_candidate');
function hide_menu_for_candidate(){
	$user = wp_get_current_user();
	if(in_array('candidate',$user->roles)){
		echo "<style>#menu-item-55{ display:none; }</style>";
	}
	//print_r($user);	
}

/****** New Item add in Menu Login/LogOut ******/

add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'primary') {
        $items .= '<li><a href="'. wp_logout_url(home_url()) .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
        $items .= '<li><a href="'.wp_login_url(home_url()).'">Log In</a></li>';
    }
    return $items;
}

/****** Remove Admin Bar ******/

add_action('after_setup_theme', 'remove_admin_bar_sp');
function remove_admin_bar_sp() {
	if (!current_user_can('administrator') && !is_admin()) {
	  show_admin_bar(false);
	}
}

/**** Login page Logo Url Change ****/
add_filter('login_headerurl', 'custom_loginlogo_url');
function custom_loginlogo_url($url) {
     return home_url();
}

/***** Login page Logo Image Change ******/

function sp_custom_login_logo() { ?>
<style type="text/css">
#login h1 a, .login h1 a {
background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/img/ziggeo-logo.png);
height:65px;
width:320px;
background-size: 320px 65px;
background-repeat: no-repeat;
padding-bottom: 30px;
}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'sp_custom_login_logo' );

/***** Change Mail From Name ******/
add_filter('wp_mail_from_name', 'custom_wp_mail_from_name');
function custom_wp_mail_from_name($original_email_from) {
    return 'Yak Resume';
}
