# ProxiedMail Client | Receive email via webhook Laravel

ProxiedMail AB Test :construction_worker_woman: is a simple package for base library to create proxy-emails and receive webhooks or simply browse emails list.
You can find base PHP library in https://github.com/proxied-mail/proxiedmail-php-client

You're welcome to [visit the docs](http://docs.proxiedmail.com/).


# What is the ProxiedMail ? 

[ABRouter](https://proxiedmail.com) is a tool that brings the email experience to a new level because it was built around the privacy first concept that enhances using a unique email each time which makes it a second password, but also allows you more control over your correspondence.
Additionally, it gives you the advantage of moving to another email provider just in a few seconds.
Because we have this kind of system we also aim to bring more into the experience of development using emails.

## Features

🛠 Creating endless proxy emails with one of ProxiedMail domains (i.e abc@proxiedmail.com, abcd@pxdmail.com, abcde@pxdmail.net)

🛠 Setting up forwarding email or disabling forwarding

🛠 Setting up a callback to your URL

🛠 Browsing received emails on the received emails endpoint 

🛠 Setting up custom domains. You can do everything using your domain as well.

🛠 Domain-to-domain forwarding. Just in case you need it we can forward emails by mask, like *@domainhostedatproxiedmail.com -> *someotherdomain.com. In this case, the MX of the first domain should be pointed to ProxiedMail and the second domain should be verified by TXT record.

## :package: Install
Via composer

``` bash
$ composer require abrouter/laravel-abtest
```

## Setting service provider
This package provide auto discovery for service provider

If Laravel package auto-discovery is disabled, add service providers manually to /config/app.php. There are service provider you must add:

```
\ProxiedMail\Client\Providers\ProxiedMailServiceProvider::class
```

### Publish client configuration:

```bash
php artisan vendor:publish --tag=proxiedmail
```

### Configure ABRouter client:

Put your ProxiedMail API token in /config/proxiedmail.php. You can find this token in [ProxiedMail API Settings](https://proxiedmail.com/en/settings).

```php

use Abrouter\LaravelClient\Bridge\KvStorage;
use Abrouter\LaravelClient\Bridge\ParallelRunning\TaskManager;

return [
    'apiToken' => 'YOUR API TOKEN',
    'host' => 'https://proxiedmail.com',
];
```

## :rocket: Live example
This example demonstrates create proxy emails, browse received emails and receive emails via webhook.

```php
use ProxiedMail\Client\Bridge\ProxiedMailClient;
use ProxiedMail\Client\Facades\ApiFacade;

class ExampleController
{
    public function browseReceivedEmails(ProxiedMailClient $proxiedMailClient)
    {
        /**
         * @var ApiFacade $api
         */
        $api = $proxiedMailClient->getClient();

        $proxyEmail = $api->createProxyEmail(
            [],
            null,
            null,
            null,
            true
        );


        // while (true) with 100 seconds limit
        foreach (range(0, 180) as $non) {
            echo "PROXY-EMAIL: " . $proxyEmail->getProxyAddress() . "\n";
            echo "Time limit is 3 mins \n";
            echo "Send the email to this proxy-email to get email payload printed here \n";

            //checking webhook receiver

            $receivedEmails = $api->getReceivedEmailsLinksByProxyEmailId($proxyEmail->getId())->getReceivedEmailLinks();
            echo "Amount of received emails: " . count($receivedEmails) . "\n";
            foreach ($receivedEmails as $receivedEmail) {
                echo "Have received email: \n";
                var_dump($receivedEmail);

                echo "\n";
            }

            echo "\n";

            sleep(1);
        }
    }


    public function receiveEmailViaWebhook(ProxiedMailClient $proxiedMailClient)
    {
        /**
         * @var ApiFacade $api
         */
        $api = $proxiedMailClient->getClient();

        $wh = $api->createWebhook(); //creating webhook-receiver
        $proxyEmail = $api->createProxyEmail(
            [],
            null,
            $wh->getCallUrl() //specifying webhook url
        );


        // while (true) with 100 seconds limit
        foreach (range(0, 100) as $non) {
            echo "PROXY-EMAIL: " . $proxyEmail->getProxyAddress() . "\n";
            echo "Send the email to this proxy-email to get email payload printed here";

            //checking webhook receiver
            $whStatus = $api->statusWebhook($wh->getId());

            echo "Webhook STATUS: \n";
            echo "Received: " . ($whStatus->isReceived() ? 'yes' : 'no') . "\n"; //printing webhook status

            //printing payload if received
            if ($whStatus->isReceived()) {
                echo "WEBHOOK PAYLOAD: \n";
                echo json_encode($whStatus->getPayload());
                break;
            }


            echo "\n";

            sleep(1);
        }
    }
}
```


### Managing UI

You can create find the UI on [ProxiedMail](https://proxiedmail.com/) to manage your domains, emails, and webhooks.

## Example
You can get an dockerized usage example by the following link: (https://github.com/abrouter/laravel-example)

## :wrench: Contributing

Please feel free to fork and sending Pull Requests. This project follows [Semantic Versioning 2](http://semver.org) and [PSR-2](http://www.php-fig.org/psr/psr-2/).

## :page_facing_up: License

GPL3. Please see [License File](LICENSE) for more information.

## Questions

For any questions please contact laraclient@pxdmail.com
