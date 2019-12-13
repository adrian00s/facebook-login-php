<?php

  /*
 * This is the success page user will see when logged in.
 */

  session_start();

  # Here's all user data
  //print_r($_SESSION["user_data"]);

  echo "successfully logged in <br>";

  echo <<<USERDATA

  Your name is: {$_SESSION["user_data"]["name"]} <br>
  Your user id is: {$_SESSION["user_data"]["id"]} 

USERDATA;

?>