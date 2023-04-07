<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCommonModel {

    function removeSpecialCharacter($string) {
        $string = sanitize_title($string);
        return $string;
    }

    function stringToAlias($string){
        $string = $this->removeSpecialCharacter($string);
        $string = strtolower(str_replace(' ', '-', $string));
        return $string;
    }

     function getTimeForView($time,$unit){
        $text = $time.' '.ucfirst($unit);
        if($time > 1){
            $text .= 's';
        }
        return $text;
    }

    function getGoogleMapApiAddress() {
        $filekey = file_get_contents(WPJOBPORTAL_PLUGIN_PATH.'/includes/google-map-js.inc');
        $key =wpjobportal::$_configuration['google_map_api_key'];
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $matcharray = array(
            '{PROTOCOL}' => $protocol,
            '{KEY}' => $key,
        );
        foreach ($matcharray AS $find => $replace) {
            $filekey = str_replace($find, $replace, $filekey);
        }
        return $filekey;
    }

    function getFancyPrice($price,$currentyid=0,$override_config=array()){
        $currency_align = isset($override_config['currency_align']) ? $override_config['currency_align'] : wpjobportal::$_configuration['currency_align'];
        $thousand_separator = isset($override_config['thousand_separator']) ? $override_config['thousand_separator'] : wpjobportal::$_configuration['thousand_separator'];
        $short_price = isset($override_config['short_price']) ? $override_config['short_price'] : wpjobportal::$_configuration['short_price'];
        if(isset($override_config['decimal_places'])){
            if($override_config['decimal_places'] === 'fit_to_currency' && $currentyid){
                $decimal_places = '';//strlen(WPJOBPORTALincluder::getJSModel('currency')->getCurrencySmallestUnit($currentyid))-1;
            }else{
                $decimal_places = $override_config['decimal_places'];
            }
        }else{
            $decimal_places = wpjobportal::$_configuration['decimal_places'];
        }

        $text = '';
        if($currency_align == 1 && $currentyid ){//left
            $text .= WPJOBPORTALincluder::getJSModel('currency')->getCurrencySymbol($currentyid);
        }

        if( $short_price ){
            $text .= $this->getShortFormatPrice($price);
        }else{
            $text .= number_format($price,$decimal_places,'.',$thousand_separator);
        }

        if($currency_align == 2 && $currentyid ){ //right
            $text .= WPJOBPORTALincluder::getJSModel('currency')->getCurrencySymbol($currentyid);
        }
        return $text;
    }

    function getShortFormatPrice($n, $precision = 1){
        if($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        }else if($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        }else if($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        }else if($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        }else{
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
      // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
      // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }
        return $n_format . $suffix;
    }
    /**
    * @param wp job portal Function's
    * @param Get Status
    */
    function setDefaultForDefaultTable($id, $tablename) {
        if (is_numeric($id) == false)
            return false;

        switch ($tablename) {
            case "jobtypes":
            case "jobstatus":
            case "heighesteducation":
            case "careerlevels":
            case "experiences":
            case "currencies":
            case "salaryrangetypes":
            case "categories":
            case "subcategories":
                if (self::checkCanMakeDefault($id, $tablename)) {
                    if ($tablename == "currencies")
                        $column = "default";
                    else
                        $column = "isdefault";
                    //DB class limitations
                    $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . $tablename . "` AS t SET t." . $column . " = 0 ";
                    wpjobportaldb::query($query);
                    $query = "UPDATE  `" . wpjobportal::$_db->prefix . "wj_portal_" . $tablename . "` AS t SET t." . $column . " = 1 WHERE id=" . $id;
                    if (!wpjobportaldb::query($query))
                        return WPJOBPORTAL_SET_DEFAULT_ERROR;
                    else
                        return WPJOBPORTAL_SET_DEFAULT;
                    break;
                }else {
                    return WPJOBPORTAL_UNPUBLISH_DEFAULT_ERROR;
                }
                break;
        }
    }

    function checkCanMakeDefault($id, $tablename) {
        if (!is_numeric($id))
            return false;
        switch ($tablename) {
            case 'jobtypes':
            case 'jobstatus':
            case 'heighesteducation':
            case 'categories':
                $column = "isactive";
                break;
            default:
                $column = "status";
                break;
        }
        $query = "SELECT " . $column . " FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . $tablename . "` WHERE id=" . $id;
        $res = wpjobportaldb::get_var($query);
        if ($res == 1)
            return true;
        else
            return false;
    }

    function getTranskey($option_name){
         $query = "SELECT `option_value` FROM " . wpjobportal::$_wpprefixforuser . "options WHERE option_name = '$option_name'";
         $transactionKey = wpjobportaldb::get_var($query);
         return $transactionKey;
    }

      function getDefaultValue($table) {

        switch ($table) {
            case "categories":
            case "jobtypes":
            case "jobstatus":
            case "shifts":
            case "heighesteducation":
            case "ages":
            case "careerlevels":
            case "experiences":
            case "salaryrange":
            case "salaryrangetypes":
            case "subcategories":
                $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . $table . "` WHERE isdefault=1";

                $default_id = wpjobportaldb::get_var($query);
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . $table . "`";

                    $min_id = wpjobportaldb::get_var($query);
                    return $min_id;
                }
            case "currencies":
                $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . $table . "` WHERE `default`=1";

                $default_id = wpjobportaldb::get_var($query);
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . $table . "`";

                    $min_id = wpjobportaldb::get_var($query);
                    return $min_id;
                }
                break;
        }
    }






    function setOrderingUpForDefaultTable($field_id, $table) {
        if (is_numeric($field_id) == false)
            return false;
        //DB class limitations
        if($table == 'categories'){
            $parentid = wpjobportal::$_db->get_var("SELECT parentid FROM `".wpjobportal::$_db->prefix."wj_portal_categories` WHERE id = ".$field_id);
            $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f1, " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f2
                        SET f1.ordering = f1.ordering + 1
                        WHERE f1.ordering = f2.ordering - 1 AND f1.parentid = ".$parentid."
                        AND f2.id = " . $field_id;
        }else{
            $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f1, " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f2
                        SET f1.ordering = f1.ordering + 1
                        WHERE f1.ordering = f2.ordering - 1
                        AND f2.id = " . $field_id;
        }
        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_UP_ERROR;
        }
        $query = " UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . $table . "
                    SET ordering = ordering - 1
                    WHERE id = " . $field_id;

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_UP_ERROR;
        }
        return WPJOBPORTAL_ORDER_UP;
    }

    function setOrderingDownForDefaultTable($field_id, $table) {
        if (is_numeric($field_id) == false)
            return false;
        //DB class limitations
        if($table == 'categories'){
            $parentid = wpjobportal::$_db->get_var("SELECT parentid FROM `".wpjobportal::$_db->prefix."wj_portal_categories` WHERE id = ".$field_id);
            $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f1, " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f2
                        SET f1.ordering = f1.ordering - 1
                        WHERE f1.ordering = f2.ordering + 1 AND f1.parentid = ".$parentid."
                        AND f2.id = " . $field_id;
        }else{
            $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f1, " . wpjobportal::$_db->prefix . "wj_portal_" . $table . " AS f2
                        SET f1.ordering = f1.ordering - 1
                        WHERE f1.ordering = f2.ordering + 1
                        AND f2.id = " . $field_id;
        }

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_DOWN_ERROR;
        }
        $query = " UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . $table . "
                    SET ordering = ordering + 1
                    WHERE id = " . $field_id;

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_DOWN_ERROR;
        }
        return WPJOBPORTAL_ORDER_DOWN;
    }

     function getMultiSelectEdit($id, $for) {
        if (!is_numeric($id))
            return false;
        $config = wpjobportal::$_config->getConfigByFor('default');
       $query = "SELECT city.id AS id, concat(city.cityName";
        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                break;
            case 'cs'://City, State
                $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                break;
            case 'cc'://City, Country
                $query .= " ,', ', country.name)";
                break;
            case 'c'://city by default select for each case
                $query .= ")";
                break;
        }
        $query .= " AS name ,city.latitude,city.longitude";
        switch ($for) {
            case 1:
                $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mcity";
                break;
            case 2:
                $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` AS mcity";
                break;
            case 3:
                $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobalertcities` AS mcity";
                break;
        }
        $query .=" JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city on city.id=mcity.cityid
                  JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country on city.countryid=country.id
                  LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state on city.stateid=state.id";
        switch ($for) {
            case 1:
                $query .= " WHERE mcity.jobid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 2:
                $query .= " WHERE mcity.companyid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 3:
                $query .= " WHERE mcity.alertid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
        }
        $result = wpjobportaldb::get_results($query);
        $json_array = json_encode($result);
        if (empty($json_array))
            return null;
        else
            return $json_array;
    }

    function getRequiredTravel() {
        $requiredtravel = array();
        $requiredtravel[] = (object) array('id' => 1, 'text' => __('Not Required', 'wp-job-portal'));
        $requiredtravel[] = (object) array('id' => 2, 'text' => __('25 Per', 'wp-job-portal'));
        $requiredtravel[] = (object) array('id' => 3, 'text' => __('50 Per', 'wp-job-portal'));
        $requiredtravel[] = (object) array('id' => 4, 'text' => __('75 Per', 'wp-job-portal'));
        $requiredtravel[] = (object) array('id' => 5, 'text' => __('100 Per', 'wp-job-portal'));
        return $requiredtravel;
    }

    function getRequiredTravelValue($value) {
        switch ($value) {
            case '1': return __('Not Required', 'wp-job-portal'); break;
            case '2': return __('25 Per', 'wp-job-portal'); break;
            case '3': return __('50 Per', 'wp-job-portal'); break;
            case '4': return __('75 Per', 'wp-job-portal'); break;
            case '5': return __('100 Per', 'wp-job-portal'); break;
        }
    }

    /**
    * @param wp job portal Function
    * @param Log Action's
    */

    function getLogAction($for) {
        $logaction = array();
        if ($for == 1) { //employer
            $logaction[] = (object) array('id' => 'add_company', 'text' => __('New company', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'featured_company', 'text' => __('Featured company', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'add_department', 'text' => __('New department', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'add_job', 'text' => __('New job', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'featured_job', 'text' => __('Featured job', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'resume_save_search', 'text' => __('Searched and saved resume', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'view_resume_contact_detail', 'text' => __('Viewed resume contact details', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'featured_company_timeperiod', 'text' => __('Featured company for time period', 'wp-job-portal'));
        }
        if ($for == 2) {
            $logaction[] = (object) array('id' => 'add_resume', 'text' => __('New resume', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'featured_resume', 'text' => __('Featured resume', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'add_cover_letter', 'text' => __('New cover letter', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'job_alert_lifetime', 'text' => __('Life time job alert', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'job_alert_time', 'text' => __('Job alert', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'job_alert_timeperiod', 'text' => __('Job alert for time', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'job_save_search', 'text' => __('Saved a job search', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'shortlist_job', 'text' => __('Job short listed', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'job_apply', 'text' => __('Applied for job', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'view_job_apply_status', 'text' => __('Viewed job status', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'view_company_contact_detail', 'text' => __('Viewed company contact detail', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'tell_a_friend', 'text' => __('Told a friend', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'job_save_filter', 'text' => __('Saved a job filter', 'wp-job-portal'));
            $logaction[] = (object) array('id' => 'fb_share', 'text' => __('Shared on social media', 'wp-job-portal'));
        }
        return $logaction;
    }

    function getMiniMax() {
        $minimax = array();
        $minimax[] = (object) array('id' => '1', 'text' => __('Minimum', 'wp-job-portal'));
        $minimax[] = (object) array('id' => '2', 'text' => __('Maximum', 'wp-job-portal'));
        return $minimax;
    }
    /**
    * @param wp job portal Function's
    * @param Get Yes Or No
    */

    function getYesNo() {
        $yesno = array();
        $yesno[] = (object) array('id' => '1', 'text' => __('Yes', 'wp-job-portal'));
        $yesno[] = (object) array('id' => '0', 'text' => __('No', 'wp-job-portal'));
        return $yesno;
    }

    /**
    * @param wp job portal Function's
    * @param Get gender
    */

    function getGender() {
        $gender = array();
        $gender[] = (object) array('id' => '1', 'text' => __('Male', 'wp-job-portal'));
        $gender[] = (object) array('id' => '2', 'text' => __('Female', 'wp-job-portal'));
        return $gender;
    }

    /**
    * @param wp job portal Function's
    * @param Get Status
    */

    function getStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Published', 'wp-job-portal'));
        $status[] = (object) array('id' => '0', 'text' => __('Unpublished', 'wp-job-portal'));
        return $status;
    }

    function getTotalExp(&$resumeid){
        ///To get Total Experience From Resume Section's
        $resume_id = $resumeid;
        $query ="SELECT resume.employer_from_date AS fromdate,resume.employer_to_date AS todate,resume.employer_current_status
                AS status FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS resume
                WHERE resumeid='$resume_id' ORDER BY ID ASC
                ";
        wpjobportal::$_data[3] = wpjobportaldb::get_results($query);
        $daystodate = wpjobportal::$_data[3];
        $totalYear = 0;
        $totalmonth = 0;
        $totaldays = 0;
        $html = '';
        for ($i=0; $i < count(wpjobportal::$_data[3]) ; $i++) {
            $status = $daystodate[$i]->status;
            $from = $daystodate[$i]->fromdate;
            $to   = $daystodate[$i]->todate;
            $diff = abs(strtotime($to) - strtotime($from));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $totalYear += $years;
            $totalmonth += $months;
            $totaldays += $days;
        }

        if(!empty($daystodate)){

            if($totalYear > 0){
                $html.= $totalYear;

            }
            if($totalYear > 0 && $totalmonth > 5){
                $html.= '.5+'.' '.'Years';
            }else if($totalYear>0 && $totalmonth<=5) {
                $html.= ' '.'+'.'Years';
            }else if ($totalYear<=0 && $totalmonth<5) {
                $html.='Less than 1 year';
            }
            else{
                $html.='Less than 1 year';
            }
        }
        else{
            $html.="Fresh";
        }
        return $html;
    }

    /**
    * @param wp job portal Function
    * @param Option's For Job Alert
    */

    function getOptionsForJobAlert() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Subscribed', 'wp-job-portal'));
        $status[] = (object) array('id' => '0', 'text' => __('Unsubscribed', 'wp-job-portal'));
        return $status;
    }

    function getQueStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Approved', 'wp-job-portal'));
        $status[] = (object) array('id' => '0', 'text' => __('Rejected', 'wp-job-portal'));
        return $status;
    }

    /**
    * @param wp job portal
    * Roles For Combo
    */

    function getListingStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Approved', 'wp-job-portal'));
        $status[] = (object) array('id' => '-1', 'text' => __('Rejected', 'wp-job-portal'));
        return $status;
    }

    /**
    * @param wp job portal
    * Roles For Combo
    */

    function getRolesForCombo() {
        $roles = array();
        $roles[] = (object) array('id' => '1', 'text' => __('Employer', 'wp-job-portal'));
        $roles[] = (object) array('id' => '2', 'text' => __('Job seeker', 'wp-job-portal'));
        return $roles;
    }

    /**
    * @param wp job portal
    * Fields Type's
    */

    function getFeilds() {
        $values = array();
        $values[] = (object) array('id' => 'text', 'text' => __('Text Field', 'wp-job-portal'));
        $values[] = (object) array('id' => 'textarea', 'text' => __('Text Area', 'wp-job-portal'));
        $values[] = (object) array('id' => 'checkbox', 'text' => __('Check Box', 'wp-job-portal'));
        $values[] = (object) array('id' => 'date', 'text' => __('Date', 'wp-job-portal'));
        $values[] = (object) array('id' => 'select', 'text' => __('Drop Down', 'wp-job-portal'));
        $values[] = (object) array('id' => 'emailaddress', 'text' => __('Email Address', 'wp-job-portal'));
        return $values;
    }

    /**
    * @param wp job portal
    * Radius Type
    */

    function getRadiusType() {
        $radiustype = array(
            (object) array('id' => '0', 'text' => __('Select One', 'wp-job-portal')),
            (object) array('id' => '1', 'text' => __('Meters', 'wp-job-portal')),
            (object) array('id' => '2', 'text' => __('Kilometers', 'wp-job-portal')),
            (object) array('id' => '3', 'text' => __('Miles', 'wp-job-portal')),
            (object) array('id' => '4', 'text' => __('Nautical Miles', 'wp-job-portal')),
        );
        return $radiustype;
    }

    /**
    * @param wp job portal
    * Rating
    */

     function getRating($rating){
        if(!is_numeric($rating)){
            $rating = 0;
        }
        $percent = ($rating/5)*100;
        $html ="<div class=\"wjportal-container-small\"" . ( " style=\"vertical-align:middle;display:inline-block;\"" ) . ">
            <ul class=\"wjportal-stars-small\">
                <li class=\"current-rating\" style=\"width:" . (int) $percent . "%;\"></li>
            </ul>
        </div>";
        return $html;
    }

       function getJobsStats_Widget($classname, $title, $showtitle, $employers, $jobseekers, $jobs, $companies, $activejobs, $resumes, $todaystats) {
        //listModuleJobs
        $curdate = date('Y-m-d');
        $data = array();
        if ($employers == 1) {
            $query = "SELECT count(user.id) AS totalemployer
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                WHERE user.roleid = 1";
            $data['employer'] = wpjobportaldb::get_var($query);
        }
        if ($jobseekers == 1) {
            $query = "SELECT count(user.id) AS totaljobseeker
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                WHERE user.roleid = 2";
            $data['jobseeker'] = wpjobportaldb::get_var($query);
        }
        if ($jobs == 1) {
            $query = "SELECT count(job.id) AS totaljobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                WHERE job.status = 1 ";
            $data['totaljobs'] = wpjobportaldb::get_var($query);
        }
        if ($companies == 1) {
            $query = "SELECT count(company.id) AS totalcomapnies
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                WHERE company.status = 1 ";
            $data['totalcompanies'] = wpjobportaldb::get_var($query);
        }
        if ($activejobs == 1) {
            $query = "SELECT count(job.id) AS totalactivejobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $curdate . " AND DATE(job.stoppublishing) >= " . $curdate;
            $data['tatalactivejobs'] = wpjobportaldb::get_var($query);
        }
        if ($resumes == 1) {
            $query = "SELECT count(resume.id) AS totalresume
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                WHERE resume.status = 1 ";
            $data['totalresume'] = wpjobportaldb::get_var($query);
        }

        if ($todaystats == 1) {
            if ($employers == 1) {
                $query = "SELECT count(user.id) AS todayemployer
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                    WHERE user.roleid = 1 AND DATE(user.created) = '" . $curdate."'";
                $data['todyemployer'] = wpjobportaldb::get_var($query);
            }
            if ($jobseekers == 1) {
                $query = "SELECT count(user.id) AS todayjobseeker
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                    WHERE user.roleid = 2 AND DATE(user.created) = '" . $curdate."'";
                $data['todyjobseeker'] = wpjobportaldb::get_var($query);
            }
            if ($jobs == 1) {
                $query = "SELECT count(job.id) AS todayjobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) = '" . $curdate."'";

                $data['todayjobs'] = wpjobportaldb::get_var($query);
            }
            if ($companies == 1) {
                $query = "SELECT count(company.id) AS todaycomapnies
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                    WHERE company.status = 1 AND DATE(company.created) = '" . $curdate."'";

                $data['todaycompanies'] = wpjobportaldb::get_var($query);
            }
            if ($activejobs == 1) {
                $query = "SELECT count(job.id) AS todayactivejobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) = '" . $curdate."'";
                $data['todayactivejobs'] = wpjobportaldb::get_var($query);
            }
            if ($resumes == 1) {
                $query = "SELECT count(resume.id) AS todayresume
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                    WHERE resume.status = 1 AND DATE(resume.created) = '" . $curdate."'";
                $data['todayresume'] = wpjobportaldb::get_var($query);
            }
        }
        return $data;
    }

    /**
    * @param wp job portal
    * Image Extension's
    */

    function checkImageFileExtensions($file_name, $file_tmp, $image_extension_allow) {
        $allow_image_extension = explode(',', $image_extension_allow);
        if ($file_name != "" AND $file_tmp != "") {
            $ext = $this->getExtension($file_name);
            $ext = strtolower($ext);
            if (in_array($ext, $allow_image_extension))
                return true;
            else
                return false;
        }
    }

    function checkDocumentFileExtensions($file_name, $file_tmp, $document_extension_allow) {
        $allow_document_extension = explode(',', $document_extension_allow);
        if ($file_name != '' AND $file_tmp != "") {
            $ext = $this->getExtension($file_name);
            $ext = strtolower($ext);
            if (in_array($ext, $allow_document_extension))
                return true;
            else
                return false;
        }
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fclose($ourFileHandle);
        }
    }

    function getJobtempModelFrontend() {
        $componentPath = JPATH_SITE . '/components/com_wpjobportal';
        require_once $componentPath . '/models/jobtemp.php';
        $jobtemp_model = new WPJOBPORTALModelJobtemp();
        return $jobtemp_model;
    }

    function getSalaryRangeView($type, $min, $max, $currency=""){
        $salary = '';
        $currencysymbol =  isset($currency) ? $currency : wpjobportal::$_config->getConfigValue('job_currency');
        $currency_align = wpjobportal::$_config->getConfigValue('currency_align');
        $min = number_format((float)$min,2);
        $max = number_format((float)$max,2);
        if($type == 1){
            $salary = __("Negotiable",'wp-job-portal');
        }else if($type == 2){
            if($currency_align == 1){ // Left align
                $salary = $currencysymbol . ' ' . $min;
            }else if($currency_align == 2) { // Right align
                $salary = $min . ' ' . $currencysymbol;
            }
        }else if($type == 3){
            if($currency_align == 1){ // Left align
                $salary = $currencysymbol . ' ' . $min . ' - ' . $max;
            }else if($currency_align == 2){ // Right align
                $salary = $min . ' - ' . $max . ' ' . $currencysymbol;
            }
        }

        if(!empty($salary)){
            return $salary;
        }
    }

    function getYearMonth($args=array()){
            $previousTimeStamp = date('Y-m-d',strtotime($args['originalDate']));
            $lastTimeStamp = date('Y-m-d',strtotime($args['currentDate']));
            $diff = abs(strtotime($previousTimeStamp) - strtotime($lastTimeStamp));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            return array('month'=>$months,'days'=>$days,'years'=>$years);
    }

    function getLocationForView($cityname, $statename, $countryname) {
        $location = $cityname;
        $defaultaddressdisplaytype = wpjobportal::$_configuration['defaultaddressdisplaytype'];
        switch ($defaultaddressdisplaytype) {
            case 'csc':
                if ($statename)
                    $location .= ', ' . $statename;
                if ($countryname)
                    $location .= ', ' . $countryname;
                break;
            case 'cs':
                if ($statename)
                    $location .= ', ' . $statename;
                break;
            case 'cc':
                if ($countryname)
                    $location .= ', ' . $countryname;
                break;
        }
        return $location;
    }

    function getUidByObjectId($actionfor, $id) {
        if (!is_numeric($id))
            return false;
        switch ($actionfor) {
            case'company':
                $table = 'wj_portal_companies';
                break;
            case'job':
                $table = 'wj_portal_jobs';
                break;
            case'resume':
                $table = 'wj_portal_resume';
                break;
        }
        $query = "SELECT uid FROM `" . wpjobportal::$_db->prefix . $table . "`WHERE id = " . $id;
        $result = wpjobportaldb::get_var($query);

        return $result;
    }

    public function makeFilterdOrEditedTagsToReturn($tags) {
        if (empty($tags))
            return null;
        $temparray = explode(',', $tags);
        $array = array();
        for ($i = 0; $i < count($temparray); $i++) {
            $array[] = array('id' => $temparray[$i], 'name' => $temparray[$i]);
        }
        return json_encode($array);
    }

    function saveNewInWPJOBPORTAL($data) {
        if (empty($data))
            return false;

        $allow_reg_as_emp = wpjobportal::$_config->getConfigurationByConfigName('showemployerlink');
        if($allow_reg_as_emp != 1){
            $data['roleid '] = 2;
        }
        if(isset($data['socialmedia']) && !empty($data['socialid'])){
            $data['uid'] = "";
            $data['socialmedia'] = $data['socialmedia'];
        } else {
            $currentuser = get_userdata(get_current_user_id());
            $data['socialid'] = '';
            $data['socialmedia'] = '';
            $data['first_name'] = $currentuser->first_name;
            $data['last_name'] = $currentuser->last_name;
            $data['emailaddress'] = $currentuser->user_email;
            $data['uid'] = $currentuser->ID;
        }
     
        $row = WPJOBPORTALincluder::getJSTable('users');
        $data['status'] = 1; // all user autoapprove when registered as WP Job Portal users
        $data['created'] = date('Y-m-d H:i:s');
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->check()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

	if (isset($_COOKIE['first_name'])){
		setcookie('first_name' , '' , time() + 0 , COOKIEPATH);
		if ( SITECOOKIEPATH != COOKIEPATH ){
		setcookie('first_name' , '' , time() + 0 , SITECOOKIEPATH);
		}
	}
	if (isset($_COOKIE['last_name'])){
		setcookie('last_name' , '', time() + 0 , COOKIEPATH);
		if ( SITECOOKIEPATH != COOKIEPATH ){
		setcookie('last_name' , '', time() + 0 , SITECOOKIEPATH);
		}
	}
	if (isset($_COOKIE['email'])){
		setcookie('email' , '', time() + 0 , COOKIEPATH);
		if ( SITECOOKIEPATH != COOKIEPATH ){
		setcookie('email' , '', time() + 0 , SITECOOKIEPATH);
		}
	}
        return WPJOBPORTAL_SAVED;
    }

    function parseID($id){
        if(is_numeric($id)) return $id;
        $id = explode('-', $id);
        $id = $id[count($id) -1];
        return $id;
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '') {
        if (!$senderName)
            $senderName = wpjobportal::$_configuration['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', function(){return "text/html";});
        $body = preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        $result = wp_mail($recevierEmail, $subject, $body, $headers, $attachments);
        return $result;
    }

    function jsMakeRedirectURL($module, $layout, $for, $cpfor = null){
        if(empty($module) AND empty($layout) AND empty($for))
            return null;

        $finalurl = '';
        if( $for == 1 ){ // login links
            $jsthisurl = wpjobportal::makeUrl(array('wpjobportalme'=>$module, 'wpjobportallt'=>$layout));
            $jsthisurl = base64_encode($jsthisurl);
            $finalurl = wpjobportal::makeUrl(array('wpjobportalme'=>'wpjobportal', 'wpjobportallt'=>'login', 'wpjobportalredirecturl'=>$jsthisurl));
        }

        return $finalurl;
    }

    function getCitiesForFilter($cities){
        if(empty($cities))
            return NULL;


        $cities = explode(',', $cities);
        $result = array();

        $defaultaddressdisplaytype = wpjobportal::$_config->getConfigurationByConfigName('defaultaddressdisplaytype');

        foreach ($cities as $city) {
            $query = "SELECT city.id AS id, concat(city.cityName";
            switch ($defaultaddressdisplaytype) {
                case 'csc'://City, State, Country
                    $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                    break;
                case 'cs'://City, State
                    $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                    break;
                case 'cc'://City, Country
                    $query .= " ,', ', country.name)";
                    break;
                case 'c'://city by default select for each case
                    $query .= ")";
                    break;
            }
            $query .= " AS name ";
            $query .= " FROM `".wpjobportal::$_db->prefix."wj_portal_cities` AS city
                        JOIN `".wpjobportal::$_db->prefix."wj_portal_countries` AS country on city.countryid=country.id
                        LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_states` AS state on city.stateid=state.id
                        WHERE country.enabled = 1 AND city.enabled = 1";
            $query .= " AND city.id =".$city;


            $result[] = wpjobportaldb::get_row($query);
        }
        if(!empty($result)){
            return json_encode($result);
        }else{
            return NULL;
        }
    }
    function getMessagekey(){
        $key = 'common';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function stripslashesFull($input){// testing this function/.
        if (is_array($input)) {
            $input = array_map(array($this,'stripslashesFull'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = stripslashes($input);
        }
        return $input;
    }

    function validateEmployerArea(){
        $employerAreaEnabled = wpjobportal::$_config->getConfigValue('disable_employer');
        if(!$employerAreaEnabled){
            throw new Exception( WPJOBPORTALLayout::setMessageFor(5,null,null,1) );
        }
        $cuser = WPJOBPORTALincluder::getObjectClass('user');
        if($cuser->isguest()){
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            $layout = WPJOBPORTALrequest::getLayout('wpjobportallt',null,'');
            $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL($module, $layout, 1);
            $linktext = __('Login','wp-job-portal');
            throw new Exception( WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1) );
        }
        if(!$cuser->isemployer()){
            if($cuser->isjobseeker()){
               throw new Exception( WPJOBPORTALLayout::setMessageFor(2,null,null,1) );
            }
            if(!$cuser->isWPJOBPortalUser()){
                $link = wpjobportal::makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal'));
                $linktext = __('Select role','wp-job-portal');
                throw new Exception( WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1) );
            }
        }
    }

    function getMessagesForAddMore($module){
        $linktext = __('You are Not Allowed To Add More than One '.$module.' ! Contact TO Adminstrator', 'wp-job-portal');
        wpjobportal::$_error_flag = true;
        throw new Exception(WPJOBPORTALLayout::setMessageFor(16,'',$module,1));
    }

    function getBuyErrMsg(){
        $link = wpjobportal::makeUrl(array('wpjobportalme'=>'package', 'wpjobportallt'=>'packages'));
        $linktext = __('Buy Package', 'wp-job-portal');
        wpjobportal::$_error_flag = true;
        wpjobportal::$_error_flag_message_for=15;
        throw new Exception(WPJOBPORTALLayout::setMessageFor(15,$link,$linktext,1));
    }


    function getCalendarDateFormat(){
        static $js_scriptdateformat;
        if ($js_scriptdateformat) {
            return $js_scriptdateformat;
        }
        if (wpjobportal::$_configuration['date_format'] == 'm/d/Y' || wpjobportal::$_configuration['date_format'] == 'd/m/y' || wpjobportal::$_configuration['date_format'] == 'm/d/y' || wpjobportal::$_configuration['date_format'] == 'd/m/Y') {
            $dash = '/';
        } else {
            $dash = '-';
        }
        $dateformat = wpjobportal::$_configuration['date_format'];
        $firstdash = strpos($dateformat, $dash, 0);
        $firstvalue = substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = strpos($dateformat, $dash, $firstdash);
        $secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
        $js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
        $js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
        $js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
        return $js_scriptdateformat;
    }

    function getProductDesc($id){
        $name = '';
        $parse = explode('-', $id);
        $moduleid = $parse[1];
        $configname = $parse[0];

        if(!empty($id)){
            switch ($configname) {
                case 'job_currency_department_perlisting':
                    //print_r(WPJOBPORTALincluder::getJSModel('departments')->getDepartmentById($moduleid));
                    break;
                case 'company_price_perlisting':
                    break;
                case 'company_feature_price_perlisting':
                    break;
                case 'job_currency_price_perlisting':
                    break;
                case 'jobs_feature_price_perlisting':
                    break;
                case 'job_resume_price_perlisting':
                    break;
                case 'job_featureresume_price_perlisting':
                    break;
                case 'job_jobalert_price_perlisting':
                    break;
                case 'job_resumesavesearch_price_perlisting':
                    $name = WPJOBPORTALincluder::getJSModel('resumesearch')->getResumeSearchName($moduleid);
                    # Resume Search Payment
                    break;
               default:
                    # code...
                    break;
            }
            return $name;
        }
    }

    function listModuleJobsStats($classname, $title, $showtitle, $employers, $jobseekers, $jobs, $companies, $activejobs, $resumes, $todaystats,$data){
        $my_html = '
            <div id="wpjobportals_mod_wrapper" class="wjportal-stats-mod"> ';
        if ($showtitle == 1) {
            $my_html .= '<div id="wpjobportals-mod-heading" class="wjportal-mod-heading"> ' . $title . '</div>';
        }
        $my_html .='
                <div id="wpjobportals-data-wrapper" class="' . $classname . ' wjportal-stats">';
        $curdate = date('Y-m-d');
        if ($employers == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Employer', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['employer'] . ')</span></div>';
        }
        if ($jobseekers == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Job seeker', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['jobseeker'] . ')</span></div>';
        }
        if ($jobs == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Jobs', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['totaljobs'] . ')</span></div>';
        }
        if ($companies == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Companies', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['totalcompanies'] . ')</span></div>';
        }
        if ($activejobs == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Active Jobs', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['tatalactivejobs'] . ')</span></div>';
        }
        if ($resumes == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Resume', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['totalresume'] . ')</span></div>';
        }
        if ($todaystats == 1) {
            if ($showtitle == 1) {
                $my_html .= '</div> <div id="wpjobportals-mod-heading" class="wjportal-mod-heading"> ' . __('Today') . ' ' . $title . '</div>';
            }
            $my_html .='
                <div id="wpjobportals-data-wrapper" class="' . $classname . ' wjportal-stats">';
            if ($employers == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Employer', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['todyemployer'] . ')</span></div>';
            }
            if ($jobseekers == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Job seeker', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['todyjobseeker'] . ')</span></div>';
            }
            if ($jobs == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Jobs', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['todayjobs'] . ')</span></div>';
            }
            if ($companies == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Companies', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['todaycompanies'] . ')</span></div>';
            }
            if ($activejobs == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Active Jobs', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['todayactivejobs'] . ')</span></div>';
            }
            if ($resumes == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . __('Resume', 'wp-job-portal') . ' <span class="wjportal-stats-num">(' . $data['todayresume'] . ')</span></div>';
            }
            $my_html .= '</div>';
        }

        $my_html .= '</div></div>';
        return $my_html;
    }

    function getSearchFormDataOnlySort($jstlay){
        if($jstlay == 'activitylog'){
            $val1 = 4;
        }else{
            $val1 = 6;
        }
        $jsjp_search_array = array();
        $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', $val1);
        $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);
        $jsjp_search_array['search_from_myapply_myjobs'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedOnlySortandOrder(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = filter_var($_COOKIE['jsjp_jobportal_search_data'], FILTER_SANITIZE_STRING);
            $wpjp_search_cookie_data = json_decode( base64_decode($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_myapply_myjobs']) && $wpjp_search_cookie_data['search_from_myapply_myjobs'] == 1){
            $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
            $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableOnlySortandOrder($jsjp_search_array,$jstlay){
        if($jstlay == 'activitylog'){
            $val1 = 4;
        }else{
            $val1 = 6;
        }
        wpjobportal::$_search['jobs']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : $val1;
        wpjobportal::$_search['jobs']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
    }

    function getServerProtocol(){
        static $protocol;
        if ($protocol) {
            return $protocol;
        }
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol;
    }

    function wpjp_isadmin(){
        if (current_user_can('manage_options')) {
            return true;
        } else {
            return false;
        }
    }

}
?>
