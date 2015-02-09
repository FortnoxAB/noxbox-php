
# Preparations

Make sure you have finished registration with Nox Finans as a ERP Client before using this integration wrapper.
By doing this you will recieve a ERP Access Token that is mandatory.

# API Exemples

Create new client
```php
$resource = new Resources\Clients($erpAccessToken, $mode = 'production'/'test');
$client = $resource->create(null, $data);
$clientAccessToken = $client['accesstoken'];
```

Load existing client
```php
$resource = new Resources\Clients($erpAccessToken, $mode = 'production'/'test');
$client = $resource->find($clientId);
$clientAccessToken = $client['accesstoken'];

```

Now you have the client access token. You can start performing client specific actions.

Init a resource (Invoice in this case)
```php
$resource = new Resources\Invoices($clientAccessToken, $mode = 'production'/'test');
```

Create from that resource (POST)
```php
$invoice = $resource->create($id = null, $data);
```

Load from that resource (GET)
```php
$invoice = $resource->find($id);
```


Update from that resource (PUT)
```php
$invoice = $resource->update($id, $data);
```

Remove from that resource (DELETE)
```php
$invoice = $resource->update($id, $data);
```

Deleting a resource
```php
$resource->delete($id);
```

# Licence

The MIT License (MIT)

Copyright (c) 2013 Fortnox AB, Jakob Carlbring Alm

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
