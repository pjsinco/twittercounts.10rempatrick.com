<?php 

require_once('../vendor/simpletest/autorun.php');
require_once('../libraries/DB.php');


class TestOfDB extends UnitTestCase
{
  //public $db; 

  function __construct() {
    //$this->db = new DB();
  }


  function test_escape() {
    $this->assertEqual("'Hooligan'", DB::instance()->escape('Hooligan'));
  } 

  function test_in_table() {

    $this->assertTrue(DB::instance()->
      in_table('tc_user', "screen_name='TheDOmagazine'"));
    $this->assertTrue(DB::instance()->
      in_table('tc_user', "twitter_user_id='19262807'"));
    $this->assertFalse(DB::instance()->
      in_table('tc_user', "screen_name='justinbieber'"));

  }

  function test_select_rows_result_is_array() {
    $results = DB::instance()->select_rows('select * from tc_user');
    $this->assertIsA($results, 'array');
    //$this->expectError($this->db->select('select * from tcuser'));
  }

  function test_insert() {
    $random_id = $this->create_random_user();

    $this->assertTrue($random_id);
  }

  function test_update() {
    // 1. insert a new user with a random user_id
    $random_id = $this->create_random_user();

    // 2. update that row with a new user_id (increment it by 1)
    $data = array(
      'twitter_user_id' => $random_id + 1
    );

    $where_condition = 'WHERE twitter_user_id = ' . $random_id;

    $rows = DB::instance()->update_row('tc_user', $data, 
      $where_condition);

    // 3. run the test
    $this->assertEqual($rows, 1);
      
  }

  function test_update_with_null_value() {
    $random_id = $this->create_random_user();

    $data = array(
      'screen_name' => NULL
    );

    $where_condition = 'WHERE twitter_user_id = ' . $random_id;
    
    $rows = DB::instance()->update_row('tc_user', $data,
      $where_condition);
    
    $this->assertEqual($rows, 1);
  }

  // inserts a random user into tc_user
  // @return the twitter_user_id of the new random user
  private function create_random_user() {
    $random_id = rand(1000, 20000);
    $data = array(
      'twitter_user_id' => $random_id,
      'screen_name' => 'rand_' . substr($random_id, 0, 3)
    );

    $rows = DB::instance()->insert('tc_user', $data);
    if ($rows) {
      return $random_id;
    } else {
      return false; 
    }
  }
  




}

