<?php
  require_once "init.php";
?>

<html>
  <head>
  </head>

  <body>
    <?php
      \PaymentSpring\PaymentSpring::setApiKeys("test_********************5e3138", "test_cd1351721bfb5816fa9c5d51e8a474edafc0a242458b7b7d04fcef3388");
      new \PaymentSpring\Customer("john");
    ?>
  </body>
</html>
