<?php
  // Use the Composer autoloader to include the SDK.
require 'ebay-sdk-php-autoloader.php';
use DTS\eBaySDK\Credentials\Credentials;
use DTS\eBaySDK\Finding\Services\FindingService;
$credentials = new Credentials('shadowde-integrat-SBX-89a67cc35-fd63a555', 'SBX-9a67cc355e25-2b95-4871-a1e0-6103', 'b5a63fb7-e8fc-4f57-a4ca-eed94bd9a8ed');
$config = [
    'apiVersion' => '1.13.0',
    'siteId'     => '0',
    "credentials"=>$credentials
];

// Create an SDK class used to share configuration across services.
$sdk = new DTS\eBaySDK\Sdk($config);

// Create two services that share the same configuration.
$trading = $sdk->createTrading();
$shopping = $sdk->createShopping();


$service = new FindingService([
    'apiVersion'  => '1.13.0',
    'globalId'    => 'EBAY-US',
    'credentials' => $credentials
]);


$request = new DTS\eBaySDK\Finding\Types\FindItemsByKeywordsRequest();
// Assign the keywords.
$request->keywords = 'Harry Potter';

// Ask for the first 25 items.
$request->paginationInput = new DTS\eBaySDK\Finding\Types\PaginationInput();
$request->paginationInput->entriesPerPage = 25;
$request->paginationInput->pageNumber = 1;

// Ask for the results to be sorted from high to low price.
$request->sortOrder = 'CurrentPriceHighest';
$promise = $service->findItemsByKeywords($request);
/*$promise->then(function ($response) {
    echo $response->ack;
})->otherwise(function ($reason) {
    echo 'An error occurred: '.$reason->getMessage();
});*/
//print_r($response);
?>