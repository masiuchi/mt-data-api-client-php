# mt-data-api-client-client-php

Movable Type Data API client for PHP.

## Usage

```php
use \MT\DataAPI\Client;

$opts = array(
  "base_url" => "http://localhost/mt/mt-data-api.cgi",
  "client_id" => "test"
);
$client = new Client($opts);

$res = $client->call('list_sites');
```

