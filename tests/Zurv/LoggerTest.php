<?php
require_once dirname(__FILE__) . '/../../src/Zurv/Logger.php';
require_once dirname(__FILE__) . '/../../src/Zurv/Stream/Printer.php';
require_once dirname(__FILE__) . '/../../src/Zurv/Stream/File.php';

use Zurv\Logger;
use Zurv\Logger\Stream\Printer;
use Zurv\Logger\Stream\File;

class Zurv_LoggerTest extends PHPUnit_Framework_TestCase {
  /**
   * @var Zurv\Logger
   */
  protected $_logger;

  /**
   * DATA PROVIDERS
   */
  public static function logLevelProvider() {
    return array(
      array(Logger::DEBUG),
      array(Logger::INFO),
      array(Logger::NOTICE),
      array(Logger::WARN),
      array(Logger::ERROR),
      array(Logger::CRITICAL),
      array(Logger::ALERT),
      array(Logger::EMERGENCY)
    );
  }

  public function setUp() {
    $stream = new Printer();
    $this->_logger = new Logger($stream);

    $tmpDir = dirname(__FILE__) . '/../_tmp';
    mkdir($tmpDir);
  }

  public function tearDown() {
    $tmpDir = dirname(__FILE__) . '/../_tmp';
    if (is_dir($tmpDir)) {
      foreach (glob($tmpDir . '/*') as $file) {
        if ($file !== '.' && $file !== '..') {
          unlink($file);
        }
      }

      rmdir($tmpDir);
    }
  }

  protected function _startBuffer() {
    ob_start();
  }

  protected function _endBuffer() {
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }

  /**
   * @test
   * @dataProvider logLevelProvider
   */
  public function loggerPrintsLogStatementsFromGivenLogLevel($logLevel) {
    $this->_logger->setLogLevel($logLevel);

    $expected = 'Some log message';

    $this->_startBuffer();
    $this->_logger->log($expected, $logLevel);
    $actual = $this->_endBuffer();

    $this->assertEquals($expected, $actual, 'Logger shows info log');
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function loggerThrowsExceptionOnInvalidLogLevel() {
    $this->_logger->setLogLevel(123);
  }

  /**
   * @test
   */
  public function loggerLogsToMultipleStreamsOnSameLogLevel() {
    $logDir = dirname(__FILE__) . '/../_tmp';
    $this->_logger->addStream(new File($logDir . '/log.txt'));

    $expected = 'A random message';

    $this->_startBuffer();
    $this->_logger->log($expected);
    $actual = $this->_endBuffer();

    $this->assertEquals($expected, $actual, 'Printer called too');

    $this->assertTrue(file_exists($logDir . '/log.txt'));
    $this->assertEquals($expected . File::LINE_ENDING, file_get_contents($logDir . '/log.txt'), 'Check file content');
  }
}