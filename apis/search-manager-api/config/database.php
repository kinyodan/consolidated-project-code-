<?php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = 'craydel_course_manager';

class DatabaseConnection
{
  public function __construct()
  {
    
    $db = new Co\MySQL();
    $database = array(
      'host' => DB_HOST,
      'user' => DB_USER,
      'password' => DB_PASSWORD,
      'database' => DB_DATABASE
    );
    return $db->connect($database);
    //return $this->$db = $db;
  }
}
