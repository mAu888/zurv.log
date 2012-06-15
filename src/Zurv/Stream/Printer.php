<?php
namespace Zurv\Logger\Stream;

use Zurv\Logger\Stream;

require_once dirname(__FILE__) . '/../Stream.php';

class Printer implements Stream {
  public function put($message) {
    echo $message;
  }
}