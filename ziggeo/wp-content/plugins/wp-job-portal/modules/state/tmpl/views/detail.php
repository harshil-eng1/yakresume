<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
*
*/
?>
<tr>
    <td>
        <input type="checkbox" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($row->id); ?>" />
    </td>
    <td>
        <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_html(__('name','wp-job-portal')); ?>">
            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($row->name)); ?>
        </a>
    </td>
    <td>
        <?php if ($row->enabled == '1') { ?>
	        <a href="<?php echo admin_url('admin.php?page=wpjobportal_state&task=unpublish&action=wpjobportaltask&wpjobportal-cb[]='.$row->id.$pageid); ?>" title="<?php echo esc_html(__('published', 'wp-job-portal')); ?>">
	            <img src="<?php echo WPJOBPORTAL_PLUGIN_URL; ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('published', 'wp-job-portal')); ?>" />
	        </a>
       <?php } else { ?>
	        <a href="<?php echo admin_url('admin.php?page=wpjobportal_state&task=publish&action=wpjobportaltask&wpjobportal-cb[]='.$row->id.$pageid); ?>" title="<?php echo esc_html(__('not published', 'wp-job-portal')); ?>">
	            <img src="<?php echo WPJOBPORTAL_PLUGIN_URL; ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('not published', 'wp-job-portal')); ?>" />
	        </a>
		<?php } ?>
    </td>
    <td>
        <a href="<?php echo admin_url('admin.php?page=wpjobportal_city&stateid='.$row->id.'&countryid='.$row->countryid); ?>" title="<?php echo esc_html(__('cities', 'wp-job-portal')); ?>">
            <?php echo esc_html(__('Cities', 'wp-job-portal')); ?>
        </a>
    </td>
    <td>
        <a class="wpjobportal-table-act-btn" href="<?php echo esc_url($link); ?>" title="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
            <img src="<?php echo WPJOBPORTAL_PLUGIN_URL; ?>includes/images/control_panel/dashboard/edit.png" alt="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
        </a>
        <a class="wpjobportal-table-act-btn" href="<?php echo wp_nonce_url(admin_url('admin.php?page=wpjobportal_state&task=remove&action=wpjobportaltask&wpjobportal-cb[]='.$row->id),'delete-state'); ?>" onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
            <img src="<?php echo WPJOBPORTAL_PLUGIN_URL; ?>includes/images/control_panel/dashboard/delete.png" alt="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
        </a>
    </td>
</tr>
