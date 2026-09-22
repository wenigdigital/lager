<?php

declare(strict_types=1);

return [
	'routes' => [
		['name' => 'page#redirect', 'url' => '/', 'verb' => 'GET'],
		['name' => 'page#main', 'url' => '/page/main', 'verb' => 'GET'],
		['name' => 'api#tree', 'url' => '/api/tree', 'verb' => 'GET'],
		['name' => 'api#stockBySlot', 'url' => '/api/stock/slot/{slot_id}', 'verb' => 'GET'],
		['name' => 'api#history', 'url' => '/api/movement/history', 'verb' => 'GET'],
		['name' => 'api#search', 'url' => '/api/search', 'verb' => 'GET'],
		['name' => 'api#createLocation', 'url' => '/api/location', 'verb' => 'POST'],
		['name' => 'api#updateLocation', 'url' => '/api/location/{id}', 'verb' => 'PUT'],
		['name' => 'api#deleteLocation', 'url' => '/api/location/{id}', 'verb' => 'DELETE'],
		['name' => 'api#createCabinet', 'url' => '/api/cabinet', 'verb' => 'POST'],
		['name' => 'api#updateCabinet', 'url' => '/api/cabinet/{id}', 'verb' => 'PUT'],
		['name' => 'api#deleteCabinet', 'url' => '/api/cabinet/{id}', 'verb' => 'DELETE'],
		['name' => 'api#createSlot', 'url' => '/api/slot', 'verb' => 'POST'],
		['name' => 'api#updateSlot', 'url' => '/api/slot/{id}', 'verb' => 'PUT'],
		['name' => 'api#deleteSlot', 'url' => '/api/slot/{id}', 'verb' => 'DELETE'],
		['name' => 'api#createStock', 'url' => '/api/stock', 'verb' => 'POST'],
		['name' => 'api#updateStock', 'url' => '/api/stock/{id}', 'verb' => 'PUT'],
		['name' => 'api#deleteStock', 'url' => '/api/stock/{id}', 'verb' => 'DELETE'],
		['name' => 'api#createMovement', 'url' => '/api/movement', 'verb' => 'POST'],
		['name' => 'api#scanByCode', 'url' => '/api/scan/{code}', 'verb' => 'GET'],
		['name' => 'api#uploadStockImage', 'url' => '/api/stock/{id}/image', 'verb' => 'POST'],
		['name' => 'api#getStockImage', 'url' => '/api/stock/{id}/image', 'verb' => 'GET'],
	],
];
