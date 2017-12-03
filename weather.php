<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT');
header('Access-Control-Allow-Headers: 0,1,cache-control, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
/**
 * This script mirrors metaweather API.
 * It offers two commands:
 *
 * command: search
 * uri: weather.php?command=search&keyword={your_keyword}
 * 
 * command: location
 * uri: weather.php?command=location&woeid={target_woeid}
 */

$locations = [];

if (file_exists('./cache.txt')) {
	$locations = json_decode(file_get_contents('./cache.txt'), true);
}

/**
 * Declarations
 */
$validCommands = [
	'search',
	'location'
];

$command = isset($_GET['command']) ? $_GET['command'] : null;
$baseUrl = 'https://www.metaweather.com/api/location/';

/**
 * Functions
 */
function quitWithResponse($output, $code = 200) {
	header('Content-Type: text/json');
	http_response_code($code);
	echo $output;
	exit;
}

function response($output, $code = 200) {
	header('Content-Type: text/json');
	http_response_code($code);
	return $output;
}

function quitWithJsonResponse($output, $code = 200) {
	return quitWithResponse(
		json_encode($output),
		$code
	);
}

function mirrorToEndpoint($uri, $out = false) {
	global $baseUrl;
	$url = $baseUrl . $uri;
	//echo $url;
	$response = @file_get_contents($url);
	
	if ( $response ) {
		if ($out) {
			quitWithResponse($response);
		} else {
			return response($response);	
		}
	}	
	
	quitWithJsonResponse(['error' => 'Not found'], 404);
}

function requireParameters($params) {
	foreach ($params as $param) {
		if (!isset($_GET[$param])) {
			quitWithJsonResponse(['error' => $param . ' is missing']);
		}
	}
}

/**
 * Commands
 */
function search($location = null) {
	if (!$location) {
		requireParameters(['keyword']);
		$location = $_GET['keyword'];
	} 
	
	return mirrorToEndpoint('search/?query=' . $location);
}

function location($woeid = null) {
	global $locations;

	if (!$woeid && isset($_GET['locations'])) {
		$toUpdate = false;
		foreach ($_GET['locations'] as $location) {
			if (!array_key_exists($location, $locations)) {
				$result = search($location);
				if ($result) {
					$toUpdate = true;
					$locations[$location] = json_decode($result);
				} else {
					print_r($result);
				}
			}
		}

		if ($toUpdate) {
			file_put_contents('cache.txt', json_encode($locations));
		}
		$results = [];

		//echo "locations;"; print_r($_GET['locations']);
		//get weathers
		foreach ($_GET['locations'] as $location) {
			if (isset($locations[$location][0]['woeid'])) {
				$woeid = $locations[$location][0]['woeid'];
				$results[$location] = location($woeid);
			}
		}
		
		if (empty($results))
			quitWithJsonResponse(['error' => "Location not found"], 404);


		quitWithJsonResponse($results);
		return ;
	}
		
	if (!$woeid) {
		requireParameters(['woeid']);
		$woeid = $_GET['woeid'];
	}

	$out = isset($_GET['out']) ? true : false;

	return mirrorToEndpoint($woeid, $out);
}

/**
 * Execution
 */
if (is_null($command) or !in_array($command, $validCommands)) {
	quitWithJsonResponse(['error' => 'Invalid command'], 422);
}

$command();