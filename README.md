# Email Campaigner API Client #

This library is intended to make it easy for you to get started with and to use [Email Campaigner](http://www.emailcampaigner.com) in your applications.

## Basic Examples ##

I find examples a great way to learn, so here's a couple for you (without the boilerplate)...

```php
// Subscribe some lucky guy to our email list
$obj_client = new \Docnet\EMC\Client(EMC_API_KEY, EMC_API_SECRET);
$obj_client->subscribe('bill@microsoft.com');
```

Now let's unsubscribe him

```php
// Bill changed his mind...
$obj_client = new \Docnet\EMC\Client(EMC_API_KEY, EMC_API_SECRET);
$obj_client->unsubscribe('bill@microsoft.com');
```
### Install with Composer ###

To install using composer, use this require line

`"docnet/php-emc-client": "dev-master"`
