<?php

  /*
   * Here is where all the login proccess happens
   * After being logged, you would use getUserData method to send data to $_SESSION to
   * retrieve later. Afterwards, you will redirect the user to the home page where he logged in
   */
  require_once("./includes/include.callback.php");

  $callback = new Callback\Callback("your app id", "your app secret");
  $callback->getUserData(["id, name"]);
  $callback->redirectTo("./success.php");
?>