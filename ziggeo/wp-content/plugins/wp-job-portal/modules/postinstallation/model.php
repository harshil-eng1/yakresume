<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALPostinstallationModel {

    function updateInstallationStatusConfiguration(){
            $flag = get_option('wpjobportal_post_installation');
            if($flag == false){
                add_option( 'wpjobportal_post_installation', '1', '', 'yes' );
            }else{
                update_option( 'wpjobportal_post_installation', '1');
            }
    }

    function storeconfigurations($data){
        if (empty($data))
            return false;
        $error = false;
        unset($data['action']);
        unset($data['form_request']);
        unset($data['step']);
        foreach ($data as $key => $value) {
            $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET `configvalue` = '" . $value . "' WHERE `configname`= '" . $key . "'";
            if (!wpjobportaldb::query($query)) {
                $error = true;
            }
        }

        if ($error)
            return WPJOBPORTAL_SAVE_ERROR;
        else
            return WPJOBPORTAL_SAVED;
    }

    function getConfigurationValues(){
        $this->updateInstallationStatusConfiguration();
        $query = "SELECT configvalue,configname  FROM`" . wpjobportal::$_db->prefix . "wj_portal_config`";
        $data = wpjobportaldb::get_results($query);
        $config_array = array();
        foreach ($data as $config) {
            if($config->configname == 'offline'){
                $config_array['offline']=$config->configvalue;
            }
            if($config->configname == 'title'){
                $config_array['title']=$config->configvalue;
            }
            if($config->configname == 'adminemailaddress'){
                $config_array['adminemailaddress']=$config->configvalue;
            }
            if($config->configname == 'mailfromaddress'){
                $config_array['mailfromaddress']=$config->configvalue;
            }
            if($config->configname == 'system_slug'){
                $config_array['system_slug']=$config->configvalue;
            }
            if($config->configname == 'disable_employer'){
                $config_array['disable_employer']=$config->configvalue;
            }
            if($config->configname == 'cur_location'){
                $config_array['cur_location']=$config->configvalue;
            }
            if($config->configname == 'companyautoapprove'){
                $config_array['companyautoapprove']=$config->configvalue;
            }
            if($config->configname == 'jobautoapprove'){
                $config_array['jobautoapprove']=$config->configvalue;
            }
            if($config->configname == 'empautoapprove'){
                $config_array['empautoapprove']=$config->configvalue;
            }
            if($config->configname == 'newdays'){
                $config_array['newdays']=$config->configvalue;
            }
            if($config->configname == 'searchjobtag'){
                $config_array['searchjobtag']=$config->configvalue;
            }
            if($config->configname == 'visitor_can_apply_to_job'){
                $config_array['visitor_can_apply_to_job']=$config->configvalue;
            }
            if($config->configname == 'visitor_can_post_job'){
                $config_array['visitor_can_post_job']=$config->configvalue;
            }
            if($config->configname == 'employerview_js_controlpanel'){
                $config_array['employerview_js_controlpanel']=$config->configvalue;
            }
            if($config->configname == 'data_directory'){
                $config_array['data_directory']=$config->configvalue;
            }
            if($config->configname == 'date_format'){
                $config_array['date_format']=$config->configvalue;
            }
            if($config->configname == 'mailfromname'){
                $config_array['mailfromname']=$config->configvalue;
            }
            if($config->configname == 'showemployerlink'){
                $config_array['showemployerlink']=$config->configvalue;
            }
            if($config->configname == 'system_have_gold_job'){
                $config_array['system_have_gold_job']=$config->configvalue;
            }
            if($config->configname == 'system_have_featured_job'){
                $config_array['system_have_featured_job']=$config->configvalue;
            }
            if($config->configname == 'allow_jobshortlist'){
                $config_array['allow_jobshortlist']=$config->configvalue;
            }
            if($config->configname == 'allow_tellafriend'){
                $config_array['allow_tellafriend']=$config->configvalue;
            }
            if($config->configname == 'employer_defaultgroup'){
                $config_array['employer_defaultgroup']=$config->configvalue;
            }
            if($config->configname == 'jobseeker_defaultgroup'){
                $config_array['jobseeker_defaultgroup']=$config->configvalue;
            }
            if($config->configname == 'default_pageid'){
                $config_array['default_pageid']=$config->configvalue;
            }
        }
        wpjobportal::$_data[0] = $config_array;

    }

    function installSampleData($insertsampledata, $jsmenu,$empmenu,$temp_data = 0) {
        $date = date('Y-m-d H:i:s');
        $curdate = date('Y-m-d H:i:s');
        $thirdydaydate = date('Y-m-d', strtotime($curdate. ' + 30 days'));
        $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('data_directory');


        if($jsmenu == 1){
            $query = "SELECT COUNT(ID) FROM ".wpjobportal::$_db->prefix."posts WHERE post_content LIKE '%[wpjobportal_jobseeker_controlpanel]%'";
            $pageexists = wpjobportal::$_db->get_var($query);
            if($pageexists == 0){
                $post = array(
                    'post_name' => 'wp-job-portal-jobseeker-controlpanel',
                    'post_title' => 'Job seeker',
                    'post_status' => 'publish',
                    'post_content' => '[wpjobportal_jobseeker_controlpanel]',
                    'post_type' => 'page'
                );
                $post_ID = wp_insert_post($post);
            }
        }
        if($empmenu == 1){
            $query = "SELECT COUNT(ID) FROM ".wpjobportal::$_db->prefix."posts WHERE post_content LIKE '%[wpjobportal_employer_controlpanel]%'";
            $pageexists = wpjobportal::$_db->get_var($query);
            if($pageexists == 0){
                $post = array(
                    'post_name' => 'wp-job-portal-employer-controlpanel',
                    'post_title' => 'Employer',
                    'post_status' => 'publish',
                    'post_content' => '[wpjobportal_employer_controlpanel]',
                    'post_type' => 'page'
                );
                $post_ID = wp_insert_post($post);
            }
        }
        if ($insertsampledata == 1) {
            // sample images zip
            $basepath = ABSPATH;
            $wp_upload_dir = wp_upload_dir();
            $url = "https://d3ewuxxxzqg2dd.cloudfront.net/sample_data.zip";
            $filepath = $basepath . "tmp/jp_sample_data.zip";
            if(!function_exists('download_url')){
               do_action('wpjobportal_load_wp_file');
            }

            if(file_exists($basepath.'tmp')){
                $this->recursiveremove($basepath.'tmp');
            }
            if(file_exists($basepath.'tmp')){
                rename($basepath.'tmp', $basepath.'tmp-rename');
            }
            $this->makeDir($basepath.'tmp');

            $tmpfile = download_url( $url);
            if(!is_wp_error($tmpfile)){
                copy( $tmpfile, $filepath );
                @unlink( $tmpfile ); // must unlink afterwards

                if (file_exists($basepath . "tmp/jp_sample_data.zip")) {
                    do_action('wpjobportal_load_wp_pcl_zip');
                    $archive = new PclZip($basepath . "tmp/jp_sample_data.zip");
                    $v_list = $archive->extract($wp_upload_dir["basedir"]."/".$data_directory."/");
                }

                // end of sample images code
            }
			$jobseeker_id = 0;
			$employer_id = 0;
			if(isset($_POST['jobseeker_id']) && is_numeric($_POST['jobseeker_id']) && $_POST['jobseeker_id'] != 0){
				/* insert new jobseeker */
				$query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "users` where ID= ". $_POST['jobseeker_id'];
				$jobseeker_data =wpjobportaldb::get_row($query);
				
				$query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` where uid= ". $_POST['jobseeker_id'];
				$jobseeker_already_exist =wpjobportaldb::get_var($query);
				if(empty($jobseeker_already_exist)){
					$insert_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_users`
								(id,uid,first_name,roleid,emailaddress,status,created)
								  VALUES('',".$_POST['jobseeker_id'].",'".$jobseeker_data->user_login."',2,'".$jobseeker_data->user_email."',1,'$date');";
					wpjobportaldb::query($insert_query);
					$jobseeker_id = wpjobportal::$_db->insert_id;
				}else{
					$jobseeker_id = $jobseeker_already_exist;
				}
			}

			if(isset($_POST['employer_id']) && is_numeric($_POST['employer_id']) && $_POST['employer_id'] != 0){
				/* insert new employer */
				$query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "users` where ID= ". $_POST['employer_id'];
				$employer_data =wpjobportaldb::get_row($query);
				
				$query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` where uid= ". $_POST['employer_id'];
				$employer_already_exist =wpjobportaldb::get_var($query);
				if(empty($employer_already_exist)){
					$insert_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_users`
								(id,uid,first_name,roleid,emailaddress,status,created)
								  VALUES('',".$_POST['employer_id'].",'". $employer_data->user_login ."',1,'". $employer_data->user_email ."',1,'$date');";
					wpjobportaldb::query($insert_query);
					$employer_id = wpjobportal::$_db->insert_id;
				}else{
					$employer_id = $employer_already_exist;
				}
			}

            /**
            *@param wp job portal
            * * Sample Data Company
            */
            // For feature vehicle addons
            $isfeaturedcompany = 0;
            $startfeatureddate = "0000-00-00 00:00:00";
            $endfeatureddate = "0000-00-00 00:00:00";
            if(in_array('featuredcompany', wpjobportal::$_active_addons)){
               $isfeaturedcompany = 1;
               $startfeatureddate = date("Y-m-d H:i:s");
               $endfeatureddate = date("Y-m-d H:i:s", strtotime("+12 months"));
            }

            if($employer_id > 0 && is_numeric($employer_id)){
                //  first company
                $cityid = '69785';// cityids for companies
                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` ( `uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ( '".$employer_id ."', 'Buruj Solution', 'buruj-solution', 'http://www.burujsolutions.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sampledata@info.com', '<p>We aligns itself with modern and advanced concepts in IT industry to help its customers by providing value added software. We performs thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid', 'WAPDA Town, Gujranwala ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$thirdydaydate."', 2, '0000-00-00 00:00:00', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid = wpjobportal::$_db->insert_id;
                    // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x1")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x1",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid);
                //Cities
                 $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid . ", " . $cityid . ");";
                wpjobportaldb::query($insert_companycity);

                //  Second Company
                $cityid1 = '69787';// cityids for companies

                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` ( `uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Joom Sky', 'joom-sky', 'http://www.joomsky.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sampledata@joomsky.com', '<p>We aligns itself with modern and advanced concepts in IT industry to help its customers by providing value added software. We performs thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid1', 'WAPDA Town, Gujranwala ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$thirdydaydate."', 2, '0000-00-00 00:00:00', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid1 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x2")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x2",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid1);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid1 . ", " . $cityid1 . ");";
                wpjobportaldb::query($insert_companycity);


                //Third Company
                $cityid2 = '69795';// cityids for companies

                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` ( `uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Joom Shark', 'joom-shark', 'http://www.joomshark.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample@joomshark.com', '<p>We aligns itself with modern and advanced concepts in IT industry to help its customers by providing value added software. We performs thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid2', 'WAPDA Town, Gujranwala ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$endfeatureddate."', '".$isfeaturedcompany."', '".$startfeatureddate."', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid2 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x3")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x3",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid2);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid2 . ", " . $cityid2 . ");";
                wpjobportaldb::query($insert_companycity);


                //  Fourth Company
                $cityid3 = '69820';// cityids for companies

                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` ( `uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ( '".$employer_id ."', 'Sample Company', 'sample-company', 'http://www.sample.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample@sample.com', '<p>We perform thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid3', 'some streest in some city ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$thirdydaydate."', 2, '0000-00-00 00:00:00', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid3 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x4")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x4",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid3);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid3 . ", " . $cityid3 . ");";
                wpjobportaldb::query($insert_companycity);

                //Fifth Company
                $cityid4 = '69786';

                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` ( `uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Sample Company 1', 'sample-company-1', 'http://www.sample1.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample1@sample1.com', '<p>We perform thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid4', 'some streest in some city ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$thirdydaydate."', 2, '0000-00-00 00:00:00', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid4 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x5")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x5",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid4);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid4 . ", " . $cityid4 . ");";
                wpjobportaldb::query($insert_companycity);


                //Sixth  Company
                $cityid5 = '69788';
                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` ( `uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Sample Company 2', 'sample-company-2', 'http://www.sample2.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample2@2sample.com', '<p>problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid5', 'some streest in some city ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$endfeatureddate."', '".$isfeaturedcompany."', '".$startfeatureddate."', '[]', '', 0)";

                wpjobportaldb::query($insert_company);
                $companyid5 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x6")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x6",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid5);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)VALUES( " . $companyid5 . ", " . $cityid5 . ");";
                wpjobportaldb::query($insert_companycity);



                //Seventh Company
                $cityid6 = '69801';

                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` (`uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Sample Company 3', 'sample-company-3', 'http://www.sample3.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample3@3sample.com', '<p>problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid6', 'some streest in some city ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$thirdydaydate."', 2, '0000-00-00 00:00:00', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid6 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x7")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x7",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid6);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid6 . ", " . $cityid6 . ");";
                wpjobportaldb::query($insert_companycity);

                //Eight Company
                 $cityid7 = '69792';

                 $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` (`uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Sample Company 4', 'sample-company-4', 'http://www.sample.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample4@4sample.com', '<p>problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid7', 'some streest in some city ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$thirdydaydate."', 2, '0000-00-00 00:00:00', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid7 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x8")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x8",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid7);

                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid7 . ", " . $cityid7 . ");";
                wpjobportaldb::query($insert_companycity);

                //Ninth Company
                $cityid8 = '69793';

                $insert_company = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companies` (`uid`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `tagline`, `contactemail`, `description`, `city`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `userpackageid`, `price`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`) VALUES
                    ('".$employer_id ."', 'Sample Company 5', 'sample-company-5', 'http://www.sample5.com', 'default-logo.png', 1, '', '', 0, '', 'We are the Best', 'sample5@5sample.com', '<p>problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution</p>\n', '$cityid8', 'some streest in some city ', '', '".$curdate."', '".$curdate."', 0, NULL, NULL, 1, 0, 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$endfeatureddate."', '".$isfeaturedcompany."', '".$startfeatureddate."', '[]', '', 0)
                    ";
                wpjobportaldb::query($insert_company);
                $companyid8 = wpjobportal::$_db->insert_id;
                // logo handling
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x9")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_x9",$wp_upload_dir["basedir"]."/".$data_directory."/data/employer/comp_".$companyid8);
                $insert_companycity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (`companyid`, `cityid`)
                VALUES( " . $companyid8 . ", " . $cityid8 . ");";
                wpjobportaldb::query($insert_companycity);

                /**
                *@param wp job portal
                * * Sample Data For job
                */


                //First  JOB
                $isfeaturedjob = 2;
                $startfeatureddate = "0000-00-00 00:00:00";
                $endfeatureddate = "0000-00-00 00:00:00";
                if(in_array('featuredjob', wpjobportal::$_active_addons)){
                    $isfeaturedjob = 1;
                    $startfeatureddate = date("Y-m-d H:i:s");
                    $endfeatureddate = date("Y-m-d H:i:s", strtotime("+12 months"));
                }

                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                    ('".$employer_id."', '".$companyid."', 'PHP Developer', 'php-developer', '13', 1, 2, 0, '<p>Responsibilities</p>\n<p>Work closely with Project Managers and other members of the Development Team to both develop detailed specification documents with clear project deliverables and timelines, and to ensure timely completion of deliverables.<br />\nProduce project estimates during sales process, including expertise required, total number of people required, total number of development hours required, etc.<br />\nAttend client meetings during the sales process and during development.<br />\nWork with clients and Project Managers to build and refine graphic designs for websites. Must have strong skills in Photoshop, Fireworks, or equivalent application(s).<br />\nConvert raw images and layouts from a graphic designer into CSS/XHTML themes.<br />\nDetermine appropriate architecture, and other technical solutions, and make relevant recommendations to clients.<br />\nCommunicate to the Project Manager with efficiency and accuracy any progress and/or delays. Engage in outside-the-box thinking to provide high value-of-service to clients.<br />\nAlert colleagues to emerging technologies or applications and the opportunities to integrate them into operations and activities.<br />\nBe actively involved in and contribute regularly to the development community of the CMS of your choice.<br />\nDevelop innovative, reusable Web-based tools for activism and community building.</p>\n', '', '', '', '', '".$cityid."', '', '', '', '', '', '', '', 0, 2, '', '3 Month', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 0, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 3, '', 0, '$', 'qgk6BtRM8', '67.0099388', '24.8614622', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$startfeatureddate."', '".$endfeatureddate."', '".$isfeaturedjob."', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 3, 1000, 1500, 2, 0, 0);
                    ";
                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid);



                //Second job
                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid1."', 'Android Developer', 'android-developer', '13', 1, 3, 0, '<p>Games developers are involved in the creation and production of games for personal computers, games consoles, social/online games, arcade games, tablets, mobile phones and other hand held devices. Their work involves either design (including art and animation) or programming.</p>\n<p>Games development is a fast-moving, multi-billion pound industry. The making of a game from concept to finished product can take up to three years and involve teams of up to 200 professionals.</p>\n<p>There are many stages, including creating and designing a game&#8217;s look and how it plays, animating characters and objects, creating audio, programming, localisation, testing and producing.</p>\n<p>The games developer job title covers a broad area of work and there are many specialisms within the industry. These include:</p>\n<p>quality assurance tester;<br />\nprogrammer, with various specialisms such as network, engine, toolchain and artificial intelligence;<br />\naudio engineer;<br />\nartist, including concept artist, animator and 3D modeller;<br />\nproducer;<br />\neditor;<br />\ndesigner;<br />\nspecial effects technician.</p>\n', '', '', '', '', '".$cityid1."', '', '', '', '', '', '', '', 0, 3, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 0, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 3, '', 0, '$', 'CKd6@8JQw', '73.0791073', '31.4187142', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 3, 2500, 3000, 2, 0, 0);

                    ";



                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid1);


                // Third Job
                $insert_job = " INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid2."', 'Accountant', 'accountant', '13', 1, 3, 0, '<ul>\n<li>Accountant Job Duties:</li>\n<li>Prepares asset, liability, and capital account entries by compiling and analyzing account information.<br />\nDocuments financial transactions by entering account information.<br />\nRecommends financial actions by analyzing accounting options.<br />\nSummarizes current financial status by collecting information; preparing balance sheet, profit and loss statement, and other reports.<br />\nSubstantiates financial transactions by auditing documents.<br />\nMaintains accounting controls by preparing and recommending policies and procedures.<br />\nGuides accounting clerical staff by coordinating activities and answering questions.<br />\nReconciles financial discrepancies by collecting and analyzing account information.<br />\nSecures financial information by completing data base backups.<br />\nMaintains financial security by following internal controls.<br />\nPrepares payments by verifying documentation, and requesting disbursements.<br />\nAnswers accounting procedure questions by researching and interpreting accounting policy and regulations.<br />\nComplies with federal, state, and local financial legal requirements by studying existing and new legislation, enforcing adherence to requirements, and advising management on needed actions.<br />\nPrepares special financial reports by collecting, analyzing, and summarizing account information and trends.<br />\nMaintains customer confidence and protects operations by keeping financial information confidential.<br />\nMaintains professional and technical knowledge by attending educational workshops; reviewing professional publications; establishing personal networks; participating in professional societies.<br />\nAccomplishes the result by performing the duty.<br />\nContributes to team effort by accomplishing related results as needed.</li>\n</ul>\n', '', '', '', '', '".$cityid2."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 0, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'CA', 3, '', 0, '$', 'pmX6r!W27', '73.0931461', '33.7293882', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 3, 4000, 4500, 2, 0, 0);";
                            wpjobportaldb::query($insert_job);
                            $jobid = wpjobportal::$_db->insert_id;
                            $insetjobcities = $this->insertJobCities($jobid, $cityid2);


                //4th job
                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid3."', 'Senior Software Engineer', 'senior-software-engineer', '13', 1, 2, 0, '<ul>\n<li>You might be responsible for the replacement of a whole system based on the specifications provided by an IT analyst but often you&#8217;ll work with &#8216;off the shelf&#8217; software, modifying it and integrating it into the existing network. The skill in this is creating the code to link the systems together.</li>\n<li>You&#8217;ll also be responsible for:</li>\n<li>Reviewing current systems<br />\nPresenting ideas for system improvements, including cost proposals<br />\nWorking closely with analysts, designers and staff<br />\nProducing detailed specifications and writing the programme codes<br />\nTesting the product in controlled, real situations before going live<br />\nPreparation of training manuals for users<br />\nMaintaining the systems once they are up and running</li>\n</ul>\n', '', '', '', '', '".$cityid3."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 5, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 4, '', 0, '$', 'CNQ83Jrgm', '74.3833333', '31.5166667', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 2, 4500, 4500, 2, 0, 0);
                        ";

                 wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid3);

                //5th job

                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid4."', 'Web Designer Engineer', 'web-designer', '13', 1, 2, 0, '<p>An associate''s degree program related to web design, such as an Associate of Applied Science in Web Graphic Design, provides a student with a foundation in the design and technical aspects of creating a website. Students learn web design skills and build professional portfolios that highlight their skills and abilities. Common topics include:</p>\r\n<ul>\r\n<li>Fundamentals of design imaging</li>\r\n<li>Basic web design</li>\r\n<li>Animation</li>\r\n<li>Multimedia design</li>\r\n<li>Content management</li>\r\n<li>Editing for video and audio</li>\r\n<li>Multimedia programming and technology</li>\r\n</ul>\r\n<p>A bachelor''s degree program in multimedia or web design allows students to learn advanced skills needed for professional web design. Students develop artistic and creative abilities in addition to technical skills. Degree programs, such as a Bachelor of Science in Web Design and Interactive Media, cover:</p>\r\n<ul>\r\n<li>Databases</li>\r\n<li>Webpage scripting</li>\r\n<li>Programming</li>\r\n<li>Digital imaging</li>\r\n<li>Multimedia design</li>\r\n<li>Web development</li>\r\n</ul>', '', '', '', '', '".$cityid4."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 5, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 4, '', 0, '$', 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$startfeatureddate."', '".$endfeatureddate."', '".$isfeaturedjob."', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 2, 4500, 4500, 2, 0, 0);
                        ";
                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid4);



                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid5."', 'WP Developer', 'wp-developer', '13', 1, 2, 0, '<ul>\n<li>You might be responsible for the replacement of a whole system based on the specifications provided by an IT analyst but often you&#8217;ll work with &#8216;off the shelf&#8217; software, modifying it and integrating it into the existing network. The skill in this is creating the code to link the systems together.</li>\n<li>You&#8217;ll also be responsible for:</li>\n<li>Reviewing current systems<br />\nPresenting ideas for system improvements, including cost proposals<br />\nWorking closely with analysts, designers and staff<br />\nProducing detailed specifications and writing the programme codes<br />\nTesting the product in controlled, real situations before going live<br />\nPreparation of training manuals for users<br />\nMaintaining the systems once they are up and running</li>\n</ul>\n', '', '', '', '', '".$cityid5."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 5, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 4, '', 0, '$', 'CNQ83Jrgm', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 2, 4500, 4500, 2, 0, 0);
                        ";



                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid5);


                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid6."', 'Senior Web Developer', 'senior-web-developer', '13', 1, 2, 0, '<ul>\n<li>You might be responsible for the replacement of a whole system based on the specifications provided by an IT analyst but often you&#8217;ll work with &#8216;off the shelf&#8217; software, modifying it and integrating it into the existing network. The skill in this is creating the code to link the systems together.</li>\n<li>You&#8217;ll also be responsible for:</li>\n<li>Reviewing current systems<br />\nPresenting ideas for system improvements, including cost proposals<br />\nWorking closely with analysts, designers and staff<br />\nProducing detailed specifications and writing the programme codes<br />\nTesting the product in controlled, real situations before going live<br />\nPreparation of training manuals for users<br />\nMaintaining the systems once they are up and running</li>\n</ul>\n', '', '', '', '', '".$cityid6."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 5, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 4, '', 0, '$', 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$startfeatureddate."', '".$endfeatureddate."', '".$isfeaturedjob."', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 2, 4500, 4500, 2, 0, 0);
                        ";
                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid6);


                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid7."', 'Junior PHP Developer', 'junior-php-developer', '13', 1, 2, 0, '<ul>\n<li>You might be responsible for the replacement of a whole system based on the specifications provided by an IT analyst but often you&#8217;ll work with &#8216;off the shelf&#8217; software, modifying it and integrating it into the existing network. The skill in this is creating the code to link the systems together.</li>\n<li>You&#8217;ll also be responsible for:</li>\n<li>Reviewing current systems<br />\nPresenting ideas for system improvements, including cost proposals<br />\nWorking closely with analysts, designers and staff<br />\nProducing detailed specifications and writing the programme codes<br />\nTesting the product in controlled, real situations before going live<br />\nPreparation of training manuals for users<br />\nMaintaining the systems once they are up and running</li>\n</ul>\n', '', '', '', '', '".$cityid7."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 5, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 4, '', 0, '$', 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".$startfeatureddate."', '".$endfeatureddate."', '".$isfeaturedjob."', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 2, 4500, 4500, 2, 0, 0);";
                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid7);


                $insert_job = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (`uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `sendemail`, `metadescription`, `metakeywords`, `ordering`, `aboutjobfile`, `status`, `educationid`, `degreetitle`, `careerlevel`, `map`, `subcategoryid`, `currency`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`, `salarytype`, `salarymin`, `salarymax`, `salaryduration`, `userpackageid`, `price`) VALUES
                        ('".$employer_id."', '".$companyid8."', 'Junior Andriod Developer', 'junior-games-developer', '13', 1, 2, 0, '<p>Games developers are involved in the creation and production of games for personal computers, games consoles, social/online games, arcade games, tablets, mobile phones and other hand held devices. Their work involves either design (including art and animation) or programming.</p>\r\n<p>Games development is a fast-moving, multi-billion pound industry. The making of a game from concept to finished product can take up to three years and involve teams of up to 200 professionals.</p>\r\n<p>There are many stages, including creating and designing a game''s look and how it plays, animating characters and objects, creating audio, programming, localisation, testing and producing.</p>\r\n<p>The games developer job title covers a broad area of work and there are many specialisms within the industry. These include:</p>\r\n<ul>\r\n<li>quality assurance tester;</li>\r\n<li>programmer, with various specialisms such as network, engine, toolchain and artificial intelligence;</li>\r\n<li>audio engineer;</li>\r\n<li>artist, including concept artist, animator and 3D modeller;</li>\r\n<li>producer;</li>\r\n<li>editor;</li>\r\n<li>designer;</li>\r\n<li>special effects technician.</li>\r\n</ul>', '', '', '', '', '".$cityid8."', '', '', '', '', '', '', '', 0, 1, '', '', '', '".$curdate."', 0, '0000-00-00 00:00:00', 0, 5, 0, '".$curdate."', '".$thirdydaydate."', '', 0, '', '', 0, '', 1, NULL, 'BSCS', 4, '', 0, '$', 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '[]', '', 0, '', 2, 4500, 4500, 2, 0, 0);";

                wpjobportaldb::query($insert_job);
                $jobid = wpjobportal::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid8);
            }

            if($jobseeker_id > 0){
                /**
                  * @param wp job portal
                  * * Sample Data * *
                  * * * Resume  * * *
                  */
                // first  resumes

                $isfeaturedresume = 2;
                $startfeatureresumeddate = "0000-00-00 00:00:00";
                $endfeatureresumeddate = "0000-00-00 00:00:00";
                if(in_array('featureresume', wpjobportal::$_active_addons)){
                    $isfeaturedresume = 1;
                    $startfeatureresumeddate = date("Y-m-d H:i:s");
                    $endfeatureresumeddate = date("Y-m-d H:i:s", strtotime("+12 months"));
                }

                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Data', '340000', '', 'Sample-Data', 'First name ', 'last Name', '1', 'sampledata@info.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '[]', 0, 0);";
                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;

                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x1")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x1",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);


                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` (`resumeid`, `employer`, `employer_from_date`, `employer_to_date`, `employer_current_status`, `employer_city`, `employer_phone`, `employer_address`, `created`, `last_modified`, `params`, `serverstatus`, `serverid`)
                                VALUES( " .$resumeid.  ", 'Sample data', '" .$date. "', '" .$date. "' , 0, '" .$cityid. "', '123456789', 'GT ROAD TRUST PLAZA', '0000-00-00 00:00:00', '', '', NULL, NULL);";
                wpjobportaldb::query($resume_query);


                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` ( `resumeid`, `institute`, `institute_certificate_name`, `institute_study_area`, `created`, `last_modified`, `serverstatus`, `serverid`, `fromdate`, `todate` , `params`)
                                        VALUES( " .$resumeid. " , 'Sample data', 'Sample data', 'Sample', '" .$date. "', '', NULL, NULL, '12/5/2014', '12/18/2019', '');";

                wpjobportaldb::query($resume_query);


                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` ( `resumeid`, `language`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Sample data','".$date."','0000-00-00 00:00:00',NULL,NULL);";
                wpjobportaldb::query($resume_query);

                // 2nd resume

                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume', '340000', '', 'sample-resume', 'John ', 'Doe', '1', 'sample@resume.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '".$startfeatureresumeddate."', '0000-00-00 00:00:00', '".$endfeatureresumeddate."', '".$isfeaturedresume."', '', 0, '', '[]', 0, 0);";
                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x2")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x2",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                    VALUES (" . $resumeid . ", 'Gujranwala', $cityid1, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);

                //3rd Resume
                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 1', '340000', '', 'sample-resume-1', 'John ', 'Doe', '1', 'sample@resume1.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '[]', 0, 0);";
                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x3")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x3",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                    VALUES (" . $resumeid . ", 'Gujranwala', $cityid2, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);
                //4th Resume

                 $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 2', '340000', '', 'sample-resume-2', 'John ', 'Doe', '1', 'sample@resume2.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '".$startfeatureresumeddate."', '0000-00-00 00:00:00', '".$endfeatureresumeddate."', '".$isfeaturedresume."', '', 0, '', '[]', 0, 0);";
                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x4")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x4",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid3, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);
                //5th Resume
                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 3', '340000', '', 'sample-resume-3', 'John ', 'Doe', '1', 'sample@resume3.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '[]', 0, 0);";
                    wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x5")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x5",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid4, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);

                ///6th Resume
                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 4', '340000', '', 'sample-resume-4', 'John ', 'Doe', '1', 'sample@resume4.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '".$startfeatureresumeddate."', '0000-00-00 00:00:00', '".$endfeatureresumeddate."', '".$isfeaturedresume."', '', 0, '', '[]', 0, 0);";
                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x6")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x6",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid5, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);


                //7th Resume
                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 5', '340000', '', 'sample-resume-5', 'John ', 'Doe', '1', 'sample@resume5.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '[]', 0, 0);";

                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x7")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x7",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid6, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);



                ///8th Resume

                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 3', '340000', '', 'sample-resume-6', 'John ', 'Doe', '1', 'sample@resume6.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '[]', 0, 0);";
                 wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x8")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x8",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid7, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);

                ///9th Resume

                $resume_query = "INSERT INTO `".wpjobportal::$_db->prefix."wj_portal_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `salaryfixed`, `keywords`, `alias`, `first_name`, `last_name`, `gender`, `email_address`, `cell`, `nationality`, `searchable`, `photo`, `job_category`, `jobtype`, `status`, `resume`, `skills`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `params`, `userpackageid`, `price`) VALUES
                    ('".$jobseeker_id."', '".$date."', '0000-00-00', 0, 0, 'Sample Resume 7', '340000', '', 'sample-resume-7', 'John ', 'Doe', '1', 'sample@resume7.com', '123456789', '1', 1, 'resume-photo.png', 13, 1, 1, '', 'this is some text that i have written in skills section of reusme.', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '[]', 0, 0);";
                wpjobportaldb::query($resume_query);
                $resumeid = wpjobportal::$_db->insert_id;
                if (file_exists($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x9")) rename($wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_x9",$wp_upload_dir["basedir"]."/".$data_directory."/data/jobseeker/resume_".$resumeid);

                $resume_query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` ( `resumeid`, `address`, `address_city`,  `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid8, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                wpjobportaldb::query($resume_query);




                /**
                ** @param wp job portal
                *Sample Data
                * * *Job Apply Code
                **/

                $jobs = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE title='Web Designer' OR title='senior software engineer' OR title='Accountant' OR title='Android Developer' OR title='PHP Developer';";
                $jobids = wpjobportaldb::get_results($jobs);


                foreach ($jobids AS $jobid) {
                    $appliedjobs = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` (`jobid`, `uid`, `cvid`, `apply_date`, `resumeview`, `comments`, `coverletterid`, `action_status`, `serverstatus`, `serverid`,status)
                        VALUES (" . $jobid->id . "," . $jobseeker_id . "," . $resumeid . ",'" . $date . "',0,NULL,NULL,1,NULL,NULL,1)";
                    wpjobportaldb::query($appliedjobs);
                }
            }
            if ($temp_data == 1) {
                $product_type = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
                if($product_type == 'pro'){
                    $flag = 'p';
                }else{
                    $flag = 'f';
                }
                $this->installSampleDataTemplate($flag);
            }


        }
        return true;
    }

     function installSampleDataTemplate($flag) {
        if(wpjobportal::$theme_chk == 2){
            return $this->installSampleDataTemplateJobHub($flag);
        }
        $product_type = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
        if($flag == 'p'){
            if($product_type != 'pro'){
                return 0;
            }
                $pro  = 1;
        }elseif($flag == 'f'){
            if($product_type != 'free'){
                return 0;
            }
            $pro  = 0;
        }elseif($flag == 'ftp'){
            if($product_type != 'pro'){
                return 0;
            }
            $this->installFreeToProData();
            return 1;
        }
        return 1;
    }

    function installSampleDataTemplateJobHub($flag) {
        $product_type = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
        if($flag == 'p'){
            return 0;
        }elseif($flag == 'f'){
            if($product_type != 'free'){
                return 0;
            }
            $pro  = 0;
        }elseif($flag == 'ftp'){
                return 0;
        }
        // Check for the rev slider
        $wp_upload_dir = wp_upload_dir();
        if (file_exists(get_template_directory() . "/framework/plugins/sample-data.zip")) {
            do_action('wpjobportal_load_wp_pcl_zip');
            $archive = new PclZip(get_template_directory() . "/framework/plugins/sample-data.zip");
            $v_list = $archive->extract($wp_upload_dir["basedir"]);
        }
        if( ! function_exists("__update_post_meta")){
            function __update_post_meta( $post_id, $field_name, $value = "" ){
                if ( empty( $value ) OR ! $value ){
                    delete_post_meta( $post_id, $field_name );
                }elseif ( ! get_post_meta( $post_id, $field_name ) ){
                    add_post_meta( $post_id, $field_name, $value );
                }else{
                    update_post_meta( $post_id, $field_name, $value );
                }
            }
        }
        if( ! function_exists("uploadPostFeatureImage")){
            function uploadPostFeatureImage($filename,$parent_post_id){
                // Check the type of file. We"ll use this as the "post_mime_type".
                $filetype = wp_check_filetype( wpjobportalphplib::wpJP_basename( $filename ), null );
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    "guid"           => $wp_upload_dir["url"] . "/" . wpjobportalphplib::wpJP_basename( $filename ),
                    "post_mime_type" => $filetype["type"],
                    "post_title"     => wpjobportalphplib::wpJP_preg_replace( "/\.[^.]+$/", "", wpjobportalphplib::wpJP_basename( $filename ) ),
                    "post_content"   => "",
                    "post_status"    => "inherit"
                );
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                do_action('wpjobportal_load_wp_image');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                set_post_thumbnail( $parent_post_id, $attach_id );
            }
        }
        $jh_pages = array();
        // Home
        $new_page_title = "Home";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["home"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["home"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["home"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["home"], "jh_show_header", 2);
        // Home 1
        $new_page_title = "Home 1";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["home1"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["home1"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["home1"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["home1"], "jh_show_header", 2);
        
        $wp_upload_dir = wp_upload_dir();
        /* end of homepages */
        // Price table
        $new_page_title = "Pricing Table";
        $new_page_content = '<div class="jsjb-jh-pricicing-box-wrap"><div class="jsjb-jh-price-box-row"><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-1"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-2"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-3"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-4"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-5"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-6"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-7"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-8"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-9"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div></div></div>';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["pricing_table"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["pricing_table"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["pricing_table"], "jh_show_header", 1);
        update_post_meta($jh_pages["pricing_table"], "_wp_page_template", $new_page_template);
    // job hub pages
        $page_array[1] = "Jobseeker Control Panel";
        $page_array[2] = "Newest Jobs";
        $page_array[3] = "My Applied Jobs";
        $page_array[4] = "My Resume";
        $page_array[5] = "Search Job";
        $page_array[6] = "Jobs By Category";
        $page_array[8] = "Add Resume";
        $page_array[11] = "All Companies";

        $page_array[18] = "Jobseeker Stats";
        $page_array[19] = "Employer Control Panel";
        $page_array[20] = "My Jobs";
        $page_array[21] = "Add Job";
        $page_array[22] = "Resume Search";
        $page_array[23] = "Resume By Category";
        $page_array[24] = "My Companies";
        $page_array[25] = "Add Company";
        $page_array[32] = "Employer Stats";
        $page_array[33] = "Login";
        $page_array[34] = "Employer Registration";
        $page_array[35] = "Jobseeker Registration";
        $page_array[36] = "Thank You";
        foreach ($page_array as $key => $value) {
            // $value_string = wpjobportalphplib::wpJP_strtolower($value);
            // $value_string = sanitize_title($value_string);
            $value_string = wpjobportalphplib::wpJP_strtolower($value);
            $value_string = wpjobportalphplib::wpJP_str_replace(" ","_",$value_string);
            $new_page_title = $value;
            $new_page_content = '[vc_row][vc_column][jh_job_hub_pages page page="'.$key.'"][/vc_column][/vc_row]';
            $new_page_template = "templates/template-fullwidth.php";
            $page_check = get_page_by_title($new_page_title);
            $new_page = array(
                    "post_type" => "page",
                    "post_title" => $new_page_title,
                    "post_content" => $new_page_content,
                    "post_status" => "publish",
                    "post_author" => 1,
                    "post_parent" => 0,
            );
            if(!isset($page_check->ID)){
                $jh_pages[$value_string] = wp_insert_post($new_page);
            }else{
                $new_page["post_title"] = "Job Hub ".$new_page_title;
                $jh_pages[$value_string] = wp_insert_post($new_page);
            }
            update_post_meta($jh_pages[$value_string], "jh_show_header", 1);
            update_post_meta($jh_pages[$value_string], "_wp_page_template", $new_page_template);
        }
    // job hub pages end
        // News & Rumors
        $new_page_title = "News & Rumors";
        $new_page_content = '[vc_row][vc_column][jh_news_and_rumors style="3" posts_per_page="2"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-news_and_rumors.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["news_and_rumors"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["news_and_rumors"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["news_and_rumors"], "jh_show_header", 1);
        update_post_meta($jh_pages["news_and_rumors"], "_wp_page_template", $new_page_template);
        // FAQ
        $new_page_title = "FAQ";
        $new_page_content = '[vc_row][vc_column][vc_tta_accordion active_section="1" el_class="jsjb-jh-faq-wrap"][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754286724-c9ed48ed-c7f7" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754537433-695717d6-6bfc" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754536332-73927004-0a28" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754535606-7dffd7a9-2119" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754534808-bb9dfb79-6d18" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754534095-ec21098b-4397" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754529612-21aedc15-ddd9" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754528056-93a9c873-2efd" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][/vc_tta_accordion][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["faq"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["faq"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["faq"], "jh_show_header", 1);
        update_post_meta($jh_pages["faq"], "_wp_page_template", $new_page_template);
       // Our Team
        $new_page_title = "Our Team";
        $new_page_content = '[vc_row][vc_column][jh_team_memebers per_row="3" posts_per_page="4" style="3"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["ourteam"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["ourteam"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["ourteam"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["ourteam"], "jh_show_header", 1);
        // Contact Us
        $new_page_title = "Contact Us";
        $new_page_content = "";
        $new_page_template = "templates/template-contactus.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["contact_us"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["contact_us"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["contact_us"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["contact_us"], "jh_show_header", 1);
        // Blog Page
        $new_page_title = "Blog";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        if(!isset($page_check->ID)){
            $jh_pages["blog"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["blog"] = wp_insert_post($new_page);
        }
        update_option("page_for_posts", $jh_pages["blog"]);
    // Update home page contents
        //Home
        $new_page_content = '[vc_row][vc_column][jh_job_search wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="7" subtitle="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text"][/vc_column][/vc_row][vc_row][vc_column][jh_feature_3box][/vc_column][/vc_row][vc_row][vc_column][jh_jobs_category wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="2" category="1" category1="1" category2="1" category3="1" category4="1" category5="1" category6="1" category7="1"][/vc_column][/vc_row][vc_row][vc_column][jh_5count_box style="2" count1="587" count2="146" count3="919" count4="796"][/vc_column][/vc_row][vc_row][vc_column][jh_jobs wpjobportalpageid="'.$jh_pages["newest_jobs"].'" title1="Latest Jobs" noofjobs="6" style="2"][/vc_column][/vc_row][vc_row][vc_column][jh_job_hub_custom_link wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="6" title="REGISTER AS EMPLOYER" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut veniam ." description1="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut veniam ."][/vc_column][/vc_row][vc_row][vc_column][jh_price_tables style="2" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][jh_companies wpjobportalpageid="'.$jh_pages["newest_jobs"].'" companytype="1" style="1" bgcolor="#ffffff" title="Popular Companies" scrollstyle="2" speed="1" posts_per_page="10"][/vc_column][/vc_row][vc_row][vc_column][jh_shortdescription_with_btn][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jh_pages["home"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        //Home 1
        $new_page_content = '[vc_row][vc_column][jh_job_search wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="8"][/vc_column][/vc_row][vc_row][vc_column][jh_latest_featured_jobs wpjobportalpageid="'.$jh_pages["newest_jobs"].'" title="Latest Jobs" heading="Featured Jobs" noofjobs="6"][/vc_column][/vc_row][vc_row][vc_column][jh_job_hub_custom_link wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="7" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo"][/vc_column][/vc_row][vc_row][vc_column][jh_latest_resume wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="5" title="Latest Resume" description="Here you can see most recent resume added by users.You are able to set how many resume will be displayed ,order them or chose the specific taxonomy terms of resume." post_per_page="5" scrollstyle="2" speed="1"][/vc_column][/vc_row][vc_row][vc_column][jh_5count_box style="6" count1="587" count2="146" count3="919" count4="796"][/vc_column][/vc_row][vc_row][vc_column][jh_job_hub_custom_link wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="8" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo"][/vc_column][/vc_row][vc_row][vc_column][jh_news_and_rumors style="1"][/vc_column][/vc_row][vc_row][vc_column][jh_companies wpjobportalpageid="'.$jh_pages["newest_jobs"].'" companytype="1" style="2" title="Popular Companies" posts_per_page="6"][/vc_column][/vc_row][vc_row][vc_column][jh_shortdescription_with_btn][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jh_pages["home1"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        //Home 2
        $new_page_content = '[vc_row video_bg="yes"][vc_column][vc_empty_space height="550px"][/vc_column][/vc_row][vc_row][vc_column][jh_job_search wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="2"][/vc_column][/vc_row][vc_row][vc_column][jh_latest_jobs_types_categories_cities wpjobportalpageid="'.$jh_pages["newest_jobs"].'" title="Latest Jobs" noofjobs="5" display_style="1" show_jobs_by_types="1" number_of_job_type="5" show_number_of_jobs_by_type="1" show_jobs_by_categories="1" number_of_categories="5" show_number_of_jobs_by_category="1" show_jobs_by_cities="1" number_of_cities="5" show_number_of_jobs_by_city="1"][/vc_column][/vc_row][vc_row][vc_column][jh_image_and_text_box_with_links][/vc_column][/vc_row][vc_row][vc_column][jh_latest_resume wpjobportalpageid="'.$jh_pages["newest_jobs"].'" style="4" title="Latest Resume" description="Here you can see most recent resume added by users.You are able to set how many resume will be displayed ,order them or chose the specific taxonomy terms of resume." post_per_page="5"][/vc_column][/vc_row][vc_row][vc_column][jh_news_and_rumors style="4"][/vc_column][/vc_row][vc_row][vc_column][jh_testimonial style="4" heading="Kind Words From Happy Candidates" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et ."][/vc_column][/vc_row][vc_row el_class="jsjb-jh-companies-carasole-without-bg"][vc_column][jh_companies wpjobportalpageid="'.$jh_pages["newest_jobs"].'" companytype="1" style="2" title="Popular Companies" posts_per_page="6"][/vc_column][/vc_row][vc_row][vc_column][jh_post_add style="1"][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jh_pages["home2"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        // Update WP Options
        $wp_page_array = array();
        foreach ($page_array as $key => $value) {
            $value_string = wpjobportalphplib::wpJP_strtolower($value);
            $value_string = wpjobportalphplib::wpJP_str_replace(" ","_",$value_string);
            $wp_page_array[$value_string] = $jh_pages[$value_string];
        }
        update_option("job-hub-layout", $wp_page_array);
        // ----------------Posts -------- //
        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post5.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post4.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // ----------------Custom posts -------- //
        // News & Rumors
        $new_page_title = "Advertising For Your Business";
        $new_page_content = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for "lorem ipsum" will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).';
        $new_page = array(
                "post_type" => "jh_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_1.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Attract More Attention Sales";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<strong></strong>';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_2.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Top fun activities tips for you ";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_3.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);


        // Team members
        $new_page_title = "Member 4";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Front-end Developer");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_1.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 3";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Project Manager");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 2";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Team Leader");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_3.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 1";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Cheif executive office / CEO");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_4.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // Price Table
        $new_page_title = "Basic Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jh_price" , "$ 500");
        __update_post_meta($new_page_id, "jh_line1" , "1500 Credits");
        __update_post_meta($new_page_id, "jh_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jh_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jh_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jh_buynowlink" , "#");

        $new_page_title = "Business Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jh_price" , "$ 750");
        __update_post_meta($new_page_id, "jh_line1" , "2500 Credits");
        __update_post_meta($new_page_id, "jh_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jh_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jh_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jh_buynowlink" , "#");

        $new_page_title = "Complete Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jh_price" , "$ 1500");
        __update_post_meta($new_page_id, "jh_line1" , "6000 Credits");
        __update_post_meta($new_page_id, "jh_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jh_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jh_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jh_buynowlink" , "#");

        // TESTIMONIAL
        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis quam sit amet dolor fermentum, in porta nisi egestas. Nullam convallis laoreet gravida. Pellentesque sed.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Naro MathDoe";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at tempus velit. Aliquam et diam convallis, tempus ligula ut, placerat sem. Nulla condimentum nulla a.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "MARY DOE";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce accumsan vitae massa vel aliquet. Morbi sed nibh eget lectus consequat tempor. Aliquam erat volutpat. Nam.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Robert Lafore";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eleifend lacinia enim at dapibus. Nam eget accumsan neque. Nam felis augue, egestas ut varius vel.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_4.jpeg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam commodo laoreet neque, vitae facilisis quam eleifend a. In consectetur purus quis arcu dictum, sit amet.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_5.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);
        // Pages and custom post are created Now create Menu ----------------

        update_option( "page_on_front", $jh_pages["home"] );
        update_option( "show_on_front", "page" );

        // MENU
        // Check if the menu exists
        $menu_name = "Job Hub";
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        // If it doesn"t exist, let"s create it.
        if( !$menu_exists){
            $menu_id = wp_create_nav_menu($menu_name);

            $locations = get_theme_mod("nav_menu_locations");
            $locations["primary"] = $menu_id;
            set_theme_mod( "nav_menu_locations", $locations );

            $itemData =  array(
                "menu-item-object-id" => $jh_pages["home"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_home = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["home1"],
                "menu-item-parent-id" => $parent_home,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["home2"],
                "menu-item-parent-id" => $parent_home,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            // Job seeker

            $itemData =  array(
                "menu-item-title" => "Job Seeker",
                "menu-item-object-id" => $jh_pages["jobseeker_control_panel"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            $parent_jobseeker = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Jobs",
                "menu-item-object-id" => $jh_pages["newest_jobs"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Search Job",
                "menu-item-object-id" => $jh_pages["search_job"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Jobs By Categories",
                "menu-item-object-id" => $jh_pages["jobs_by_category"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Add Resume",
                "menu-item-object-id" => $jh_pages["add_resume"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Applied Jobs",
                "menu-item-object-id" => $jh_pages["my_applied_jobs"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            // employer
            $itemData =  array(
                "menu-item-title" => "Employer",
                "menu-item-object-id" => $jh_pages["employer_control_panel"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            $parent_employer = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Add Job",
                "menu-item-object-id" => $jh_pages["add_job"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Jobs",
                "menu-item-object-id" => $jh_pages["my_jobs"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Resume Search",
                "menu-item-object-id" => $jh_pages["resume_search"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Resumes By Categories",
                "menu-item-object-id" => $jh_pages["resume_by_category"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Pages",
                "menu-item-object-id" => $jh_pages["blog"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_pages = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["news_and_rumors"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["faq"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["blog"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["pricing_table"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["thank_you"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            if($pro == 1){

            $itemData =  array(
                "menu-item-title" => "Credits",
                "menu-item-object-id" => $jh_pages["jobseeker_credits_pack"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_credits = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Job Seeker Credits Pack",
                "menu-item-object-id" => $jh_pages["jobseeker_credits_pack"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_credits = wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Job Seeker Credits Log",
                "menu-item-object-id" => $jh_pages["jobseeker_credits_log"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Job Seeker Rate List",
                "menu-item-object-id" => $jh_pages["jobseeker_rate_list"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Employer Credits Pack",
                "menu-item-object-id" => $jh_pages["employer_credits_pack"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Employer Credits Log",
                "menu-item-object-id" => $jh_pages["employer_credits_log"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Employer Rate List",
                "menu-item-object-id" => $jh_pages["employer_rate_list"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            }

            $itemData =  array(
                "menu-item-object-id" => $jh_pages["ourteam"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["contact_us"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            }
            $widget_positions = get_option("sidebars_widgets");
            // Woocommerce sidebar
                $widget_positions["woocommerce-sidebar"][] = "woocommerce_widget_cart-1";
                $widget_woocommerce_widget_cart_array[1] = array("title" => "My Cart");
                $widget_woocommerce_widget_cart_array["_multiwidget"] = 1;
                // Left sidebar
                $widget_positions["left-sidebar"][] = "search-1";
                $search_array[1] = array("title" => "Search");
                $search_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "recent-posts-1";
                $recent_posts_array[1] = array("title" => "Recent Posts", "number" => 5);
                $recent_posts_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "recent-comments-1";
                $recent_comments_array[1] = array("title" => "Recent Comments", "number" => 5);
                $recent_comments_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "archives-1";
                $archives_array[1] = array("title" => "Archives");
                $archives_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "categories-1";
                $categories_array[1] = array("title" => "Categories");
                $categories_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "meta-1";
                $meta_array[1] = array("title" => "Meta");
                $meta_array["_multiwidget"] = 1;
                // Right sidebar
                $widget_positions["right-sidebar"][] = "calendar-1";
                $calendar_array[1] = array("title" => "Calendar");
                $calendar_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "widget_jsjb_recent_comments-1";
                $widget_jsjb_recent_comments_array[1] = array("title" => "Job Hub Recent Comments", "count" => 2);
                $widget_jsjb_recent_comments_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "widget_jsjb_recent_posts-1";
                $widget_jsjb_recent_posts_array[1] = array("title" => "Job Hub Recent Posts", "category" => "");
                $widget_jsjb_recent_posts_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "nav_menu-1";
                $nav_menu_array[1] = array("title" => "Custom Menu", "nav_menu" => "");
                $nav_menu_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "pages-1";
                $pages_array[1] = array("title" => "Pages", "sortby" => "post_title");
                $pages_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "tag_cloud-1";
                $tag_cloud_array[1] = array("title" => "Tag Cloud", "taxonomy" => "post_tag");
                $tag_cloud_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "text-1";
                $text_array[1] = array("title" => "Text Heading", "text" => "Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum");
                $text_array["_multiwidget"] = 1;
                // News and rumors
                $widget_positions["news_and_rumors"][] = "search-2";
                $search_array[2] = array("title" => "Search");
                $search_array["_multiwidget"] = 1;
                $widget_positions["news_and_rumors"][] = "recent-posts-2";
                $recent_posts_array[2] = array("title" => "Recent Posts", "number" => 5);
                $recent_posts_array["_multiwidget"] = 1;
                // footer1
                if (!is_active_sidebar( 'footer1' ) ) { // check if widget was activated from template. to avoid dupilication
                    $widget_positions["footer1"][] = "widget_jsjb_footeraboutus-1";
                    $widget_jsjb_footeraboutus_array[1] = array("title" => "Job Hub", "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.");
                    $widget_jsjb_footeraboutus_array["_multiwidget"] = 1;
                }
                // footer2
                $widget_positions["footer2"][] = "widget_jsjb_footerusefullinks-1";
                $widget_jsjb_footerusefullinks_array[1] = array(
                    "title" => "Useful Links",
                    "title1"=>"Newest Jobs", "link1"=> get_the_permalink($jh_pages["newest_jobs"]),
                    "title3"=>"Search Job", "link3"=> get_the_permalink($jh_pages["search_job"]),
                    "title2"=>"Resume Search", "link2"=> get_the_permalink($jh_pages["resume_search"]),
                    "title4"=>"Shortlisted Jobs", "link4"=> get_the_permalink($jh_pages["shortlisted_jobs"]),
                    "title5"=>"All Companies", "link5"=> get_the_permalink($jh_pages["all_companies"]),
                    "title6"=>"", "link6"=> "#",
                    "title7"=>"", "link7"=> "#",
                    "title8"=>"", "link8"=> "#",
                    "title9"=>"", "link9"=> "#",
                    "title10"=>"", "link10"=> "#",
                    );
                $widget_jsjb_footerusefullinks_array["_multiwidget"] = 1;
                // footer3
                // $widget_positions["footer3"][] = "widget_jsjs_footercompaniesimages-1";
                $widget_jsjs_footercompaniesimages_array[1] = array("title" => "Featured Companies", "companytype" => 1, "max_images"=>9, "column"=>3);
                $widget_jsjs_footercompaniesimages_array["_multiwidget"] = 1;
                // footer4
                if (!is_active_sidebar( 'footer3' ) ) { // check if widget was activated from template. to avoid dupilication
                    $widget_positions["footer3"][] = "widget_jsjb_footercontactus-1";
                    $widget_jsjb_footercontactus_array[1] = array("title" => "Contact Us", "email" => "jobhub@yourdomain.com", "address"=>"At vero eos et accusamus et iusto odio dignissimos", "phone"=>"+1234567890");
                    $widget_jsjb_footercontactus_array["_multiwidget"] = 1;
                }

                update_option("widget_"."widget_woocommerce_widget_cart"  , $widget_woocommerce_widget_cart_array);
                update_option("widget_"."search"  , $search_array);
                update_option("widget_"."recent-posts"  , $recent_posts_array);
                update_option("widget_"."recent-comments"  , $recent_comments_array);
                update_option("widget_"."archives"  , $archives_array);
                update_option("widget_"."categories"  , $categories_array);
                update_option("widget_"."meta"  , $meta_array);
                update_option("widget_"."calendar"  , $calendar_array);
                update_option("widget_"."widget_jsjb_recent_comments"  , $widget_jsjb_recent_comments_array);
                update_option("widget_"."widget_jsjb_recent_posts"  , $widget_jsjb_recent_posts_array);
                update_option("widget_"."nav_menu"  , $nav_menu_array);
                update_option("widget_"."pages"  , $pages_array);
                update_option("widget_"."tag_cloud"  , $tag_cloud_array);
                update_option("widget_"."text"  , $text_array);
                update_option("widget_"."widget_jsjb_footerusefullinks"  , $widget_jsjb_footerusefullinks_array);
                update_option("widget_"."widget_jsjs_footercompaniesimages"  , $widget_jsjs_footercompaniesimages_array);
                if (!is_active_sidebar( 'footer3' ) ) { // check if widget was activated from template. to avoid dupilication
                    update_option("widget_"."widget_jsjb_footercontactus"  , $widget_jsjb_footercontactus_array);
                }
                if (!is_active_sidebar( 'footer1' ) ) { // check if widget was activated from template. to avoid dupilication
                    update_option("widget_"."widget_jsjb_footeraboutus"  , $widget_jsjb_footeraboutus_array);
                }

                // update this array at last
                update_option( "sidebars_widgets" , $widget_positions);

                global $wpdb;
                $pageid = $wpdb->get_var("Select id FROM `" . $wpdb->prefix . "posts` WHERE post_name = 'js-support-ticket-controlpanel'");
                if(is_numeric($pageid) && $pageid > 0){
                    update_post_meta($pageid, "_wp_page_template", "templates/template-fullwidth.php");
                }
            // Update the configuration default page to Vehicles
                $query = "UPDATE `".$wpdb->prefix."wj_portal_config` SET configvalue = '".$jh_pages["newest_jobs"]."' WHERE configname = 'default_pageid'";
                $wpdb->query($query);
                update_option("rewrite_rules", "");
            return 1;
    }

    function insertJobCities($jobid, $cityid) {
        $insert_jobcity = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` (`jobid`, `cityid`)
        VALUES( " . $jobid . ", " . $cityid . ");";
        wpjobportaldb::query($insert_jobcity);
        return true;
    }

    function getPageList() {
        $query = "SELECT ID AS id, post_title AS text FROM `" . wpjobportal::$_db->prefix . "posts` WHERE post_type = 'page' AND post_status = 'publish' ";
        $pages = wpjobportal::$_db->get_results($query);
        return $pages;
    }

    function getListOfDemoVersions() {
        $post_data = array();// data to posted with curl call
        return ;
    }

    function getDemo($demoid,$foldername,$demo_overwrite =0) {
        if($demoid == ''){
            die('demo not seleceted');
        }
        return;
    }

    function removeJobManagerDemoData(){
        delete_option("widget_widget_woocommerce_widget_cart");
        delete_option("widget_search");
        delete_option("widget_recent-posts");
        delete_option("widget_recent-comments");
        delete_option("widget_archives");
        delete_option("widget_categories");
        delete_option("widget_meta");
        delete_option("widget_calendar");
        delete_option("widget_widget_cm_recent_comments");
        delete_option("widget_widget_cm_recent_posts");
        delete_option("widget_nav_menu");
        delete_option("widget_pages");
        delete_option("widget_tag_cloud");
        delete_option("widget_text");
        delete_option("widget_widget_cm_footeraboutus");
        delete_option("widget_widget_cm_footerusefullinks");
        delete_option("widget_widget_cm_footervehicleimages");
        delete_option("widget_widget_cm_footercontactus");

        $widget_positions = get_option("sidebars_widgets");
        unset($widget_positions["footer1"]);
        unset($widget_positions["footer2"]);
        unset($widget_positions["footer3"]);
        unset($widget_positions["footer4"]);
        unset($widget_positions["left-sidebar"]);
        unset($widget_positions["right-sidebar"]);
        unset($widget_positions["news_and_rumors"]);

        update_option( "sidebars_widgets" , $widget_positions);

        $menu_name = "Job Manager";
        $del_flag = wp_delete_nav_menu($menu_name);
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        $pages_array = get_option('job_manager_demo_pages_ids');
        if(!empty($pages_array) && is_array($pages_array) ){
            foreach ($pages_array as $key => $value) {
                wp_delete_post($value,true);
            }
        }

        $post_array = get_option('job_manager_demo_post_ids');
        if(!empty($post_array) && is_array($post_array) ){
            foreach ($post_array as $key => $value) {
                wp_delete_post($value,true);
            }
        }
        return;
    }


    function getMessagekey(){
        $key = 'postinstallation';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


    function uploadPostFeatureImage($filename,$parent_post_id){
        // Check the type of file. We"ll use this as the "post_mime_type".
        $filetype = wp_check_filetype( wpjobportalphplib::wpJP_basename( $filename ), null );
        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();
        // Prepare an array of post data for the attachment.
        $attachment = array(
            "guid"           => $wp_upload_dir["url"] . "/" . wpjobportalphplib::wpJP_basename( $filename ),
            "post_mime_type" => $filetype["type"],
            "post_title"     => wpjobportalphplib::wpJP_preg_replace( "/\.[^.]+$/", "", wpjobportalphplib::wpJP_basename( $filename ) ),
            "post_content"   => "",
            "post_status"    => "inherit"
        );
        // Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        do_action('wpjobportal_load_wp_image');
        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $parent_post_id, $attach_id );
    }


    function createSamplePosts($post_type,$post_title,$post_content,$file_path = '',$postmeta = array()){
        $new_page_template = '';
        $new_post = array(
                        "post_type" => $post_type,
                        "post_title" => $post_title,
                        "post_content" => $post_content,
                        "post_status" => "publish",
                        "post_author" => 1,
                        "post_parent" => 0,
                );
        $post_id = wp_insert_post($new_post);
        update_post_meta($post_id, "_wp_page_template", $new_page_template);
        if($file_path != ''){
            $this->uploadPostFeatureImage($file_path,$post_id);
        }
        if(!empty($postmeta)){
            foreach ($postmeta as $meta_key => $meta_value) {
                __update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
        return $post_id;
    }


    function createSampleMenuItem($menu_id,$wp_pageid,$parent_id = 0,$title=''){
        $itemData = array();
        if($title != ''){
            $itemData['menu-item-title'] = $title;
        }
        $itemData["menu-item-object-id"] = $wp_pageid;
        $itemData["menu-item-parent-id"] = $parent_id;
        $itemData["menu-item-object"] = "page";
        $itemData["menu-item-type"] = "post_type";
        $itemData["menu-item-status"] = "publish";
        // original sample
        /*
            $itemData =  array(
                "menu-item-object-id" => $cm_pages["rate_list"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
        */
        $return_parent_id = wp_update_nav_menu_item($menu_id, 0, $itemData);
        return $return_parent_id;
    }

    function createTemplatePagesGeneric($page_title,$page_content,$page_template,$show_header,$file_path=''){
        $new_page = array(
                "post_type" => "page",
                "post_title" => $page_title,
                "post_content" => $page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $page_check = get_page_by_title($page_title);
        if(!isset($page_check->ID)){
            $page_id = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Manager ".$page_title;
            $page_check = get_page_by_title($new_page["post_title"]);
            if(!isset($page_check->ID)){
                $page_id = wp_insert_post($new_page);
            }else{
                $page_id = $page_check->ID;
            }
        }
        if(isset($page_id) && $page_id != ''){
            update_post_meta($page_id, "jm_show_header", $show_header);
            update_post_meta($page_id, "jm_headerimage_url", $file_path);
            if($page_template != ''){
                update_post_meta($page_id, "_wp_page_template", $page_template);
            }
        }

        return $page_id;
    }

    function recursiveremove($dir) {
        $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('data_directory');
        $structure = glob(wpjobportalphplib::wpJP_rtrim($dir, "/").'/*');
        if (is_array($structure)) {
            foreach($structure as $file) {
                if (is_dir($file)){
                    if($file != $dir."/".$data_directory && $file != $dir."/languages")
                    $this->recursiveremove($file);
                }elseif (is_file($file)){
                    if(file_exists($file)){
                        if(is_writable($file)){
                            if(!unlink($file)){
                            }else{

                            }
                        }else{

                        }
                    }else{

                    }
                }
            }
        }
        if(file_exists($dir) && count(glob("$dir/*")) === 0 )
        rmdir($dir);
    }

    function makeDir($path){
        if (!file_exists($path)){
            mkdir($path, 0755);
            $ourFileName = $path.'/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file ($ourFileName)");
            fclose($ourFileHandle);
        }
    }

    function getWpUsersList() {
        $query = "SELECT id,CONCAT(user_login,' ( ',display_name,' ) - ',id) AS text  FROM `" . wpjobportal::$_db->prefix . "users`";
        $users = wpjobportal::$_db->get_results($query);
        $data[0] = (object) array('id' => 0, 'text' => esc_html(__('Select User', 'wp-job-portal')));
        foreach ($users as $user) {
            $data[] = $user;
        }
        return $data;
    }

    function addMissingUsers(){
        // wpuid column does not esist in the table. it was showing a query error in log
        $missingUser = 0;
        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "users`";
        $users = wpjobportal::$_db->get_results($query);
        $wpUsers = array();
        $jsstUsers = array();
        foreach ($users as $key => $user) {
            $wpUsers[] = $user->id;
        }
        $query = " SELECT uid AS wpuid FROM `" . wpjobportal::$_db->prefix . "wj_portal_users`";
        $users = wpjobportal::$_db->get_results($query);
        foreach ($users as $key => $user) {
            $jsstUsers[] = $user->wpuid;
        }

        $missingUsers = array_diff($wpUsers,$jsstUsers);
        foreach ($missingUsers as $missingUser) {
            $query = "SELECT count(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = " . $missingUser;
            $total = wpjobportal::$_db->get_var($query);
            if ($total == 0) {
                $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "users` WHERE id = " . $missingUser;
                $user = wpjobportal::$_db->get_row($query);                
                if (isset($user)) {
                    $row = WPJOBPORTALincluder::getJSTable('users');
                    $data['uid'] = $user->ID;
                    $data['first_name '] = $user->display_name;
                    $data['emailaddress'] = $user->user_email;
                    $data['issocial'] = 0;
                    $data['socialid'] = null;
                    $data['status'] = 1;
                    $data['created'] = date_i18n('Y-m-d H:i:s');
                    $data = wpjobportal::wpjobportal_sanitizeData($data);
                    $row->bind($data);
                    $row->store();
                    $missingUser = 1;
                }
            }
        }
        if ($missingUser == 1) {
            //JSSTmessage::setMessage(esc_html(__('Missing user(s) added successfully!', 'wp-job-portal')), 'updated');
        } else {
            //JSSTmessage::setMessage(esc_html(__('No missing user found!', 'wp-job-portal')), 'error');
        }
        return;
    }

    function installSampleDataTemplateJobPortal() {
        // $product_type = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
        // if($flag == 'p'){
        //     return 0;
        // }elseif($flag == 'f'){
        //     if($product_type != 'free'){
        //         return 0;
        //     }
        //     $pro  = 0;
        // }elseif($flag == 'ftp'){
        //         return 0;
        // }
        // Check for the rev slider
        $wp_upload_dir = wp_upload_dir();
        if (file_exists(get_template_directory() . "/framework/plugins/sample-data.zip")) {
            do_action('wpjobportal_load_wp_pcl_zip');
            $archive = new PclZip(get_template_directory() . "/framework/plugins/sample-data.zip");
            $v_list = $archive->extract($wp_upload_dir["basedir"]);
        }
        if( ! function_exists("__update_post_meta")){
            function __update_post_meta( $post_id, $field_name, $value = "" ){
                if ( empty( $value ) OR ! $value ){
                    delete_post_meta( $post_id, $field_name );
                }elseif ( ! get_post_meta( $post_id, $field_name ) ){
                    add_post_meta( $post_id, $field_name, $value );
                }else{
                    update_post_meta( $post_id, $field_name, $value );
                }
            }
        }
        if( ! function_exists("uploadPostFeatureImage")){
            function uploadPostFeatureImage($filename,$parent_post_id){
                // Check the type of file. We"ll use this as the "post_mime_type".
                $filetype = wp_check_filetype( wpjobportalphplib::wpJP_basename( $filename ), null );
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    "guid"           => $wp_upload_dir["url"] . "/" . wpjobportalphplib::wpJP_basename( $filename ),
                    "post_mime_type" => $filetype["type"],
                    "post_title"     => wpjobportalphplib::wpJP_preg_replace( "/\.[^.]+$/", "", wpjobportalphplib::wpJP_basename( $filename ) ),
                    "post_content"   => "",
                    "post_status"    => "inherit"
                );
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                do_action('wpjobportal_load_wp_image');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                set_post_thumbnail( $parent_post_id, $attach_id );
            }
        }
        $jp_pages = array();
        // Home
        $new_page_title = "Home";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jp_pages["home"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["home"] = wp_insert_post($new_page);
        }
        update_post_meta($jp_pages["home"], "_wp_page_template", $new_page_template);
        update_post_meta($jp_pages["home"], "jp_show_header", 2);
        // there is only one homepage for now
        // Home 1
        // $new_page_title = "Home 1";
        // $new_page_template = "templates/template-homepage.php";
        // $page_check = get_page_by_title($new_page_title);
        // $new_page = array(
        //         "post_type" => "page",
        //         "post_title" => $new_page_title,
        //         "post_content" => "",
        //         "post_status" => "publish",
        //         "post_author" => 1,
        //         "post_parent" => 0,
        // );
        // if(!isset($page_check->ID)){
        //     $jp_pages["home1"] = wp_insert_post($new_page);
        // }else{
        //     $new_page["post_title"] = "Job Portal ".$new_page_title;
        //     $jp_pages["home1"] = wp_insert_post($new_page);
        // }
        // update_post_meta($jp_pages["home1"], "_wp_page_template", $new_page_template);
        // update_post_meta($jp_pages["home1"], "jp_show_header", 2);

        $wp_upload_dir = wp_upload_dir();
        /* end of homepages */
        // Price table
        $new_page_title = "Pricing Table";
        $new_page_content = '<div class="wpj-jp-price-box-wrp"><div class="container"><div class="row"><div class="col-md-4"><div class="wpj-jp-price-item"><div class="wpj-jp-price-item-top"><h3 class="wpj-jp-price-heading">Lite Plan</h3><div class="wpj-jp-price-subheading">Individuals and small teams</div></div><div class="wpj-jp-price-item-mid"><ul class="wpj-jp-price-feat-list"><li>10 Jobs</li><li>5 Featured Jobs</li><li>10 Companies</li><li>5 Featured Companies</li></ul></div><div class="wpj-jp-price-item-btm"><div class="wpj-jp-price-for">Per Month</div><div class="wpj-jp-price-amount">$50</div><div class="wpj-jp-price-action"><a class="wpj-jp-price-act-btn" href="#">Select Plan</a></div><div class="wpj-jp-price-validity-date">7 days money back guaranty</div></div></div></div><div class="col-md-4"><div class="wpj-jp-price-item"><div class="wpj-jp-price-item-top"><h3 class="wpj-jp-price-heading">Pro Plan</h3><div class="wpj-jp-price-subheading">Individuals and small teams</div></div><div class="wpj-jp-price-item-mid"><ul class="wpj-jp-price-feat-list"><li>50 Jobs</li><li>10 Featured Jobs</li><li>50 Companies</li><li>10 Featured Companies</li></ul></div><div class="wpj-jp-price-item-btm"><div class="wpj-jp-price-for">Per Month</div><div class="wpj-jp-price-amount">$100</div><div class="wpj-jp-price-action"><a class="wpj-jp-price-act-btn" href="#">Select Plan</a></div><div class="wpj-jp-price-validity-date">12 days money back guaranty</div></div></div></div><div class="col-md-4"><div class="wpj-jp-price-item"><div class="wpj-jp-price-item-top"><h3 class="wpj-jp-price-heading">Enterprise Plan</h3><div class="wpj-jp-price-subheading">Individuals and small teams</div></div><div class="wpj-jp-price-item-mid"><ul class="wpj-jp-price-feat-list"><li>100 Jobs</li><li>30 Featured Jobs</li><li>100 Companies</li><li>30 Featured Companies</li></ul></div><div class="wpj-jp-price-item-btm"><div class="wpj-jp-price-for">Per Month</div><div class="wpj-jp-price-amount">$200</div><div class="wpj-jp-price-action"><a class="wpj-jp-price-act-btn" href="#">Select Plan</a></div><div class="wpj-jp-price-validity-date">15 days money back guaranty</div></div></div></div></div></div></div>';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jp_pages["pricing_table"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["pricing_table"] = wp_insert_post($new_page);
        }
        update_post_meta($jp_pages["pricing_table"], "jp_show_header", 1);
        update_post_meta($jp_pages["pricing_table"], "_wp_page_template", $new_page_template);
    // job portal pages
        $page_array[1] = "Jobseeker Control Panel";
        $page_array[2] = "Newest Jobs";
        $page_array[3] = "Search Job";
        $page_array[4] = "Jobs By Category";
        $page_array[5] = "Shortlisted Jobs";
        $page_array[6] = "Add Resume";
        $page_array[7] = "My Resume";
        $page_array[8] = "My Applied Jobs";
        $page_array[9] = "Job Alert";
        $page_array[10] = "All Companies";

        //$page_array[18] = "Jobseeker Stats";
        $page_array[13] = "Employer Control Panel";
        $page_array[14] = "Add Company";
        $page_array[15] = "My Companies";
        $page_array[16] = "Add Job";
        $page_array[17] = "My Jobs";
        $page_array[19] = "Resume Search";
        $page_array[21] = "Resume By Category";

        $page_array[24] = "Login";
        $page_array[23] = "Employer Registration";
        $page_array[12] = "Jobseeker Registration";
        //$page_array[36] = "Thank You";
        foreach ($page_array as $key => $value) {
            // $value_string = wpjobportalphplib::wpJP_strtolower($value);
            // $value_string = sanitize_title($value_string);
            $value_string = wpjobportalphplib::wpJP_strtolower($value);
            $value_string = wpjobportalphplib::wpJP_str_replace(" ","_",$value_string);
            $new_page_title = $value;
            $new_page_content = '[vc_row][vc_column][jp_job_portal_theme_pages page page="'.$key.'"][/vc_column][/vc_row]';
            $new_page_template = "templates/template-fullwidth.php";
            $page_check = get_page_by_title($new_page_title);
            $new_page = array(
                    "post_type" => "page",
                    "post_title" => $new_page_title,
                    "post_content" => $new_page_content,
                    "post_status" => "publish",
                    "post_author" => 1,
                    "post_parent" => 0,
            );
            if(!isset($page_check->ID)){
                $jp_pages[$value_string] = wp_insert_post($new_page);
            }else{
                $new_page["post_title"] = "Job Portal ".$new_page_title;
                $jp_pages[$value_string] = wp_insert_post($new_page);
            }
            update_post_meta($jp_pages[$value_string], "jp_show_header", 1);
            update_post_meta($jp_pages[$value_string], "_wp_page_template", $new_page_template);
        }
    // job portal pages end
        // News & Rumors
        $new_page_title = "News & Rumors";
        $new_page_content = '[vc_row][vc_column][jp_news_and_rumors style="1"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-news_and_rumors.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jp_pages["news_and_rumors"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["news_and_rumors"] = wp_insert_post($new_page);
        }
        update_post_meta($jp_pages["news_and_rumors"], "jp_show_header", 1);
        update_post_meta($jp_pages["news_and_rumors"], "_wp_page_template", $new_page_template);
        // FAQ
        $new_page_title = "FAQ";
        $new_page_content = '[vc_row][vc_column][vc_tta_accordion active_section="1" el_class="wpj-jp-faq-wrp"][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951130596-88b25e02-157b" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951220884-2db479be-60ff" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951219563-3b45a760-05c4" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951218665-3ded33fe-07ba" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951217730-cfcd0f42-e20f" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951216882-7e2f71dd-8b45" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951215858-83a26644-c79c" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1607951214211-c2266b4e-9781" el_class="wpj-jp-faq-item"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][/vc_tta_accordion][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jp_pages["faq"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["faq"] = wp_insert_post($new_page);
        }
        update_post_meta($jp_pages["faq"], "jp_show_header", 1);
        update_post_meta($jp_pages["faq"], "_wp_page_template", $new_page_template);
       // Our Team
        $new_page_title = "Our Team";
        $new_page_content = '[vc_row][vc_column][jp_team_member per_row="3" posts_per_page="3" heading="Leader Ship Of Job Portal"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jp_pages["ourteam"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["ourteam"] = wp_insert_post($new_page);
        }
        update_post_meta($jp_pages["ourteam"], "_wp_page_template", $new_page_template);
        update_post_meta($jp_pages["ourteam"], "jp_show_header", 1);
        // Contact Us
        $new_page_title = "Contact Us";
        $new_page_content = "";
        $new_page_template = "templates/template-contactus.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jp_pages["contact_us"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["contact_us"] = wp_insert_post($new_page);
        }
        update_post_meta($jp_pages["contact_us"], "_wp_page_template", $new_page_template);
        update_post_meta($jp_pages["contact_us"], "jp_show_header", 1);
        // Blog Page
        $new_page_title = "Blog";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        if(!isset($page_check->ID)){
            $jp_pages["blog"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Portal ".$new_page_title;
            $jp_pages["blog"] = wp_insert_post($new_page);
        }
        update_option("page_for_posts", $jp_pages["blog"]);
    // Update home page contents
        //Home
//        $new_page_content = '[vc_row][vc_column][jp_job_search wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="7" subtitle="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text"][/vc_column][/vc_row][vc_row][vc_column][jp_feature_3box][/vc_column][/vc_row][vc_row][vc_column][jp_jobs_category wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="2" category="1" category1="1" category2="1" category3="1" category4="1" category5="1" category6="1" category7="1"][/vc_column][/vc_row][vc_row][vc_column][jp_5count_box style="2" count1="587" count2="146" count3="919" count4="796"][/vc_column][/vc_row][vc_row][vc_column][jp_jobs wpjobportalpageid="'.$jp_pages["newest_jobs"].'" title1="Latest Jobs" noofjobs="6" style="2"][/vc_column][/vc_row][vc_row][vc_column][jp_job_hub_custom_link wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="6" title="REGISTER AS EMPLOYER" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut veniam ." description1="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut veniam ."][/vc_column][/vc_row][vc_row][vc_column][jp_price_tables style="2" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][jp_companies wpjobportalpageid="'.$jp_pages["newest_jobs"].'" companytype="1" style="1" bgcolor="#ffffff" title="Popular Companies" scrollstyle="2" speed="1" posts_per_page="10"][/vc_column][/vc_row][vc_row][vc_column][jp_shortdescription_with_btn][/vc_column][/vc_row]';
        $new_page_content = '[vc_row full_width="stretch_row_content" content_placement="middle" css=".vc_custom_1630562962294{background-image: url(/wp-content/uploads/2021/01/company-bg.png?id=1015) !important;}"][vc_column][jp_job_search wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="1" title="Simplest Pathway To Grab Your New Job" shortdescription="Design the ideal site for Employers and Jobseekers within seconds." category="13" category1="7" category2="10" category3="14" category4="1"][/vc_column][/vc_row][vc_row el_class="wpj-jp-hp-stats-mod-main"][vc_column][jp_number_stats style="1" stat_count1="1045" stat_count2="1045" stat_count3="1045" stat_count4="1045"][/vc_column][/vc_row][vc_row][vc_column][jp_portal_feature_box style="1" shortdescription="Create an account in a few steps and apply for a job that suits you." description1="Fill out a simple form and register yourself to meet with exciting chances." link1icon="far fa-user" description2="Our match meter indicates which jobs are relevant to your abilities and requests." link2icon="fas fa-search" description3="Don`t delay submitting your CV to apply for a job or get employed by firms." link3icon="far fa-address-card" link1="|||" link2="|||" link3=""][/vc_column][/vc_row][vc_row][vc_column][jp_latest_jobs style="1" wpjobportalpageid="'.$jp_pages["newest_jobs"].'" subheading="We offer" heading="job vacancies right now!" shortdescription="Here we listed the most recent and hottest jobs in tech and other popular fields, find the suitable job according to your skills." noofjobs="7" nooffjobs="3"][/vc_column][/vc_row][vc_row][vc_column][jp_portal_popular_area style="1" heading="Present Your Resume In Front Of leading Employers" shortdescription="Get in contact personally with hiring managers and recruiters." icon1="far fa-user" icon2="far fa-address-card" icon3="far fa-paper-plane"][/vc_column][/vc_row][vc_row][vc_column][jp_latest_resume wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="1" subheading="Over 1500.000 supporter of all over the world" heading="Fast Resume Submission" shortdescription="Every member of your website can submit their CV by filling out the resume form where they will be providing their basic and academic information & more." post_per_page="5" scrollstyle="1"][/vc_column][/vc_row][vc_row][vc_column][jp_company_portfolio wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="1" post_per_page="10" scrollstyle="1"][/vc_column][/vc_row][vc_row][vc_column][jp_news_and_rumors style="2" heading="Packed With Core Features" shortdescription="We researched a lot to provide superb and most required features for your website so you can easily manage Your website." posts_per_page="10"][/vc_column][/vc_row][vc_row][vc_column][jp_latest_companies wpjobportalpageid="'.$jp_pages["newest_jobs"].'" companytype="1" style="1" subheading="Over 1500.000 supporter of all over the world" heading="Companies We`ve Supported" posts_per_page="10" shortdescription="Companies we`ve helped recruit excellent applicants over the years." scrollstyle="1"][/vc_column][/vc_row][vc_row][vc_column][jp_call_to_action style="1" heading="Featured Companies" shortdescription="Companies that are featured on our site might recruit applicants." link1icon="far fa-file-alt" link2icon="fas fa-search" link1url="|||" link2url="|||"][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jp_pages["home"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        // //Home 1
        // $new_page_content = '[vc_row][vc_column][jp_job_search wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="8"][/vc_column][/vc_row][vc_row][vc_column][jp_latest_featured_jobs wpjobportalpageid="'.$jp_pages["newest_jobs"].'" title="Latest Jobs" heading="Featured Jobs" noofjobs="6"][/vc_column][/vc_row][vc_row][vc_column][jp_job_hub_custom_link wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="7" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo"][/vc_column][/vc_row][vc_row][vc_column][jp_latest_resume wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="5" title="Latest Resume" description="Here you can see most recent resume added by users.You are able to set how many resume will be displayed ,order them or chose the specific taxonomy terms of resume." post_per_page="5" scrollstyle="2" speed="1"][/vc_column][/vc_row][vc_row][vc_column][jp_5count_box style="6" count1="587" count2="146" count3="919" count4="796"][/vc_column][/vc_row][vc_row][vc_column][jp_job_hub_custom_link wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="8" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo"][/vc_column][/vc_row][vc_row][vc_column][jp_news_and_rumors style="1"][/vc_column][/vc_row][vc_row][vc_column][jp_companies wpjobportalpageid="'.$jp_pages["newest_jobs"].'" companytype="1" style="2" title="Popular Companies" posts_per_page="6"][/vc_column][/vc_row][vc_row][vc_column][jp_shortdescription_with_btn][/vc_column][/vc_row]';
        // $my_post = array(
        //     "ID"           => $jp_pages["home1"],
        //     "post_content" => $new_page_content,
        // );
        // wp_update_post( $my_post );
        // //Home 2
        // $new_page_content = '[vc_row video_bg="yes"][vc_column][vc_empty_space height="550px"][/vc_column][/vc_row][vc_row][vc_column][jp_job_search wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="2"][/vc_column][/vc_row][vc_row][vc_column][jp_latest_jobs_types_categories_cities wpjobportalpageid="'.$jp_pages["newest_jobs"].'" title="Latest Jobs" noofjobs="5" display_style="1" show_jobs_by_types="1" number_of_job_type="5" show_number_of_jobs_by_type="1" show_jobs_by_categories="1" number_of_categories="5" show_number_of_jobs_by_category="1" show_jobs_by_cities="1" number_of_cities="5" show_number_of_jobs_by_city="1"][/vc_column][/vc_row][vc_row][vc_column][jp_image_and_text_box_with_links][/vc_column][/vc_row][vc_row][vc_column][jp_latest_resume wpjobportalpageid="'.$jp_pages["newest_jobs"].'" style="4" title="Latest Resume" description="Here you can see most recent resume added by users.You are able to set how many resume will be displayed ,order them or chose the specific taxonomy terms of resume." post_per_page="5"][/vc_column][/vc_row][vc_row][vc_column][jp_news_and_rumors style="4"][/vc_column][/vc_row][vc_row][vc_column][jp_testimonial style="4" heading="Kind Words From Happy Candidates" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et ."][/vc_column][/vc_row][vc_row el_class="jsjb-jh-companies-carasole-without-bg"][vc_column][jp_companies wpjobportalpageid="'.$jp_pages["newest_jobs"].'" companytype="1" style="2" title="Popular Companies" posts_per_page="6"][/vc_column][/vc_row][vc_row][vc_column][jp_post_add style="1"][/vc_column][/vc_row]';
        // $my_post = array(
        //     "ID"           => $jp_pages["home2"],
        //     "post_content" => $new_page_content,
        // );
        // wp_update_post( $my_post );
        // Update WP Options
        $wp_page_array = array();
        foreach ($page_array as $key => $value) {
            $value_string = wpjobportalphplib::wpJP_strtolower($value);
            $value_string = wpjobportalphplib::wpJP_str_replace(" ","_",$value_string);
            $wp_page_array[$value_string] = $jp_pages[$value_string];
        }
        update_option("job-portal-layout", $wp_page_array);
        // ----------------Posts -------- //
        $jp_post_ids = array();
        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post2.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post3.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post1.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post5.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post4.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // ----------------Custom posts -------- //
        // News & Rumors
        $new_page_title = "Advertising For Your Business";
        $new_page_content = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for "lorem ipsum" will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).';
        $new_page = array(
                "post_type" => "jp_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_1.png";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Attract More Attention Sales";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<strong></strong>';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_2.png";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Top fun activities tips for you ";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_3.png";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);


        // Team members
        $new_page_title = "Member 4";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jp_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Front-end Developer");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_1.png";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 3";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jp_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Project Manager");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_2.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 2";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jp_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Team Leader");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_3.png";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 1";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jp_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Cheif executive office / CEO");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_4.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // Price Table
        $new_page_title = "Basic Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $jp_post_ids[] = $new_page_id;
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jp_price" , "$ 500");
        __update_post_meta($new_page_id, "jp_line1" , "1500 Credits");
        __update_post_meta($new_page_id, "jp_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jp_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jp_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jp_buynowlink" , "#");

        $new_page_title = "Business Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $jp_post_ids[] = $new_page_id;
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jp_price" , "$ 750");
        __update_post_meta($new_page_id, "jp_line1" , "2500 Credits");
        __update_post_meta($new_page_id, "jp_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jp_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jp_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jp_buynowlink" , "#");

        $new_page_title = "Complete Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $jp_post_ids[] = $new_page_id;
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jp_price" , "$ 1500");
        __update_post_meta($new_page_id, "jp_line1" , "6000 Credits");
        __update_post_meta($new_page_id, "jp_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jp_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jp_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jp_buynowlink" , "#");

        // TESTIMONIAL
        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis quam sit amet dolor fermentum, in porta nisi egestas. Nullam convallis laoreet gravida. Pellentesque sed.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_1.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Naro MathDoe";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at tempus velit. Aliquam et diam convallis, tempus ligula ut, placerat sem. Nulla condimentum nulla a.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_2.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "MARY DOE";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce accumsan vitae massa vel aliquet. Morbi sed nibh eget lectus consequat tempor. Aliquam erat volutpat. Nam.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_3.jpg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Robert Lafore";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eleifend lacinia enim at dapibus. Nam eget accumsan neque. Nam felis augue, egestas ut varius vel.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_4.jpeg";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam commodo laoreet neque, vitae facilisis quam eleifend a. In consectetur purus quis arcu dictum, sit amet.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_5.png";
        $parent_post_id = $new_page_id;
        $jp_post_ids[] = $parent_post_id;
        uploadPostFeatureImage($filename,$parent_post_id);
        // Pages and custom post are created Now create Menu ----------------

        update_option( "page_on_front", $jp_pages["home"] );
        update_option( "show_on_front", "page" );


        // custom menu widgets
        // $new_page_title = "Register As Job Seeker";
        // $new_page_content = "";
        // $new_page_template = "";
        // $page_check = get_page_by_title($new_page_title);
        // $new_page = array(
        //         "post_type" => "jp_menu_widget",
        //         "post_title" => $new_page_title,
        //         "post_content" => $new_page_content,
        //         "post_status" => "publish",
        //         "post_author" => 1,
        //         "post_parent" => 0,
        //         "wpj_mw_widget" => 1,
        //         "wpj_mw_register_btn_title" => "Register As Job Seeker",
        //         "wpj_mw_register_btn_link" => "#",
        //         "wpj_jp_widget_image_URL_two" => JOB_PORTAL_THEME_IMAGE.'/register-jobseeker.png',
        //         "wpj_mw_banner_description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
        // );
        // $menu_widget_1_id = wp_insert_post($new_page);
        //update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        // $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_5.png";
        // uploadPostFeatureImage($filename,$menu_widget_1_id);

        // custom menu widgets




         // Custom menu widget 3
        $new_page_title = "Register As Job Seeker";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_menu_widget",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $widget3_id = wp_insert_post($new_page);
        update_post_meta($widget3_id, "jp_menu_widget", 4);
        __update_post_meta($widget3_id, "wpj_mw_widget" , 1);
        __update_post_meta($widget3_id, "widget_type" , 1);
        __update_post_meta($widget3_id, "wpj_mw_register_btn_title" , "Register As Job Seeker");
        __update_post_meta($widget3_id, "wpj_jp_widget_image_URL_two", JOB_PORTAL_THEME_IMAGE.'/register-jobseeker.png');
        __update_post_meta($widget3_id, "wpj_mw_register_btn_link" , get_the_permalink($jp_pages["jobseeker_registration"]));
        __update_post_meta($widget3_id, "wpj_mw_banner_description" , "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.");



         // Custom menu widget 4
        $new_page_title = "Register As Employer";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jp_menu_widget",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $widget4_id = wp_insert_post($new_page);
        update_post_meta($widget4_id, "jp_menu_widget", 5);
        __update_post_meta($widget4_id, "wpj_mw_widget" , 1);
        __update_post_meta($widget4_id, "widget_type" , 1);
        __update_post_meta($widget4_id, "wpj_mw_register_btn_title" , "Register As Employer");
        __update_post_meta($widget4_id, "wpj_jp_widget_image_URL_two", JOB_PORTAL_THEME_IMAGE.'/register-employer.png');
        __update_post_meta($widget4_id, "wpj_mw_register_btn_link" , get_the_permalink($jp_pages["employer_registration"]));
        __update_post_meta($widget4_id, "wpj_mw_banner_description" , "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.");




        // $new_page_title = "Register as a Instructor";
        // $new_page_content = "";
        // $new_page_template = "";
        // $page_check = get_page_by_title($new_page_title);
        // $new_page = array(
        //         "post_type" => "jp_menu_widget",
        //         "post_title" => $new_page_title,
        //         "post_content" => $new_page_content,
        //         "post_status" => "publish",
        //         "post_author" => 1,
        //         "post_parent" => 0,
        //         "wpj_mw_widget" => 1,
        //         "wpj_mw_register_btn_title" => "Register As Employer",
        //         "wpj_jp_widget_image_URL_two" => JOB_PORTAL_THEME_IMAGE.'/register-employer.png',
        //         "wpj_mw_register_btn_link" => "#",
        //         "wpj_mw_banner_description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
        // );
        // $menu_widget_2_id = wp_insert_post($new_page);
        //update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        // $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_5.png";
        // uploadPostFeatureImage($filename,$menu_widget_2_id);

        // MENU
        // Check if the menu exists
        $menu_name = "Job Portal";
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        // If it doesn"t exist, let"s create it.
        if( !$menu_exists){
            $menu_id = wp_create_nav_menu($menu_name);

            $locations = get_theme_mod("nav_menu_locations");
            $locations["primary"] = $menu_id;
            set_theme_mod( "nav_menu_locations", $locations );

            $itemData =  array(
                "menu-item-object-id" => $jp_pages["home"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_home = wp_update_nav_menu_item($menu_id, 0, $itemData);
            // $itemData =  array(
            //     "menu-item-object-id" => $jp_pages["home1"],
            //     "menu-item-parent-id" => $parent_home,
            //     "menu-item-object" => "page",
            //     "menu-item-type"      => "post_type",
            //     "menu-item-status"    => "publish"
            //   );
            // wp_update_nav_menu_item($menu_id, 0, $itemData);
            // $itemData =  array(
            //     "menu-item-object-id" => $jp_pages["home2"],
            //     "menu-item-parent-id" => $parent_home,
            //     "menu-item-object" => "page",
            //     "menu-item-type"      => "post_type",
            //     "menu-item-status"    => "publish"
            //   );
            // wp_update_nav_menu_item($menu_id, 0, $itemData);
            // Job seeker

            $itemData =  array(
                "menu-item-title" => "Job Seeker",
                "menu-item-object-id" => $jp_pages["jobseeker_control_panel"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns" =>  "4",
                "menu-item-status"    => "publish"
            );
            $parent_jobseeker = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $parent_jobseeker, '_menu_item_numberofsubcolumns', 4 );
            $itemData =  array(
                "menu-item-title" => "Jobs",
                "menu-item-object-id" => $jp_pages["newest_jobs"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Search Job",
                "menu-item-object-id" => $jp_pages["search_job"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Jobs By Categories",
                "menu-item-object-id" => $jp_pages["jobs_by_category"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Add Resume",
                "menu-item-object-id" => $jp_pages["add_resume"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Resumes",
                "menu-item-object-id" => $jp_pages["my_resume"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "My Applied Jobs",
                "menu-item-object-id" => $jp_pages["my_applied_jobs"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Register As Jobseeker",
                "menu-item-object-id" => $widget3_id,
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "jp_menu_widget",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns"      => "3",
                "menu-item-status"    => "publish"
            );
            $submenuid = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $submenuid, '_menu_item_numberofsubcolumns', 3 );


            // employer
            $itemData =  array(
                "menu-item-title" => "Employer",
                "menu-item-object-id" => $jp_pages["employer_control_panel"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns"      => 4,
                "menu-item-status"    => "publish"
            );
            $parent_employer = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $parent_employer, '_menu_item_numberofsubcolumns', 4 );
            $itemData =  array(
                "menu-item-title" => "Add Job",
                "menu-item-object-id" => $jp_pages["add_job"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Jobs",
                "menu-item-object-id" => $jp_pages["my_jobs"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Resume Search",
                "menu-item-object-id" => $jp_pages["resume_search"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Resumes By Categories",
                "menu-item-object-id" => $jp_pages["resume_by_category"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Companies",
                "menu-item-object-id" => $jp_pages["my_companies"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Register As Employer",
                "menu-item-object-id" => $widget4_id,
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "jp_menu_widget",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns"      => 3,
                "menu-item-status"    => "publish"
            );
            $submenuid = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $submenuid, '_menu_item_numberofsubcolumns', 3 );

            $itemData =  array(
                "menu-item-title" => "Pages",
                "menu-item-object-id" => $jp_pages["blog"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_pages = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jp_pages["news_and_rumors"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jp_pages["faq"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jp_pages["blog"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jp_pages["pricing_table"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            // $itemData =  array(
            //     "menu-item-object-id" => $jp_pages["thank_you"],
            //     "menu-item-parent-id" => $parent_pages,
            //     "menu-item-object" => "page",
            //     "menu-item-type"      => "post_type",
            //     "menu-item-status"    => "publish"
            //   );
            // wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-object-id" => $jp_pages["ourteam"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jp_pages["contact_us"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            }
            $widget_positions = get_option("sidebars_widgets");
            // Woocommerce sidebar
                $widget_positions["woocommerce-sidebar"][] = "woocommerce_widget_cart-1";
                $widget_woocommerce_widget_cart_array[1] = array("title" => "My Cart");
                $widget_woocommerce_widget_cart_array["_multiwidget"] = 1;
                // Left sidebar
                $widget_positions["left-sidebar"][] = "search-1";
                $search_array[1] = array("title" => "Search");
                $search_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "recent-posts-1";
                $recent_posts_array[1] = array("title" => "Recent Posts", "number" => 5);
                $recent_posts_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "recent-comments-1";
                $recent_comments_array[1] = array("title" => "Recent Comments", "number" => 5);
                $recent_comments_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "archives-1";
                $archives_array[1] = array("title" => "Archives");
                $archives_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "categories-1";
                $categories_array[1] = array("title" => "Categories");
                $categories_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "meta-1";
                $meta_array[1] = array("title" => "Meta");
                $meta_array["_multiwidget"] = 1;
                // Right sidebar
                $widget_positions["right-sidebar"][] = "calendar-1";
                $calendar_array[1] = array("title" => "Calendar");
                $calendar_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "widget_wpj_recent_comments-1";
                $widget_wpj_recent_comments_array[1] = array("title" => "Job Portal Recent Comments", "count" => 2);
                $widget_wpj_recent_comments_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "widget_wpj_recent_posts-1";
                $widget_wpj_recent_posts_array[1] = array("title" => "Job Portal Recent Posts", "category" => "");
                $widget_wpj_recent_posts_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "nav_menu-1";
                $nav_menu_array[1] = array("title" => "Custom Menu", "nav_menu" => "");
                $nav_menu_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "pages-1";
                $pages_array[1] = array("title" => "Pages", "sortby" => "post_title");
                $pages_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "tag_cloud-1";
                $tag_cloud_array[1] = array("title" => "Tag Cloud", "taxonomy" => "post_tag");
                $tag_cloud_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "text-1";
                $text_array[1] = array("title" => "Text Heading", "text" => "Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum");
                $text_array["_multiwidget"] = 1;
                // News and rumors
                $widget_positions["news_and_rumors"][] = "search-2";
                $search_array[2] = array("title" => "Search");
                $search_array["_multiwidget"] = 1;
                $widget_positions["news_and_rumors"][] = "recent-posts-2";
                $recent_posts_array[2] = array("title" => "Recent Posts", "number" => 5);
                $recent_posts_array["_multiwidget"] = 1;
                // footer1
                // $custom_logo_id = get_theme_mod( 'custom_logo' );
                // $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                $logo_url = JOB_PORTAL_THEME_IMAGE.'/logo.png';
                // if(is_array($image) && !empty($image) && isset($image[0])){
                //     $logo_url =  $image[0];
                // }
                // if (!is_active_sidebar( 'footer1' ) ) { // check if widget was activated from template. to avoid dupilication
                    $widget_positions["footer1"][] = "widget_wpj_footeraboutus-1";
                    $widget_wpj_footeraboutus_array[1] = array("title" => "Job portal", "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.","logo"=>$logo_url);
                    $widget_wpj_footeraboutus_array["_multiwidget"] = 1;
                // }
                // footer2
                $widget_positions["footer2"][] = "widget_wpj_footerusefullinks-1";
                $widget_wpj_footerusefullinks_array[1] = array(
                    "title" => "For Job Seeker",
                    "title1"=>"Newest Jobs", "link1"=> get_the_permalink($jp_pages["newest_jobs"]),
                    "title3"=>"Search Job", "link3"=> get_the_permalink($jp_pages["search_job"]),
                    "title2"=>"Resume Search", "link2"=> get_the_permalink($jp_pages["resume_search"]),
                    "title4"=>"Shortlisted Jobs", "link4"=> get_the_permalink($jp_pages["shortlisted_jobs"]),
                    "title5"=>"All Companies", "link5"=> get_the_permalink($jp_pages["all_companies"]),
                    "title6"=>"", "link6"=> "#",
                    "title7"=>"", "link7"=> "#",
                    "title8"=>"", "link8"=> "#",
                    "title9"=>"", "link9"=> "#",
                    "title10"=>"", "link10"=> "#",
                    );
                $widget_wpj_footerusefullinks_array["_multiwidget"] = 1;
                // footer3
                $widget_positions["footer3"][] = "widget_wpj_footerusefullinks-2";
                $widget_wpj_footerusefullinks_array[2] = array(
                    "title" => "For Employer",
                    "title1"=>"My Jobs", "link1"=> get_the_permalink($jp_pages["my_jobs"]),
                    "title2"=>"Add Job", "link2"=> get_the_permalink($jp_pages["add_job"]),
                    "title3"=>"Resume Search", "link3"=> get_the_permalink($jp_pages["resume_search"]),
                    "title4"=>"My Companies", "link4"=> get_the_permalink($jp_pages["my_companies"]),
                    "title5"=>"", "link5"=> "#",
                    "title6"=>"", "link6"=> "#",
                    "title7"=>"", "link7"=> "#",
                    "title8"=>"", "link8"=> "#",
                    "title9"=>"", "link9"=> "#",
                    "title10"=>"", "link10"=> "#",
                    );
                $widget_wpj_footerusefullinks_array2["_multiwidget"] = 1;
                // $widget_positions["footer3"][] = "widget_jsjs_footercompaniesimages-1";
                // $widget_jsjs_footercompaniesimages_array[1] = array("title" => "Featured Companies", "companytype" => 1, "max_images"=>9, "column"=>3);
                // $widget_jsjs_footercompaniesimages_array["_multiwidget"] = 1;

                // footer4
                //if (!is_active_sidebar( 'footer4' ) ) { // check if widget was activated from template. to avoid dupilication
                    $widget_positions["footer4"][] = "widget_wpj_footercontactus-1";
                    $widget_wpj_footercontactus_array[1] = array("title" => "Contact Us", "email" => "jobportal@yourdomain.com", "address"=>"At vero eos et accusamus et iusto odio dignissimos", "phone"=>"+1234567890");
                    $widget_wpj_footercontactus_array["_multiwidget"] = 1;
                    update_option( "sidebars_widgets" , $widget_positions);
                //}

                update_option("widget_"."widget_woocommerce_widget_cart"  , $widget_woocommerce_widget_cart_array);
                update_option("widget_"."search"  , $search_array);
                update_option("widget_"."recent-posts"  , $recent_posts_array);
                update_option("widget_"."recent-comments"  , $recent_comments_array);
                update_option("widget_"."archives"  , $archives_array);
                update_option("widget_"."categories"  , $categories_array);
                update_option("widget_"."meta"  , $meta_array);
                update_option("widget_"."calendar"  , $calendar_array);
                update_option("widget_"."widget_wpj_recent_comments"  , $widget_wpj_recent_comments_array);
                update_option("widget_"."widget_wpj_recent_posts"  , $widget_wpj_recent_posts_array);
                update_option("widget_"."nav_menu"  , $nav_menu_array);
                update_option("widget_"."pages"  , $pages_array);
                update_option("widget_"."tag_cloud"  , $tag_cloud_array);
                update_option("widget_"."text"  , $text_array);
                update_option("widget_"."widget_wpj_footerusefullinks"  , $widget_wpj_footerusefullinks_array);
                //update_option("widget_"."widget_jsjs_footercompaniesimages"  , $widget_jsjs_footercompaniesimages_array);
                // if (!is_active_sidebar( 'footer4' ) ) { // check if widget was activated from template. to avoid dupilication
                    update_option("widget_"."widget_wpj_footercontactus"  , $widget_wpj_footercontactus_array);
                // }
                // if (!is_active_sidebar( 'footer1' ) ) { // check if widget was activated from template. to avoid dupilication
                    update_option("widget_"."widget_wpj_footeraboutus"  , $widget_wpj_footeraboutus_array);
                // }

                // update this array at last
                update_option( "sidebars_widgets" , $widget_positions);

                //
                // redux options// template options
                $redux_options_json = '{"animated_menu":"1", "fixed_menu":"1", "menu_login_logout":"1", "jobs_layout":"1", "footer_copyright_show":"1", "header_bg":"#378AD8", "primary_color":"#378AD8", "copyright_bg":"#378AD8"}';// json is generated from export redux options interface,
                $options = json_decode($redux_options_json, true);
                $redux_option = ReduxFrameworkInstances::get_instance('job_portal_theme_options');
                global $job_portal_theme_options;
                if(isset($job_portal_theme_options['header_bg']['hover'])){
                    $options['header_bg']['hover'] = $job_portal_theme_options['header_bg']['hover'];// to solve a merge array problem;
                }
                $options = array_merge($job_portal_theme_options,$options);
                $redux_option->set_options($options);

                // storing values of pages and posts
                update_option('job_portal_theme_demo_pages_ids',$jp_pages);
                update_option('job_portal_theme_demo_post_ids',$jp_post_ids);
                $demo_data_array = array();
                $demo_data_array['color'] = "#378AD8";
                update_option('job_portal_theme_demo_demo_specific_data',$demo_data_array);


                global $wpdb;
                $pageid = $wpdb->get_var("Select id FROM `" . $wpdb->prefix . "posts` WHERE post_name = 'js-support-ticket-controlpanel'");
                if(is_numeric($pageid) && $pageid > 0){
                    update_post_meta($pageid, "_wp_page_template", "templates/template-fullwidth.php");
                }
            // Update the configuration default page to Vehicles
                $query = "UPDATE `".$wpdb->prefix."wj_portal_config` SET configvalue = '".$jp_pages["newest_jobs"]."' WHERE configname = 'default_pageid'";
                $wpdb->query($query);
                update_option("rewrite_rules", "");
                die(' all code executed');
            return 1;
    }

    function removeJobPortalDemoData(){
        delete_option("widget_widget_woocommerce_widget_cart");
        delete_option("widget_search");
        delete_option("widget_recent-posts");
        delete_option("widget_recent-comments");
        delete_option("widget_archives");
        delete_option("widget_categories");
        delete_option("widget_meta");
        delete_option("widget_calendar");
        delete_option("widget_widget_wpj_recent_comments");
        delete_option("widget_widget_wpj_recent_posts");
        delete_option("widget_nav_menu");
        delete_option("widget_pages");
        delete_option("widget_tag_cloud");
        delete_option("widget_text");
        delete_option("widget_widget_wpj_footeraboutus");
        delete_option("widget_widget_wpj_footerusefullinks");
        delete_option("widget_widget_wpj_footercontactus");

        $widget_positions = get_option("sidebars_widgets");
        unset($widget_positions["footer1"]);
        unset($widget_positions["footer2"]);
        unset($widget_positions["footer3"]);
        unset($widget_positions["footer4"]);
        unset($widget_positions["left-sidebar"]);
        unset($widget_positions["right-sidebar"]);
        unset($widget_positions["news_and_rumors"]);

        update_option( "sidebars_widgets" , $widget_positions);

        $menu_name = "Job Portal";
        $del_flag = wp_delete_nav_menu($menu_name);
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        $pages_array = get_option('job_portal_theme_demo_pages_ids');
        if(!empty($pages_array) && is_array($pages_array) ){
            foreach ($pages_array as $key => $value) {
                wp_delete_post($value,true);
            }
        }

        $post_array = get_option('job_portal_theme_demo_post_ids');
        if(!empty($post_array) && is_array($post_array) ){
            foreach ($post_array as $key => $value) {
                wp_delete_post($value,true);
            }
        }
        return;
    }

}?>
