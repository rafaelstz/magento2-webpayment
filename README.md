ImaginationMedia WebPayment - Magento 2 Module
=====================
PaymentRequest lets you accept payment from different payment methods.

![Imagination Media - Magento2 WebPayment](https://media.giphy.com/media/1fkd4JT1Aj7hLBsG0Z/giphy.gif)
![RequestPaymentAPI](https://media.giphy.com/media/8375Cq2Vjq7xypjheQ/giphy.gif)

Facts
-----
- version: 0.9.0
- extension key: ImaginationMedia_WebPayment

Description
-----------
The Payment Request API is a W3C standard candidate that is meant to eliminate checkout forms. It vastly improves user workflow during the purchase process, providing a more consistent user experience and enabling web merchants to easily leverage different payment methods.

The Payment Request API is designed to be vendor-agnostic, meaning it does not require use of a particular payment system. It's not a new payment method, nor does it integrate directly with payment processors; rather, it is a conduit from the user's payment and shipping information to merchants, with the following goals:

- Let the browser act as intermediary among merchants, users, and payment methods
- Standardize the payment communication flow as much as possible
- Seamlessly support different secure payment methods
- Work on any browser, device, or platform—mobile or otherwise

The Payment Request API is an open and cross-browser standard that replaces traditional checkout flows by allowing merchants to request and accept any payment in a single API call. The API allows the web page to exchange information with the user agent while the user is providing input, before approving or denying a payment request.

Best of all, with the browser acting as an intermediary, all the information necessary for a fast checkout can be stored in the browser, so users can just confirm and pay, all with a single click.

Compatibility
-------------
- Magento >= 2.1

Installation Instructions
-------------------------
1. Copy the content of the `src` folder to your root folder.
2. Run these commands:

```
php -f bin/magento module:enable ImaginationMedia_WebPayment
php -f bin/magento setup:upgrade
```

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/Imagination-Media/ImaginationMedia_WebPayment/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
[Rafael Corrêa Gomes](https://magento.stackexchange.com/users/16416/rafael-corr%C3%AAa-gomes?tab=profile)

Licence
-------
[MIT - Open Software Licence](https://opensource.org/licenses/MIT)

Copyright
---------
(c) 2018 [ImaginationMedia](https://www.imaginationmedia.com/)
