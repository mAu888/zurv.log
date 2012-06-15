<?php
namespace Zurv\Logger\Stream;

use Zurv\Logger\Stream;

require_once dirname(__FILE__) . '/../Stream.php';

class File implements Stream {

  const LINE_ENDING = "\r\n";

  protected $_file;

  public function __construct($file) {
    if (! file_exists($file)) {
      touch($file);
      $this->_file = $file;
    }
  }

  public function put($message) {
    $fp = fopen($this->_file, 'a');
    flock($fp, LOCK_EX);
    fwrite($fp, $message . self::LINE_ENDING);
    fclose($fp);
  }
}