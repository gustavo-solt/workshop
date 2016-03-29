<?php
$itemId = (isset($_GET['itemId']) ? (int) $_GET['itemId'] : '');

$itemContent = file_get_contents("http://api-v2.olx.com/items/" . $itemId);

if ($itemContent) {
    $itemData = json_decode($itemContent, true);

    $templateValues["id"] = $itemData['id'];
    $templateValues["title"] = $itemData['title'];
    $templateValues["description"] = $itemData['description'];
    $templateValues["imgSource"] = (isset($itemData['images']) && isset($itemData['images'][0])) ? $itemData['images'][0]['thumbnail'] : '';
    $templateValues["price"] = (isset($itemData['price']) && isset($itemData['price']['displayPrice'])) ? $itemData['price']['displayPrice'] : '';
    $templateValues["phone"] = (isset($itemData['phone'])) ? $itemData['phone'] : '';
}

$templateValues['mainTemplate'] = 'item.html';

// Ninja vars
$ninjaVars['trackPage'] = 'item';
$ninjaVars['itemId'] = $itemId;
$ninjaVars['imagesCount'] = isset($itemData['images']) ? count($itemData['images']) : 0;
