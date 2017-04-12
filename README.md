# PaymentSpring PHP Library

## Description

[PaymentSpring](https://www.paymentspring.com/) is the easiest way to accept payments online, powered by an API designed for developers. This is the official PHP library for the PaymentSpring API.

PaymentSpring API keys are required. [Sign up for a free sandbox account](https://www.paymentspring.com/signup) to get your API keys. 

Usage examples can be found in the `index.php` file. You can add your own API keys in that file to test things out. To see all of the available endpoints and parameters, [check out the API docs](https://paymentspring.com/developers).

## Installation

The easiest way to user this library is with composer.

Or, you can clone this repository. You must require the `init.php` file wherever you're going to use the this library. This is not required if you're using composer.

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
		$options
	);
```

#### Charge Token
*Note: This library doesn't currently have a built in way of creating or fetching tokens. You could, however, use the `makeRequest` function*

   **PaymentSpring Token** string   
   **Amount in Cents** integer   
   **Other Parameters** array *(optional)*
   
```php
	\PaymentSpring\Charge::chargeToken($token, $amountInCents, $options);
```
****
### Custom requests
For any requests that are not yet included in this library, you can make custom requests. To see all of the available endpoints and parameters, [check out the API docs](https://paymentspring.com/developers).

This takes 2-3 arguments. 

   **Path to request** string  
   **Parameters** array  
   **Is POST?** boolean, default false/GET



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

