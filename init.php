<?php

  // Initialize Payment Spring Singleton
  require(dirname(__FILE__) . '/lib/PaymentSpring.php');

  // API Endpoints
  require(dirname(__FILE__) . '/lib/Customer.php');
  require(dirname(__FILE__) . '/lib/Charge.php');
  require(dirname(__FILE__) . '/lib/Plan.php');
  require(dirname(__FILE__) . '/lib/Subscription.php');
