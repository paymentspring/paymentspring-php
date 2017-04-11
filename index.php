<?php
  require_once "init.php";
  \PaymentSpring\PaymentSpring::setApiKeys("PUBLIC_KEY", "PRIVATE_KEY");
?>

<html>
  <head>
    <title>Payment Spring for PHP</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>

    <style type="text/css">
      body{
        margin: auto;
        max-width: 690px;
        font-family: sans-serif;
      }
      .api-section{
        border-bottom: 5px solid;
        margin: 10px 0;
      }
      .pretty-form{
        width: 50%;
        margin: 15px auto;
        padding: 10px;
        border: 2px solid; 
      }
      .pretty-form input{
        display: block;
        margin: 10px 0;
        width: 100%;
        padding: 5px;
        font-size: 15px;
      } 
    </style>
  </head>

  <body>
    <h1>Payment Spring API Demo</h1>
    This demo page will use your actual test credentials to test dummy data.

    <div class="api-section">
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

      <b>Subscribe a customer</b>
      <pre>
        <code class="php hljs">
          $planID = 5;
          $customerID = "bff79e"
          $options = array("ends_after" => 12, "bill_immediately" => true);
          $plans = \PaymentSpring\Plan::subscribeCustomer($planID, $customerID, $options);      
        </code>
      <pre>
      <?php if($_POST["_demo_subscription"]): ?>
        <?php
          $subscription = $_POST["_demo_subscription"];
          $options = $subscription["options"];
          $planID = $subscription["plan_id"];
          $customerID = $subscription["customer_id"];
        ?>
        <h3>Response:</h3>
        <a href="">Reset</a>
        <pre>
          <code class="json hljs">
            <?php print_r(\PaymentSpring\Plan::subscribeCustomer($planID, $customerID, $options)); ?> 
          </code>
        </pre>
      <?php endif; ?>
      Test this code with the form:
      <form method="post" action="#plans" class="pretty-form"> 
        <select name="_demo_subscription[options][frequency]">
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
        <select name="_demo_subscription[plan_id]">
          <?php $plans = \PaymentSpring\Plan::listPlans(); ?>
          <?php foreach($plans->list as $plan): ?> 
            <option value="<?php echo $plan->id?>"><?php echo $plan->name ?></option>
          <?php endforeach; ?>
        </select>
        <select name="_demo_subscription[customer_id]">
          <?php $customers = \PaymentSpring\Customer::listCustomers(); ?>
          <?php foreach($customers->list as $customer): ?> 
            <option value="<?php echo $customer->id?>"><?php echo $customer->last_name ?></option>
          <?php endforeach; ?>
        </select>
        <input type="submit" value="Subscribe Customer">
      </form>
    </div>

    <div class="api-section" id="charges">
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
        <?php
          $single_charge = $_POST["_demo_single_charge"];
          unset($_POST["_demo_single_charge"]); 
        ?>
        <h3>Response:</h3>
        <a href="">Reset</a>
        <pre>
          <code class="json hljs">
            <?php print_r(\PaymentSpring\Charge::chargeCard($single_charge)); ?> 
          </code>
        </pre>
      <?php endif; ?>
      Test this code with the form:
      <form method="post" action="#charges" class="pretty-form"> 
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
    </div>
    
    <div class="api-section" id="customers">
      <h2>Customers</h2>
      <b>Create a new customer</b>
      <pre>
        <code class="php">
          $customerDetails = array(
            "first_name" => Jane,
            "last_name" => Jacobs,
            "email" => "jjacobs@hotmail.net",
            "card_number" => 4111111111111111,
            "card_exp_month" => 02,
            "card_exp_year" => 2020,
            "csc" => 123
          );

          \PaymentSpring\Customer::createCustomer($customerDetails);
        </code>
      </pre>
      <?php if($_POST["_demo_new_customer"]): ?>
        <?php
          $new_customer = $_POST["_demo_new_customer"];
          unset($_POST["_demo_new_customer"]); 
        ?>
        <h3>Response:</h3>
        <a href="">Reset</a>
        <pre>
          <code class="json hljs">
            <?php print_r(\PaymentSpring\Customer::createCustomer($new_customer)); ?> 
          </code>
        </pre>
      <?php endif; ?>
      Test this code with the form:
      <form method="post" action="#customers" class="pretty-form"> 
        <input type="text" placeholder="First Name" name="_demo_new_customer[first_name]" value="John">
        <input type="text" placeholder="Last Name" name="_demo_new_customer[last_name]" value="Candy">
        <input type="email" placeholder="Email" name="_demo_new_customer[email]" value="jcandy@pronto.ca">
        <input type="text" placeholder="CC Number" name="_demo_new_customer[card_number]" value="4111111111111111">
        <input type="text" placeholder="Expire Month" name="_demo_new_customer[card_exp_month]" value="12">
        <input type="text" placeholder="Expiry Year" name="_demo_new_customer[card_exp_year]" value="2020">
        <input type="text" placeholder="CVV number" name="_demo_new_customer[csc]" value="123">
        <input type="submit" value="Create Customer">
      </form>
    </div>
  </body>
</html>
