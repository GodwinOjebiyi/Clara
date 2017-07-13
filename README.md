### Clara
=========================

A Simple Package for getting home worth using the Zillow API and displaying the Property Image by Using Google Map.
Clara can help speed up the creation of a real estate website or a section for showing home value on your website.

## Requirements
Zillow Property Listing API KEY
Google Map Javascript API KEY

## Features
* PSR-4 autoloading compliant structure
* Unit-Testing with PHPUnit
* Examples
* Easy to use to any framework or even a plain php file
*Search and Deep Search Result API from Zillow

## Instalation
```bash
$ composer require godwin-ojebiyi/clara
```

## Usage
```bash
require_once(__DIR__ . '/vendor/autoload.php');
use GodwinOjebiyi\Clara;
$clara = new Clara($zillow_id, $google_maps_api_key);
```

# Set Address for clara to work on
```php
$clara->set_address("address");
```

# Get Home Worth
```php
if($clara->get_home_worth()){
	echo $clara->show_estimate_info('home_url');
	echo $clara->show_estimate_info('zestimate_low');
	echo $clara->show_estimate_info('zestimate_high');
	echo $clara->show_estimate_info('zestimate');
}else{
	echo "Clara cannot find your home with Zillow";
}
```

# Get Property Image
```php
echo $clara->get_property_image($width, $height);
```

## Coming Soon
*Estimated Property Value Growth Chart
*Ability to include bootstrap classes in Clara.