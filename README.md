# Airtable PHP bindings

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Convenient access to [Airtable](https://airtable.com/invite/r/4v89cs8n) data using PHP.

## Requirements

PHP 5.6.0 and later.

## Installation

You can install the library via [Composer](https://getcomposer.org/). Run the following command:

```bash
composer require knut/airtable-php
```

## Usage

### Creating a Client

1. Register for an [Airtable](https://airtable.com/invite/r/4v89cs8n) account and find your API key here: https://airtable.com/account
2. Create a new base and find your Base ID here: https://airtable.com/api

> Your API key carries the same privileges as your user account, so be sure to keep it secret!

Now you can create your Airtable client:

```php
$airtable = new \Airtable\Client($apiKey, $baseId);
```

### Finding Records
Now you can query a set of records from a table in your base using the following syntax:
```php
$records = $airtable->companies->find();
```
where ```companies``` is the name of your table in your Airtable base. Note how you can access all table by named properties on the Airtable client.

> Note that you can only request a maximimum of 100 records in a single query. To retrieve more records, use the "batch" feature below.

**Finding Records by View-name**

In Airtable you can create pre-defined filters in named [Views](https://support.airtable.com/hc/en-us/articles/202624989-Guide-to-views). This can be a very useful starting point for many queries as you don't have to specify all the filters in code, just reference a named View.

This is how you find records for a named View:
```php
$records = $airtable->companies->find(['view' => 'Name of your view in Airtable']);
```

**Finding Records by Filtering Formula**

You can use a [Formula](https://support.airtable.com/hc/en-us/articles/203255215-Formula-Field-Reference) to filter your records by code using the ```filterByFormula``` parameter.

In example to only include records where ```website_url``` isn't empty you can use:
```php
$records = $airtable->companies->find([
    'filterByFormula' => "NOT({website_url} = '')"
]);
```


**Select only what you need**

Very often you don't need all the data in a result set. You can use the ```fields``` parameter to reduce the amount of data transferred.

For example, to only return data from ```name``` and ```website_url```, specify these two field names in your parameters:

```php
$records = $airtable->companies->find([
    'fields' => ['name', 'website_url']
]);
```

**Sorting**

You can specify a sort order, limit and offset as part of our query.

```php

```

**Limit**

TBD

**Pagination**

TBD


### Batch Querying All Records

TBD

### Finding a Record
Records can be queried by ```id``` using the ```find``` function on a table:
```php
$company = $airtable->companies->find("rec3APJV3yRHlpHoA");
```

### Creating a Record
Records can be created using the ```create```function on a table:
```php
$company = $airtable->companies->create([
    'name' => 'New Company',
    'website_url' => 'https://example.com',
]);
```

### Updating a Record
Records can be updated using the ```update``` function on a table:
```php
$company->name = 'An Updated Company';
$updatedRecord = $airtable->companies->update($company);
```

### Deleting a Record
Records can be deleted using the ```delete``` function on a table.
```php
$result = $airtable->companies->delete($record);
```


## Development

Get Composer. For example, on Mac OS:

```bash
brew install composer
```

Install dependencies:
```bash
composer install
```

Run the test suite using [```phpunit```](https://phpunit.de/)
```bash
$ ./vendor/bin/phpunit
```

Code coverage report is found in ```build/coverage-report```.

## Contributing

1. Fork it (https://github.com/knut/airtable-php/fork)
2. Create your feature branch (```git checkout -b my-new-feature```)
3. Commit your changes (```git commit -am 'Add some feature'```)
4. Push to the branch (```git push origin my-new-feature```)
5. Create a new Pull Request

## License

[MIT](LICENSE)

