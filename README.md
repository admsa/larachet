# Larachet
Laravel 5 and ratchet integration

# Usage

Require composer
```
  "admsa/larachet": "dev-master"
```

Add provider to your config/app.php

```
'providers' => [
    // Existing providers
    'Admsa\Larachet\LarachetServiceProvider',
  ]
```

Add facade alias to your config/app.php

```
'aliases' => [
    // Existing aliases
    'Larachet'  => 'Admsa\Larachet\LarachetFacade'
  ]
```

```
$data = [];
Larachet::push('kittens-category', $data);
```

Javascript code usage

```
var r = new Larachet('ws://localhost:8080');

r.watch('kittens-category', function(topic, data) {
  console.log('New article published to category "' + topic + '" : ' + JSON.stringify(data));
});

r.watch('puppy-category', function(topic, data) {
  console.log('New article published to category "' + topic + '" : ' + JSON.stringify(data));
});
```

# Note
This is still under development.

Javascript code is already loaded. Kindly check the html source code.

# References
http://blog.alexandervn.nl/2012/05/03/install-zeromq-php-ubuntu
