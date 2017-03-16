<?php

  // Initialize Payment Spring Singleton
  require(dirname(__FILE__) . '/payment-spring/PaymentSpring.php');

  // API Endpoints
  require(dirname(__FILE__) . '/payment-spring/Customer.php');
  require(dirname(__FILE__) . '/payment-spring/Charge.php');
  require(dirname(__FILE__) . '/payment-spring/Plan.php');
