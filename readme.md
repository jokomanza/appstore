<p align="center"><img  style="height: 100px; width: 100px; align-self: center;" src="public/images/logo.png"></p>

<h1 align="center">Quick App Store</h1>

## Config

If the email is not sent, please change the following file
swiftmailer\swiftmailer\lib\classes\Swift\Transport\StreamBuffer.php. In _establishSocketConnection() line 253 replace:

```php
$options = array();
```

with something like this:

```php
$options = array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false));
```

## TODO

- [ ] Deploy this website using apache virtual host and user dns
- [ ] Add security setting

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
