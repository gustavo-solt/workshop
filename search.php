<?php
$searchString = (isset($_GET['searchString']) ? $_GET['searchString'] : '');
$listingContent = file_get_contents("http://api-v2.olx.com/items?location=www.olx.com.ar&searchTerm=" . urlencode($searchString));

$templateValues['listingData'] = [];
if ($listingContent) {
    $listingContent = json_decode($listingContent, true);
    $listingContent = $listingContent['data'];
    foreach ($listingContent as $index => $itemData) {
        $templateValues['listingData'][] = [
            "id" => $itemData['id'],
            "title" => $itemData['title'],
            "description" => $itemData['description'],
            "imgSource" => (isset($itemData['thumbnail'])) ? $itemData['thumbnail'] : '',
            "price" => (isset($itemData['price']) && isset($itemData['price']['displayPrice'])) ? $itemData['price']['displayPrice'] : ''
        ];
    }
}
$templateValues['mainTemplate'] = 'search.html';

// Ninja vars
$ninjaVars['trackPage'] = 'resultSet';
$ninjaVars['resultSetType'] = 'search';
$ninjaVars['searchString'] = $searchString;
