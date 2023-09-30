<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function ($) {
            //Token Input
            ";
            $multicities = "var multicities = '';";
            if(isset(wpjobportal::$_data['filter']['city'])) 
                if(!(wpjobportal::$_data['filter']['city'] == "[]")) 
                    $multicities = "var multicities = ". wpjobportal::$_data['filter']['city'].";";
            
            $inline_js_script .= $multicities;
            $inline_js_script .= "
            getTokenInput(multicities);
             jQuery('a.sort-icon').click(function (e) {
                e.preventDefault();
                changeSortBy();
            });

        });
        //Token in put
        function getTokenInput(multicities) {
            var cityArray = '". admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname")."';
            jQuery('#city').tokenInput(cityArray, {
                theme: 'wpjobportal',
                preventDuplicates: true,
                prePopulate: multicities,
                hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal')) ."\",
                noResultsText: \"". esc_html(__('No Results', 'wp-job-portal'))."\",
                searchingText: \"". esc_html(__('Searching', 'wp-job-portal'))."\"
            });
            jQuery('#wpjobportal-input-city').attr('placeholder', \"". esc_html(__('Type city:', 'wp-job-portal'))."\");
        }

        function closePopupJobManager() {
            closePopupForTemplate();
        }

        function closePopupJobHub() {
            closePopupForTemplate()
        }

        function closePopupForTemplate() {
            jQuery('div#'+common.theme_chk_prefix+'-popup').slideUp('slow');
            setTimeout(function () {
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').hide();
                //jQuery('#'+common.theme_chk_prefix+'-modal-ar-title').html('');
                jQuery('div#'+common.theme_chk_prefix+'-popup').css('display', 'none');
                /*jQuery('span#popup_coverletter_title.coverletter').html('');
                jQuery('span#popup_coverletter_desc.coverletter').html('');*/
            }, 700);

        }

        function changeSortBy() {
            var value = jQuery('a.sort-icon').attr('data-sortby');
            var img = '';
            if (value == 1) {
                value = 2;
                img = jQuery('a.sort-icon').attr('data-image2');
            } else {
                img = jQuery('a.sort-icon').attr('data-image1');
                value = 1;
            }
            jQuery('img#sortingimage').attr('src', img);
            jQuery('input#sortby').val(value);
            jQuery('form#resume_form').submit();
        }
        function changeCombo() {
            jQuery('input#sorton').val(jQuery('select#sorting').val());
            changeSortBy();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
