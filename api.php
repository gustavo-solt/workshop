<?php
$page = (isset($_GET['page']) ? $_GET['page'] : 'ad');
switch ($page) {
    case 'ad':
        $itemId = (isset($_GET['itemId']) ? $_GET['itemId'] : 0);
        if ($itemId) {
            $itemContent = file_get_contents("http://api-v2.olx.com/items/" . $itemId);
            header('Content-Type: application/json');
            echo $itemContent;
        }
        break;
    case 'ads':
        $itemIds = (isset($_GET['itemIds']) ? $_GET['itemIds'] : '');
        if ($itemIds) {
            $itemContent = file_get_contents("http://api-v2.olx.com/items/" . $itemIds);
            header('Content-Type: application/json');
            echo $itemContent;
        }
        break;
    default:
        break;
}
