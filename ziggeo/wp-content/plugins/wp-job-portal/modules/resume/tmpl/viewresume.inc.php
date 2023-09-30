<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<?php
    $mappingservices = wpjobportal::$_config->getConfigValue('mappingservice');
    if($mappingservices == "gmap"){
        $filekey = WPJOBPORTALincluder::getJSModel('common')->getGoogleMapApiAddress();
        //echo $filekey;
        wp_enqueue_script( 'google-map', $filekey, array(), '', false );
    ?>
    <?php }elseif ($mappingservices == "osm") {
        wp_enqueue_script('wpjobportal-ol-script', WPJOBPORTAL_PLUGIN_URL . 'includes/js/ol.min.js');
        wp_enqueue_style('wpjobportal-ol-style', WPJOBPORTAL_PLUGIN_URL . 'includes/css/ol.min.css');
    }

if(isset(wpjobportal::$_data[0])){

    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        var ajaxurl = \"" . admin_url('admin-ajax.php') ."\";
        jQuery(document).ready(function(){
            var print_link = document.getElementById('print-link');
            if (print_link) {
                var href = \"" . wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'printresume', 'wpjobportalid'=>wpjobportal::$_data[0]['personal_section']->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) ."\";
                print_link.addEventListener('click', function (event) {
                    print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
                    event.preventDefault();
                }, false);
            }
        });
        function showPopupAndSetValues() {
            jQuery('div#full_background').show();
            jQuery('div#popup-main-outer.coverletter').show();
            jQuery('div#popup-main.coverletter').slideDown('slow');
            jQuery('div#full_background').click(function () {
                closePopup();
            });
            jQuery('img#popup_cross').click(function () {
                closePopup();
            });
        }
        function disablefields(){
            alert('abc');
        }

        function closePopup() {
            var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            if(themecall == 0){
                jQuery('div#popup-main-outer').slideUp('slow');
                setTimeout(function () {
                    jQuery('div#full_background, div#wjportal-popup-background').hide();
                    jQuery('div#popup-main').hide();
                }, 700);
            } else {
                jQuery('div.'+common.theme_chk_prefix+'-popup-wrp').slideUp('slow');
                setTimeout(function () {
                    jQuery('div#'+common.theme_chk_prefix+'-popup-background').hide();
                    jQuery('div.'+common.theme_chk_prefix+'-popup-wrp').hide();
                }, 700);
            }
        }

        function sendMessage() {
            var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
            var uid = " . wpjobportal::$_data[0]['personal_section']->uid.";
            var resumeid = '". wpjobportal::$_data[0]['personal_section']->id."';
            var subject = jQuery('input#subject').val();
            if (subject == '') {
                alert(\"" . esc_html(__("Please fill the subject", 'wp-job-portal'))."\");
                return false;
            }

            is_tinyMCE_active = false;
            if (typeof(tinyMCE) != 'undefined') {
              if (tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false) {
                is_tinyMCE_active = true;
              }
            }

            if (is_tinyMCE_active == false) {
                var message = tinyMCE.get('jobseekermessage').getContent();
            } else {
                var message = jQuery('textarea#jobseekermessage').val();
            }
            if (message == '') {
                alert(\"" . esc_html(__("Please fill the message", 'wp-job-portal'))."\");
                return false;
            }
            jQuery.post(ajaxurl, {action: \"wpjobportal_ajax\", wpjobportalme: \"message\", task: \"sendmessageresume\", subject: subject, message: message, resumeid: resumeid, uid: uid, '_wpnonce':'". esc_attr(wp_create_nonce("send-message-resume"))."'}, function (data) {
                if (data) {
                    alert(\"" . esc_html(__("Message sent", 'wp-job-portal'))."\");
                    if(null != themecall){
                        closePopupJobManager();
                    }else{
                        closePopup();
                    }
                }else{
                    alert(\"" . esc_html(__("Message not sent", 'wp-job-portal'))."\");
                }
            });
        }

        function sendMessageJobseeker() {
            var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            if(themecall == 0){
                jQuery('div#wjportal-popup-background').show();
                jQuery('div#popup-main-outer.sendmessage').show();
                jQuery('div#popup-main.sendmessage').slideDown('slow');
                jQuery('div#wjportal-popup-background').click(function () {
                    closePopup();
                });
                jQuery('img#wjportal-popup-close-btn').click(function () {
                    closePopup();
                });
            }else{
                jQuery('div.'+common.theme_chk_prefix+'-popup-wrp').slideDown('slow');
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').show();
                jQuery('div#'+common.theme_chk_prefix+'-popup-wrp').slideDown();
                jQuery('.'+common.theme_chk_prefix+'-popup-close-icon').click(function () {
                    closePopup(1);
                });
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').click(function () {
                    closePopup(1);
                });
            }
        }

          function getPackagePopup(resumeid) {
            var ajaxurl = \"" . admin_url('admin-ajax.php') ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'getPackagePopupForResumeContactDetail', wpjobportalid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-package-popup-for-resume-contact-detail"))."'}, function (data) {
                if (data) {
                    if(jQuery('#package-popup').length)
                    jQuery('#package-popup').remove();
                    jQuery('body').append(data);
                    jQuery('#wjportal-popup-background').show();
                    jQuery('#package-popup').slideDown('slow');

                } else {
                    jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error While Adding Feature job', 'wp-job-portal'))."\");
                }
            });
        }


        function selectPackage(packageid){
            jQuery('.package-div').css('border','1px solid #ccc');
            jQuery('.wjportal-pkg-item, .wpj-jp-pkg-item').removeClass('wjportal-pkg-selected');
            jQuery('#package-div-'+packageid).addClass('wjportal-pkg-selected');
            jQuery('#wpjobportal_packageid').val(packageid);
            jQuery('#jsre_featured_button').removeAttr('disabled');
        }
         osmMap = null;
         function initialize(lat, lang, div) {
            ";
            if($mappingservices == "gmap"){
                $inline_js_script .= "
                        var myLatlng = new google.maps.LatLng(lat, lang);
                        var myOptions = {
                            zoom: 8,
                            center: myLatlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        }
                        var map = new google.maps.Map(document.getElementById(div), myOptions);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: myLatlng
                        });";
           }elseif ($mappingservices == "osm") { 
                $inline_js_script .= "
                    var coordinate = [parseFloat(lang),parseFloat(lat)];
                    if(!osmMap){
                        osmMap = new ol.Map({
                            target: div,
                            layers: [
                                new ol.layer.Tile({
                                    source: new ol.source.OSM()
                                })
                            ],
                        });
                    }
                    osmMap.setView(new ol.View({
                        center: ol.proj.fromLonLat(coordinate),
                        zoom: 20
                    }));
                    osmAddMarker(osmMap, coordinate);
                    ";
            }
            $inline_js_script .= "
        }

        jQuery(document).ready(function () {
            jQuery('div.resume-map div.row-title').click(function (e) {
                e.preventDefault();
                var img1 = '". WPJOBPORTAL_PLUGIN_URL . "includes/images/resume/show-map.png';
                var img2 = '". WPJOBPORTAL_PLUGIN_URL . "includes/images/resume/hide-map.png';
                var pdiv = jQuery(this).parent();
                var mdiv = jQuery(pdiv).find('div.row-value');
                if (jQuery(mdiv).css('display') == 'none') {
                    jQuery(mdiv).show();
                    jQuery(this).find('img').attr('src', img2);
                } else {
                    jQuery(mdiv).hide();
                    jQuery(this).find('img').attr('src', img1);
                }
            });
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php if(wpjobportal::$theme_chk == 1) { ?>
<?php
    $inline_js_script = "
        jQuery(document).ready(function(){
            jQuery('#proceedPaymentBtn').click(function(){
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').show();
                jQuery('#payment-popup').slideDown('slow');
            });
            jQuery('div#'+common.theme_chk_prefix+'-popup-background, .'+common.theme_chk_prefix+'-popup-close-icon').click(function(){
                jQuery('div#wjportal-popup-background').hide();
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').hide();
                jQuery('#payment-popup').slideUp('slow');
            });
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php } else {?>
<?php
    $inline_js_script = "
        jQuery(document).ready(function(){
            jQuery('#proceedPaymentBtn').click(function(){
                jQuery('div#wjportal-popup-background').show();
                jQuery('#payment-popup').slideDown('slow');
            });
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php }
} ?>
