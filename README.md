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

// Retrieve all products
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
Until further documentation is provided, please have a look at the tests.

## Installation

You can install the package via composer:

```bash
$ composer require signifly/shopify-php-sdk
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
