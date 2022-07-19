<?php

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

$server = new Swoole\HTTP\Server("0.0.0.0", 8600);

$server->on("start", function (Server $server) {
  echo "Swoole http server is started at http://0.0.0.0:8600\n";
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
    $search_term = $request->post['search_term'];
    $query = "SELECT extracted_key_phrases_index_list.url_course_slug,extracted_key_phrases_index_list.course_name,extracted_key_phrases_index_list.course_name_slug,extracted_key_phrases_index_list.url_course_slug,extracted_key_phrases_index_list.course_name,extracted_key_phrases_index_list.course_name_slug,extracted_key_phrases_index_list.course_rating,extracted_key_phrases_index_list.institution_code,extracted_key_phrases_index_list.institution_name,extracted_key_phrases_index_list.institution_ranking,
       extracted_key_phrases_index_list.institution_continent, extracted_key_phrases_index_list.description,extracted_key_phrases_index_list.country,extracted_key_phrases_index_list.course_overview,extracted_key_phrases_index_list.discipline,extracted_key_phrases_index_list.course_type,extracted_key_phrases_index_list.graduate_level,extracted_key_phrases_index_list.attendance_type,extracted_key_phrases_index_list.learning_mode,extracted_key_phrases_index_list.enrollment_details,extracted_key_phrases_index_list.course_requirements,extracted_key_phrases_index_list.currency,extracted_key_phrases_index_list.standard_fee_payable,extracted_key_phrases_index_list.course_small_image,extracted_key_phrases_index_list.course_image,extracted_key_phrases_index_list.course_structure_breakdown,extracted_key_phrases_index_list.course_duration,extracted_key_phrases_index_list.course_duration_category,extracted_key_phrases_index_list.standard_fee_payable_usd,extracted_key_phrases_index_list.foreign_student_fee_payable_usd,extracted_key_phrases_index_list.course_code,extracted_key_phrases_index_list.accredited_by,extracted_key_phrases_index_list.accredited_by_acronym,extracted_key_phrases_index_list.accreditation_organization_url,extracted_key_phrases_index_list.maximum_scholarship_available,
        extracted_key_phrases_index_list.is_featured, extracted_key_phrases_index_list.standard_fee_billing_type,extracted_key_phrases_index_list.popularity,extracted_key_phrases_index_list.standard_first_year_fee_payable_usd,extracted_key_phrases_index_list.foreign_student_first_year_fee_payable_usd,extracted_key_phrases_index_list.objectID FROM extracted_key_phrases_index_list INNER  JOIN extracted_key_phrases ON extracted_key_phrases.course_code = extracted_key_phrases_index_list.course_code INNER  JOIN unique_extracted_key_phrases ON unique_extracted_key_phrases.selected_key_phrases_id =extracted_key_phrases.id";
    $andParts = array();
    if (!empty($search_term))
      $andParts[] = "unique_extracted_key_phrases.phrases  LIKE  '%" . $search_term . "%'";
    if (!empty($andParts))
      $query .= " WHERE " . implode(" AND ", $andParts);
    $result = $db->query($query);
    $result = json_encode($result);
    $response->header("Content-Type", "application/json");
    $response->end($result);
    
  } catch (\Throwable $e) {
    $response->end($e);
  }
  
});
$server->start();