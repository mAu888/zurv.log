<?php
namespace Zurv;

use \Zurv\Logger\Stream;

class Logger {
  const DEBUG = 1;
  const INFO = 2;
  const NOTICE = 4;
  const WARN = 8;
  const ERROR = 16;
  const CRITICAL = 32;
  const ALERT = 64;
  const EMERGENCY = 128;

  protected $_streams = array();

  public function __construct(Stream $stream = null, $level = self::INFO) {
    if (! is_null($stream)) {
      array_push($this->_streams, array('log_level' => $level, 'object' => $stream));
    }
  }

  public function setLogLevel($level, Zurv\Logger\Stream $stream = null) {
    $logValue = log10($level) / log10(2);
    if (false === filter_var($logValue, FILTER_VALIDATE_INT, array('min_range' => 0, 'max_range' => 7))) {
      throw new \InvalidArgumentException('Invalid log level');
    }

    // Set level
    foreach ($this->_streams as &$currStream) {
      if (is_null($stream) || $currStream['object'] === $stream) {
        $currStream['log_level'] = $level;
      }
    }
  }

  /**
   *
   */
  public function addStream(Stream $stream, $level = self::INFO) {
    if ($this->_streamExists($stream)) {
      // reset log level...
    }
    else {
      array_push($this->_streams, array(
        'log_level' => $level,
        'object' => $stream
      ));
    }
  }

  /**
   *
   */ 
  public function log($message, $level = self::INFO) {
    foreach ($this->_streams as $stream) {
      if (($stream['log_level'] & $level) > 0) {
        $stream['object']->put($message);
      }
    }
  }

  /**
   * 
   */
  protected function _streamExists(Stream $stream) {
    foreach ($this->_streams as $currStream) {
      if ($currStream['object'] === $stream) {
        return true;
      }
    }

    return false;
  }
}