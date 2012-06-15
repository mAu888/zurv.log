<?php
namespace Zurv\Logger;

interface Stream {
  public function put($message);
}