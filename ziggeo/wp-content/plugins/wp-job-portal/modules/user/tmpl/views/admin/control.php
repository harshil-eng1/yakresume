<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job Controll
*/
?>
<?php
$html = '';
switch ($layout) {
	case 'usercontrol':
		?>
		<div class="wpjobportal-user-action-wrp">
		    <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=enforcedeleteuser&wpjobportalid='.$user->id); ?>" onclick='return confirm("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'; ?>");' title="<?php echo esc_html(__('enforce delete', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Enforce Delete', 'wp-job-portal')) ?>
		    </a>
		    <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=deleteuser&wpjobportalid='.$user->id); ?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
		    </a>
		    <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=changeuserstatus&wpjobportalid='.$user->id); ?>" title="<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>">
		    	<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>
		    </a>
		    <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&wpjobportallt=changerole&wpjobportalid='.$user->id); ?>" title="<?php echo esc_html(__('change role', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Change Role', 'wp-job-portal')) ?>
		    </a>
		</div>

<?php
		break;
	case 'userdetailcontrol':
		?>
		<div class="wpjobportal-user-action-wrp">
            <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=enforcedeleteuser&wpjobportalid='.$user->id); ?>" onclick='return confirm("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'; ?>");' title="<?php echo esc_html(__('enforce delete', 'wp-job-portal')) ?>">
            	<?php echo esc_html(__('Enforce Delete', 'wp-job-portal')) ?>
            </a>
            <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=deleteuser&wpjobportalid='.$user->id); ?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>">
            	<?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
            </a>
            <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=changeuserstatus&wpjobportalid='.$user->id); ?>&detail=1" title="<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>">
            	<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>
            </a>
            <a class="wpjobportal-user-act-btn" href="<?php echo admin_url('admin.php?page=wpjobportal_user&wpjobportallt=changerole&wpjobportalid='.$user->id); ?>" title="<?php echo esc_html(__('change role', 'wp-job-portal')) ?>">
            	<?php echo esc_html(__('Change Role', 'wp-job-portal')) ?>
            </a>
        </div>

		<?php
		break;
	
	default:
		# code...
		break;
}
?>