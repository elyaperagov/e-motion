<?php

namespace AZ;

class Session
{
  public static function start()
  {
    session_start();

    if (!isset($_SESSION['start_time'])) {
      $_SESSION['start_time'] = date("H:i:s Y");
    }
  }
}
