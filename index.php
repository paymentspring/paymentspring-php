<?php
  require_once "init.php";

  # Change these to your keys
  \PaymentSpring\PaymentSpring::setApiKeys(
    "test_be01893a79b9e45b538342a2394db6b2a3d8734aa0b4d6f935fe404ad4",
    "test_9791cd9fcf894e5ad9d1eda50d"
  );
?>

<html>
  <head>
    <title>Payment Spring for PHP</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
  </head>

  <body>
    <h1>Payment Spring API Demo</h1>
    This demo page will use your actual test credentials to test dummy data.

    <h2>Plans</h2>
    <b>Get all plans</b>
    <pre>
      <code class="php hljs">
        $plans = \PaymentSpring\Plan::listPlans();      
      </code>
      Will give you: 
      <code class="json hljs">
        <?php print_r(\PaymentSpring\Plan::listPlans()); ?>      
      </code>
    </pre>

    <h2>Charges</h2>
    <b>Make a single charge</b>
    <pre>
      <code class="php">
        $chargeDetails = array(
          "card_number" => 4111111111111111,
          "card_exp_month" => 02,
          "card_exp_year" => 2020,
          "csc" => 123,
          "amount" => 2000
        );

        \PaymentSpring\Charge::chargeCard($chargeDetails);

        // Optionally, you can pass the amount into the function as well, this will overried whatever is inside the charge details
        // Remember, this still needs to be in cents

        \PaymentSpring\Charge::chargeCard($chargeDetails, 2000);
      </code>
    </pre>
    <?php if($_POST["_demo_single_charge"]): ?>
      <a href="">Reset</a>
      <?php
        $single_charge = $_POST["_demo_single_charge"];
        unset($_POST["_demo_single_charge"]); 
      ?>
      <pre>
        <code class="json hljs">
          <?php print_r(\PaymentSpring\Charge::chargeCard($single_charge)); ?> 
        </code>
      </pre>
    <?php endif; ?>
    Test this code with the form:
    <form method="post" name="_demo_single_charge"> 
      <input type="text" placeholder="CC Number" name="_demo_single_charge[card_number]" value="4111111111111111">
      <br>
      <input type="text" placeholder="Expire Month" name="_demo_single_charge[card_exp_month]" value="12">
      <br>
      <input type="text" placeholder="Expiry Year" name="_demo_single_charge[card_exp_year]" value="2020">
      <br>
      <input type="text" placeholder="CVV number" name="_demo_single_charge[csc]" value="123">
      <br>
      <i>Remember this is in cents, $20 == 2000</i>
      <br>
      <input type="text" placeholder="Amount in Cents" name="_demo_single_charge[amount]" value="2000">
      <br>
      <input type="submit" value="Charge Card">
    </form>

    
  </body>
</html>
