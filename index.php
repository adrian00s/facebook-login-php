<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h1>Testing</h1>

  <?php

    /*
     * Here is where user starts. The button will be displayed.
     * Normally you would offer the change to login with your own auth system and alternatively
     * facebook login. I'll leave this empty just for the sake of example
     */

    require_once("includes/include.config.php");

    $config = new \Config\Config("your app id", "your app secret");
    $config->loginUrl("http://localhost:8012/login.php");

  ?>
</body>
</html>