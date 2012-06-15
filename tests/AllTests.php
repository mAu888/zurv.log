<?php
require_once dirname(__FILE__) . '/Zurv/LoggerTest.php';

class AllTests {
  static public function suite() {
    $suite = new PHPUnit_Framework_TestSuite('All tests');

    $suite->addTestSuite('Zurv_LoggerTest');

    return $suite;
  }
}