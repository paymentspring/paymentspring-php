## PaymentSpring PHP API

*Full Docs Coming Soon*

### Description

[PaymentSpring](https://www.paymentspring.com/) is a credit card processing gateway with a developer friendly API.  This plugin is officially supported by PaymentSpring and will allow you to send credit card data in a secure manner from a Gravity Forms form.

This plugin will not work if Gravity Forms is not installed.  You will need to purchase your own gravity forms license.

PaymentSpring API keys are required.  You can obtain your own by [registering for a free PaymentSpring test account](https://www.paymentspring.com/signup).

### Installation

### Usage

```php
  // Set your API Keys 
  \PaymentSpring\PaymentSpring::setApiKeys(YOUR_PUBLIC_KEY, YOUR_PRIVATE_KEY);
```

### Frequently Asked Questions

**How do I get support?**
This plugin is officially supported by PaymentSpring. If you're having a problem send us an e-mail at support@paymentspring.com

**Do I need to have SSL?**

Yes, we require any page that has a credit card field be encrypted by SSL

