# Shopify PHP SDK

The `signifly/shopify-php-sdk` package allows you to easily make requests to the Shopify API.

Below is a small example of how to use it with the `CredentialsProfile`.

```php
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Profiles\CredentialsProfile;

$shopify = new Shopify(
    new CredentialsProfile(
        env('SHOPIFY_API_KEY'),
        env('SHOPIFY_PASSWORD'),
        env('SHOPIFY_DOMAIN'),
        env('SHOPIFY_API_VERSION')
    )
);

// Retrieve a list of products
$shopify->products()->all(); // returns a collection of ProductResource

// Count all products
$shopify->products()->count();

// Find a product
$resource = $shopify->products()->find($id); // returns a ProductResource

// Update a product
$shopify->products()->update($id, $data); // returns a ProductResource

// Delete a product
$shopify->products()->destroy($id);
```

## Documentation
To get started follow the installation instructions below.

## Installation

You can install the package via composer:

```bash
$ composer require signifly/shopify-php-sdk
```

## Reference

A list of the available methods on the Shopify API client. The examples below assumes you have knowledge of how to make valid requests to the Shopify API. 

If you want to learn more about what options are available when making a request, please refer to [Shopify's documentation](https://help.shopify.com/en/api/reference).

### Products

**Retrieve a list of products**

```php
$shopify->products()->all([
    'page' => 1,
    'limit' => 250,
]);

// returns a collection of ProductResource
```

*NOTE:* There's a max limit of 250 items per request.

**Retrieve a count of products**

```php
$shopify->products()->count(); // returns an integer
```

**Retrieve a single product**

```php
$shopify->products()->find(123456789); // returns a ProductResource
```

**Create a new product**

```php
$shopify->products()->create([
    'title' => 'Burton Custom Freestyle 151',
    'body_html' => '<strong>Good snowboard!</strong>',
    'vendor' => 'Burton',
    'product_type' => 'Snowboard',
    'tags' => 'Barnes & Noble, John\'s Fav, "Big Air"',
]);

// returns a ProductResource
```

**Update a product**

```php
$shopify->products()->update(123456789, [
    'title' => 'An updated title',
]);

// returns a ProductResource
```

**Delete a product**

```php
$shopify->products()->destroy(123456789); // returns void
```

## Testing
```bash
$ composer test
```

## Security

If you discover any security issues, please email dev@signifly.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [Travis Elkins](https://github.com/telkins)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
