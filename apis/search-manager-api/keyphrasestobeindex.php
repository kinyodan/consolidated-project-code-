<?php

use Swoole\HTTP\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

$server = new Swoole\HTTP\Server("0.0.0.0", 8420);
$server->on("start", function (Swoole\Http\Server $server) {
  echo "Swoole http server is started at http://127.0.0.1:8420\n";
});
$server->on("request", callback: function (Request $request, Response $response) {
  $db = new Co\MySQL();
  $database = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '',
    'database' => 'craydel_search_manager'
  ];
  $db->connect($database);
  try {
    
    $results = json_decode($request->post['data']);
    
    
    foreach ($results as $result) {
      $objectID = $result->objectID;
      $url_course_slug = $result->url_course_slug;
      $course_name = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$result->course_name);
      $course_name_slug = $result->course_name_slug;
      $course_rating = $result->course_rating;
      $institution_code = $result->institution_code;
      $institution_name = $result->institution_name;
      $institution_ranking = $result->institution_ranking;
      $institution_continent = $result->institution_continent;
      $description = $result->description;
      $country = $result->country;
      $course_overview = $result->course_overview;
      $discipline = $result->discipline;
      $course_type = $result->course_type;
      $graduate_level = $result->graduate_level;
      $attendance_type = $result->attendance_type;
      $learning_mode = $result->learning_mode;
      $enrollment_details = $result->enrollment_details;
      $course_requirements = $result->course_requirements;
      $currency = $result->currency;
      $standard_fee_payable = $result->standard_fee_payable;
      $course_small_image = $result->course_small_image;
      $course_image = $result->course_image;
      $course_structure_breakdown = $result->course_structure_breakdown;
      $course_duration = $result->course_duration;
      $course_duration_category = $result->course_duration_category;
      $standard_fee_payable_usd = $result->standard_fee_payable_usd;
      $foreign_student_fee_payable_usd = $result->foreign_student_fee_payable_usd;
      $course_code = $result->course_code;
      $accredited_by = $result->accredited_by;
      $accredited_by_acronym = $result->accredited_by_acronym;
      $accreditation_organization_url = $result->accreditation_organization_url;
      $maximum_scholarship_available = $result->maximum_scholarship_available;
      $is_featured = $result->is_featured;
      $standard_fee_billing_type = $result->standard_fee_billing_type;
      $popularity = $result->popularity;
      $standard_first_year_fee_payable_usd = $result->standard_first_year_fee_payable_usd;
      $foreign_student_first_year_fee_payable_usd = $result->foreign_student_first_year_fee_payable_usd;
      $db->query(sql: "INSERT INTO `extracted_key_phrases_index_list` (`objectID`, `url_course_slug`, `course_name`, `course_name_slug`, `course_rating`, `institution_code`, `institution_name`, `institution_ranking`, `institution_continent`, `description`, `country`, `course_overview`, `discipline`, `course_type`, `graduate_level`, `attendance_type`, `learning_mode`, `enrollment_details`, `course_requirements`, `currency`, `standard_fee_payable`, `course_small_image`, `course_image`, `course_structure_breakdown`, `course_duration`, `course_duration_category`, `standard_fee_payable_usd`, `foreign_student_fee_payable_usd`, `course_code`, `accredited_by`, `accredited_by_acronym`, `accreditation_organization_url`, `maximum_scholarship_available`, `is_featured`, `standard_fee_billing_type`, `popularity`, `standard_first_year_fee_payable_usd`, `foreign_student_first_year_fee_payable_usd`)
                                                                   VALUES ('$objectID', '$url_course_slug', '$course_name', '$course_name_slug', '$course_rating', '$institution_code', '$institution_name', '$institution_ranking', '$institution_continent', '$description', '$country', '$course_overview', '$discipline', '$course_type', '$graduate_level', '$attendance_type', '$learning_mode', '$enrollment_details', '$course_requirements', '$currency', '$standard_fee_payable', '$course_small_image', '$course_image', '$course_structure_breakdown', '$course_duration', '$course_duration_category', '$standard_fee_payable_usd', '$foreign_student_fee_payable_usd', '$course_code', '$accredited_by', '$accredited_by_acronym', '$accreditation_organization_url', '$maximum_scholarship_available', '$is_featured', '$standard_fee_billing_type', '$popularity', '$standard_first_year_fee_payable_usd', '$foreign_student_fee_payable_usd')");
    }
    
    $result = $db->query('SELECT objectID FROM extracted_key_phrases_index_list');
    $result = json_encode($result);
    $response->header("Content-Type", "application/json");
    $response->status(200);
    $response->end($result);
    
    
    
  } catch (\Throwable $e) {
    $response->end($e);
  }
  
});
$server->start();
