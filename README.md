# PaymentSpring PHP API Wrapper

## Description

[PaymentSpring](https://www.paymentspring.com/) is a credit card processing gateway with a developer friendly API. This API wrapper for PHP is officially supported by PaymentSpring. 

PaymentSpring API keys are required.  You can obtain your own by [registering for a free PaymentSpring test account](https://www.paymentspring.com/signup). 

Usage examples can be found in the `index.php` file. You can add in your own API keys in that file to test things out. To get more information on additional parameters available, visit the full [API docs](https://paymentspring.com/developers).

## Installation

The easiest way to user this wrapper is with composer. You can run this to add it to your composer.json.

*Add composer information*

Alternatively, you can clone this repository. You must require the `init.php` file wherever you're going to use the the wrapper. This is not required if you're using composer.

## Usage

### Set Your API Keys
```php
  // Replace these with your actual keys.
  \PaymentSpring\PaymentSpring::setApiKeys(YOUR_PUBLIC_KEY, YOUR_PRIVATE_KEY);
```
****
### Customers

#### List Customers
This takes no arguments.

```php
	\PaymentSpring\Customer::listCustomers();
```
#### Get a Single Customer
**Customer ID** integer

```php
	\PaymentSpring\Customer::getCustomer($customerID);
```
#### Create a Customer

**Customer Parameters** array

```php
	\PaymentSpring\Customer::createCustomer($customerDetails);
```
****
### Plans
#### List Plans
This takes no arguments.

```php
	\PaymentSpring\Plan::listPlans();
```
#### Subscribe Customer to Plan

   **Plan ID** integer   
   **Customer ID** integer    
   **Additional Parameters** array *optional*

```php
	\PaymentSpring\Plan::subscribeCustomer($planID, $customerID, $options);
```

#### Create and Subscribe Customer
For when you have a new customer and want to subscribe them to a plan at the same time.

   **Customer Details** array   
   **Plan ID** integer    
   **Additional Parameters** array *optional*

```php
	\PaymentSpring\Plan::createAndSubscribeCustomer(
		$planID, 
		$customerID, 
		$options
	);
```

****
### Charges

#### Charge Card

   **Charge Details/Parameters** array   
   **Amount in Cents** integer *(not required if included in parameters)*  


```php
	$amountInCents = 2500;
	$chargeDetails = array(
		"card_number" => "4111111111111111",
		"card_exp_month" => "01",
		"card_exp_year" => "19",
		"csc" => "123"
	);
	\PaymentSpring\Charge::chargeCard($chargeDetails, $amountInCents);
```
#### Charge Customer
   **Customer ID** integer   
   **Amount in Cents** integer 
   **Other Parameters** array *(optional)*
   
```php
	\PaymentSpring\Charge::chargeCustomer(
		$customerID, 
		$amountInCents, 
		$otherParameters
	);
```

#### Charge Token
*Note: The API wrapper doesn't have a built in way of creating or fetching tokens. You could, however, use the `makeRequest` function*

   **PaymentSpring Token** string   
   **Amount in Cents** integer 

```php
	\PaymentSpring\Charge::chargeToken($token, $amountInCents);
```
****
### Custom requests
For anything that's not covered in this, you can make custom requests.

This takes 2-3 arguments. 

   **Path to request** string  
   **Parameters** array  
   **Is Post?** boolean, default false



```php
	\PaymentSpring\PaymentSpring::makeRequest(
		"/receipts/templates", 
		array(
			  "receipt_text" => "Test template text [Amount]",            
			  "name" => "Basic Template",      
            "subject" => "thanks for the payment"
       ), 
		true
	);
```

