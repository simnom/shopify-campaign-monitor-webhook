# Shopify to Campaign Monitor Webhook

This is my first proper foray into a Git repo so go easy!

Having tried out certain apps to post new customers created in Shopify into
Campaign Monitor without too much success I wrote this notification web hook
to achieve my end goal without the need for an separate application.

## Requirements

The script will need to be hosted somewhere so the webhook can be pointed to it.
The Campaign Monitor PHP wrapper is also required and can be installed via
composer.

## Assumptions

It's assumed that the users are added using a positive opt in. The script
therefore checks for the `accepts_marketing` flag in the customer object before
posting to Campaign Monitor.

## Setup

Grab your API key and List ID from Campaign Monitor and enter these into the
config array at the top of the webhook script.

### API Key

For standalone accounts you can find this under *Account Settings* in the top
menu.

### List ID

List ID can be located under the *change name/type* link of the list within
Campaign Monitor you wish to post to.

### Composer

Install the Campaign Monitor wrapper via composer using `composer install` or
`composer update` depending on your project set up.

### Shopify domain

The script validates the incoming webhook using the `X-Shopify-Shop-Domain`
header.  Add any domains that you have set up for your Shopify account to the
`shopify-domains` array in the config variable.

### Create webhook

Within your Shopify admin area navigate to *Settings -> Notifications*. At the
foot of this page is a section for webhooks.  Add the full URL of your
notification script and give it a suitable name.

Test the webhook and you should be good to go!