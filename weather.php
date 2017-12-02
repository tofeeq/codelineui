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

function mirrorToEndpoint($uri) {
	global $baseUrl;
	$url = $baseUrl . $uri;
	$response = @file_get_contents($url);
	
	if ( $response ) {
		return response($response);	
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
				}
			}
		}

		if ($toUpdate) {
			file_put_contents('cache.txt', json_encode($locations));
		}
		$results = [];
		//get weathers
		foreach ($_GET['locations'] as $location) {
			$woeid = $locations[$location][0]['woeid'];
			$results[$location] = location($woeid);
		}
		
		quitWithJsonResponse($results);
		return ;
	}
		
	if (!$woeid) {
		requireParameters(['woeid']);
		$woeid = $_GET['woeid'];
	}

	return mirrorToEndpoint($woeid);
}

/**
 * Execution
 */
if (is_null($command) or !in_array($command, $validCommands)) {
	quitWithJsonResponse(['error' => 'Invalid command'], 422);
}

//$command();
header('Content-Type: text/json');
echo ('{"Istanbul":"{\"consolidated_weather\":[{\"id\":5583883596726272,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"SSE\",\"created\":\"2017-12-02T02:54:41.794900Z\",\"applicable_date\":\"2017-12-02\",\"min_temp\":13.724,\"max_temp\":18.068000000000001,\"the_temp\":16.495000000000001,\"wind_speed\":6.1965569642885558,\"wind_direction\":158.82261520061533,\"air_pressure\":1022.635,\"humidity\":72,\"visibility\":19.037570587767437,\"predictability\":71},{\"id\":5726351688466432,\"weather_state_name\":\"Light Cloud\",\"weather_state_abbr\":\"lc\",\"wind_direction_compass\":\"SSE\",\"created\":\"2017-12-02T02:54:44.243370Z\",\"applicable_date\":\"2017-12-03\",\"min_temp\":14.358000000000001,\"max_temp\":17.878,\"the_temp\":16.535,\"wind_speed\":7.2714028033277653,\"wind_direction\":165.42972247756424,\"air_pressure\":1024.71,\"humidity\":72,\"visibility\":18.208661417322833,\"predictability\":70},{\"id\":6475417338773504,\"weather_state_name\":\"Heavy Rain\",\"weather_state_abbr\":\"hr\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T02:54:47.337070Z\",\"applicable_date\":\"2017-12-04\",\"min_temp\":7.9259999999999993,\"max_temp\":15.374000000000001,\"the_temp\":14.879999999999999,\"wind_speed\":7.9926827462117247,\"wind_direction\":260.09846023527234,\"air_pressure\":1024.27,\"humidity\":87,\"visibility\":10.969065656565656,\"predictability\":77},{\"id\":6709783503568896,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"NNW\",\"created\":\"2017-12-02T02:54:50.657410Z\",\"applicable_date\":\"2017-12-05\",\"min_temp\":5.1200000000000001,\"max_temp\":8.4740000000000002,\"the_temp\":8.1699999999999999,\"wind_speed\":9.3942964609067054,\"wind_direction\":337.17643032661249,\"air_pressure\":1022.2149999999999,\"humidity\":66,\"visibility\":14.95578322596039,\"predictability\":71},{\"id\":6646157858045952,\"weather_state_name\":\"Light Cloud\",\"weather_state_abbr\":\"lc\",\"wind_direction_compass\":\"NW\",\"created\":\"2017-12-02T02:54:53.744630Z\",\"applicable_date\":\"2017-12-06\",\"min_temp\":3.9459999999999993,\"max_temp\":7.1719999999999997,\"the_temp\":7.3000000000000007,\"wind_speed\":13.920643239871151,\"wind_direction\":317.78230327332693,\"air_pressure\":1027.325,\"humidity\":66,\"visibility\":13.306042710570269,\"predictability\":70},{\"id\":4721764542185472,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T02:54:56.289080Z\",\"applicable_date\":\"2017-12-07\",\"min_temp\":4.4500000000000002,\"max_temp\":9.016,\"the_temp\":10.84,\"wind_speed\":6.9969286367613144,\"wind_direction\":262.0,\"air_pressure\":1027.7,\"humidity\":58,\"visibility\":null,\"predictability\":71}],\"time\":\"2017-12-02T06:36:31.483690+02:00\",\"sun_rise\":\"2017-12-02T07:10:38.266263+02:00\",\"sun_set\":\"2017-12-02T16:36:07.930566+02:00\",\"timezone_name\":\"LMT\",\"parent\":{\"title\":\"Turkey\",\"location_type\":\"Country\",\"woeid\":23424969,\"latt_long\":\"38.957741,35.431702\"},\"sources\":[{\"title\":\"BBC\",\"slug\":\"bbc\",\"url\":\"http:\/\/www.bbc.co.uk\/weather\/\",\"crawl_rate\":180},{\"title\":\"Forecast.io\",\"slug\":\"forecast-io\",\"url\":\"http:\/\/forecast.io\/\",\"crawl_rate\":480},{\"title\":\"Met Office\",\"slug\":\"met-office\",\"url\":\"http:\/\/www.metoffice.gov.uk\/\",\"crawl_rate\":180},{\"title\":\"OpenWeatherMap\",\"slug\":\"openweathermap\",\"url\":\"http:\/\/openweathermap.org\/\",\"crawl_rate\":360},{\"title\":\"Weather Underground\",\"slug\":\"wunderground\",\"url\":\"https:\/\/www.wunderground.com\/?apiref=fc30dc3cd224e19b\",\"crawl_rate\":720},{\"title\":\"World Weather Online\",\"slug\":\"world-weather-online\",\"url\":\"http:\/\/www.worldweatheronline.com\/\",\"crawl_rate\":360},{\"title\":\"Yahoo\",\"slug\":\"yahoo\",\"url\":\"http:\/\/weather.yahoo.com\/\",\"crawl_rate\":180}],\"title\":\"Istanbul\",\"location_type\":\"City\",\"woeid\":2344116,\"latt_long\":\"41.040852,28.986179\",\"timezone\":\"Europe\/Istanbul\"}","Berlin":"{\"consolidated_weather\":[{\"id\":6076464805969920,\"weather_state_name\":\"Light Rain\",\"weather_state_abbr\":\"lr\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T02:34:53.664280Z\",\"applicable_date\":\"2017-12-02\",\"min_temp\":0.28799999999999998,\"max_temp\":2.9740000000000002,\"the_temp\":1.8500000000000001,\"wind_speed\":2.9914528154346618,\"wind_direction\":276.06158625997466,\"air_pressure\":1023.1900000000001,\"humidity\":88,\"visibility\":11.300877873220392,\"predictability\":75},{\"id\":6094295966679040,\"weather_state_name\":\"Light Rain\",\"weather_state_abbr\":\"lr\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T02:34:56.790850Z\",\"applicable_date\":\"2017-12-03\",\"min_temp\":-0.36499999999999999,\"max_temp\":3.2620000000000005,\"the_temp\":0.66999999999999993,\"wind_speed\":5.8942667873761234,\"wind_direction\":232.54748562748924,\"air_pressure\":1026.21,\"humidity\":88,\"visibility\":9.8773164718046598,\"predictability\":75},{\"id\":6324720425238528,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T02:34:59.798890Z\",\"applicable_date\":\"2017-12-04\",\"min_temp\":1.7,\"max_temp\":4.6240000000000006,\"the_temp\":2.605,\"wind_speed\":9.7638743346774834,\"wind_direction\":261.56699193738336,\"air_pressure\":1020.85,\"humidity\":85,\"visibility\":14.668709735146743,\"predictability\":73},{\"id\":6470983321911296,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"WNW\",\"created\":\"2017-12-02T02:35:02.806300Z\",\"applicable_date\":\"2017-12-05\",\"min_temp\":2.9180000000000001,\"max_temp\":6.6300000000000008,\"the_temp\":6.4550000000000001,\"wind_speed\":10.444693941646385,\"wind_direction\":286.411102948473,\"air_pressure\":1024.5700000000002,\"humidity\":86,\"visibility\":18.104271057026963,\"predictability\":73},{\"id\":4640979093028864,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T02:35:05.743180Z\",\"applicable_date\":\"2017-12-06\",\"min_temp\":4.6799999999999997,\"max_temp\":6.3680000000000003,\"the_temp\":5.9100000000000001,\"wind_speed\":10.693638152940427,\"wind_direction\":273.13727731184963,\"air_pressure\":1028.4400000000001,\"humidity\":84,\"visibility\":15.361538614491369,\"predictability\":73},{\"id\":6103710534991872,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T02:35:08.845680Z\",\"applicable_date\":\"2017-12-07\",\"min_temp\":4.2119999999999997,\"max_temp\":7.2359999999999998,\"the_temp\":6.4900000000000002,\"wind_speed\":12.456343354807922,\"wind_direction\":234.62575503161,\"air_pressure\":1022.84,\"humidity\":79,\"visibility\":null,\"predictability\":73}],\"time\":\"2017-12-02T05:36:35.415600+01:00\",\"sun_rise\":\"2017-12-02T07:56:06.089077+01:00\",\"sun_set\":\"2017-12-02T15:55:28.920419+01:00\",\"timezone_name\":\"LMT\",\"parent\":{\"title\":\"Germany\",\"location_type\":\"Country\",\"woeid\":23424829,\"latt_long\":\"51.164181,10.454150\"},\"sources\":[{\"title\":\"BBC\",\"slug\":\"bbc\",\"url\":\"http:\/\/www.bbc.co.uk\/weather\/\",\"crawl_rate\":180},{\"title\":\"Forecast.io\",\"slug\":\"forecast-io\",\"url\":\"http:\/\/forecast.io\/\",\"crawl_rate\":480},{\"title\":\"HAMweather\",\"slug\":\"hamweather\",\"url\":\"http:\/\/www.hamweather.com\/\",\"crawl_rate\":360},{\"title\":\"Met Office\",\"slug\":\"met-office\",\"url\":\"http:\/\/www.metoffice.gov.uk\/\",\"crawl_rate\":180},{\"title\":\"OpenWeatherMap\",\"slug\":\"openweathermap\",\"url\":\"http:\/\/openweathermap.org\/\",\"crawl_rate\":360},{\"title\":\"Weather Underground\",\"slug\":\"wunderground\",\"url\":\"https:\/\/www.wunderground.com\/?apiref=fc30dc3cd224e19b\",\"crawl_rate\":720},{\"title\":\"World Weather Online\",\"slug\":\"world-weather-online\",\"url\":\"http:\/\/www.worldweatheronline.com\/\",\"crawl_rate\":360},{\"title\":\"Yahoo\",\"slug\":\"yahoo\",\"url\":\"http:\/\/weather.yahoo.com\/\",\"crawl_rate\":180}],\"title\":\"Berlin\",\"location_type\":\"City\",\"woeid\":638242,\"latt_long\":\"52.516071,13.376980\",\"timezone\":\"Europe\/Berlin\"}","London":"{\"consolidated_weather\":[{\"id\":6074366781554688,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"WNW\",\"created\":\"2017-12-02T02:31:03.702310Z\",\"applicable_date\":\"2017-12-02\",\"min_temp\":2.4060000000000001,\"max_temp\":6.3420000000000005,\"the_temp\":5.4100000000000001,\"wind_speed\":5.7165100112017821,\"wind_direction\":291.138873311738,\"air_pressure\":1027.675,\"humidity\":78,\"visibility\":8.3400441422094964,\"predictability\":71},{\"id\":5921643851415552,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"WNW\",\"created\":\"2017-12-02T02:31:06.595580Z\",\"applicable_date\":\"2017-12-03\",\"min_temp\":4.8059999999999992,\"max_temp\":9.266,\"the_temp\":7.1799999999999997,\"wind_speed\":5.5944404038638353,\"wind_direction\":291.88511712134175,\"air_pressure\":1029.375,\"humidity\":83,\"visibility\":7.4493085381372781,\"predictability\":71},{\"id\":4676113804558336,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"NW\",\"created\":\"2017-12-02T02:31:06.593170Z\",\"applicable_date\":\"2017-12-04\",\"min_temp\":4.6719999999999997,\"max_temp\":10.144,\"the_temp\":9.9250000000000007,\"wind_speed\":6.4548019024035641,\"wind_direction\":305.70630636670938,\"air_pressure\":1032.075,\"humidity\":86,\"visibility\":8.9505413385826778,\"predictability\":71},{\"id\":4646172245360640,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T02:31:06.392720Z\",\"applicable_date\":\"2017-12-05\",\"min_temp\":3.4040000000000008,\"max_temp\":8.0820000000000007,\"the_temp\":7.5800000000000001,\"wind_speed\":6.2303066292611149,\"wind_direction\":269.05316034047377,\"air_pressure\":1036.335,\"humidity\":82,\"visibility\":12.533367633023143,\"predictability\":71},{\"id\":5498870087811072,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T02:31:06.890110Z\",\"applicable_date\":\"2017-12-06\",\"min_temp\":7.0280000000000005,\"max_temp\":10.736000000000001,\"the_temp\":8.6449999999999996,\"wind_speed\":8.3913838576537021,\"wind_direction\":232.57021709954432,\"air_pressure\":1033.2550000000001,\"humidity\":82,\"visibility\":6.2416736260240198,\"predictability\":73},{\"id\":6340245658271744,\"weather_state_name\":\"Light Rain\",\"weather_state_abbr\":\"lr\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T02:31:09.194270Z\",\"applicable_date\":\"2017-12-07\",\"min_temp\":6.6560000000000006,\"max_temp\":11.734,\"the_temp\":13.210000000000001,\"wind_speed\":11.282405750417562,\"wind_direction\":234.25505191039082,\"air_pressure\":1020.2,\"humidity\":80,\"visibility\":null,\"predictability\":75}],\"time\":\"2017-12-02T04:36:39.256930Z\",\"sun_rise\":\"2017-12-02T07:45:22.263432Z\",\"sun_set\":\"2017-12-02T15:54:17.072479Z\",\"timezone_name\":\"LMT\",\"parent\":{\"title\":\"England\",\"location_type\":\"Region \/ State \/ Province\",\"woeid\":24554868,\"latt_long\":\"52.883560,-1.974060\"},\"sources\":[{\"title\":\"BBC\",\"slug\":\"bbc\",\"url\":\"http:\/\/www.bbc.co.uk\/weather\/\",\"crawl_rate\":180},{\"title\":\"Forecast.io\",\"slug\":\"forecast-io\",\"url\":\"http:\/\/forecast.io\/\",\"crawl_rate\":480},{\"title\":\"HAMweather\",\"slug\":\"hamweather\",\"url\":\"http:\/\/www.hamweather.com\/\",\"crawl_rate\":360},{\"title\":\"Met Office\",\"slug\":\"met-office\",\"url\":\"http:\/\/www.metoffice.gov.uk\/\",\"crawl_rate\":180},{\"title\":\"OpenWeatherMap\",\"slug\":\"openweathermap\",\"url\":\"http:\/\/openweathermap.org\/\",\"crawl_rate\":360},{\"title\":\"Weather Underground\",\"slug\":\"wunderground\",\"url\":\"https:\/\/www.wunderground.com\/?apiref=fc30dc3cd224e19b\",\"crawl_rate\":720},{\"title\":\"World Weather Online\",\"slug\":\"world-weather-online\",\"url\":\"http:\/\/www.worldweatheronline.com\/\",\"crawl_rate\":360},{\"title\":\"Yahoo\",\"slug\":\"yahoo\",\"url\":\"http:\/\/weather.yahoo.com\/\",\"crawl_rate\":180}],\"title\":\"London\",\"location_type\":\"City\",\"woeid\":44418,\"latt_long\":\"51.506321,-0.12714\",\"timezone\":\"Europe\/London\"}","Helsinki":"{\"consolidated_weather\":[{\"id\":4970755003514880,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T04:07:35.180420Z\",\"applicable_date\":\"2017-12-02\",\"min_temp\":1.0525,\"max_temp\":3.8439999999999999,\"the_temp\":0.74500000000000011,\"wind_speed\":9.2547783740491525,\"wind_direction\":263.5648663186978,\"air_pressure\":1017.5549999999999,\"humidity\":89,\"visibility\":15.683408892070309,\"predictability\":73},{\"id\":4721384638906368,\"weather_state_name\":\"Heavy Rain\",\"weather_state_abbr\":\"hr\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T04:07:38.066940Z\",\"applicable_date\":\"2017-12-03\",\"min_temp\":2.2640000000000002,\"max_temp\":5.6159999999999997,\"the_temp\":5.7000000000000002,\"wind_speed\":18.316550299724806,\"wind_direction\":218.12993449620924,\"air_pressure\":1009.765,\"humidity\":93,\"visibility\":3.7773154776107529,\"predictability\":77},{\"id\":4691973508169728,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"NW\",\"created\":\"2017-12-02T04:07:41.205790Z\",\"applicable_date\":\"2017-12-04\",\"min_temp\":-1.5425000000000002,\"max_temp\":2.3580000000000001,\"the_temp\":1.8799999999999999,\"wind_speed\":7.0823442208510299,\"wind_direction\":319.50697152178481,\"air_pressure\":1008.5650000000001,\"humidity\":93,\"visibility\":10.729837747554283,\"predictability\":73},{\"id\":5683147337367552,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"NW\",\"created\":\"2017-12-02T04:07:44.169410Z\",\"applicable_date\":\"2017-12-05\",\"min_temp\":-1.278,\"max_temp\":-0.18666666666666668,\"the_temp\":-0.23000000000000001,\"wind_speed\":8.2796068527177287,\"wind_direction\":307.63955058315241,\"air_pressure\":1010.46,\"humidity\":81,\"visibility\":21.234739123518651,\"predictability\":73},{\"id\":4526040735023104,\"weather_state_name\":\"Thunder\",\"weather_state_abbr\":\"t\",\"wind_direction_compass\":\"WSW\",\"created\":\"2017-12-02T04:07:47.195810Z\",\"applicable_date\":\"2017-12-06\",\"min_temp\":0.20000000000000009,\"max_temp\":2.4020000000000001,\"the_temp\":1.9199999999999999,\"wind_speed\":9.364426889020919,\"wind_direction\":253.10743414046527,\"air_pressure\":1005.515,\"humidity\":89,\"visibility\":15.866713393780323,\"predictability\":80},{\"id\":5553839763619840,\"weather_state_name\":\"Thunder\",\"weather_state_abbr\":\"t\",\"wind_direction_compass\":\"WSW\",\"created\":\"2017-12-02T04:07:50.650910Z\",\"applicable_date\":\"2017-12-07\",\"min_temp\":0.57499999999999996,\"max_temp\":2.4359999999999999,\"the_temp\":0.85999999999999999,\"wind_speed\":9.8632010061242337,\"wind_direction\":243.62485441189332,\"air_pressure\":1009.25,\"humidity\":79,\"visibility\":null,\"predictability\":80}],\"time\":\"2017-12-02T06:36:43.275270+02:00\",\"sun_rise\":\"2017-12-02T08:58:47.233650+02:00\",\"sun_set\":\"2017-12-02T15:20:14.562373+02:00\",\"timezone_name\":\"LMT\",\"parent\":{\"title\":\"Finland\",\"location_type\":\"Country\",\"woeid\":23424812,\"latt_long\":\"64.950142,26.067390\"},\"sources\":[{\"title\":\"BBC\",\"slug\":\"bbc\",\"url\":\"http:\/\/www.bbc.co.uk\/weather\/\",\"crawl_rate\":180},{\"title\":\"Forecast.io\",\"slug\":\"forecast-io\",\"url\":\"http:\/\/forecast.io\/\",\"crawl_rate\":480},{\"title\":\"Met Office\",\"slug\":\"met-office\",\"url\":\"http:\/\/www.metoffice.gov.uk\/\",\"crawl_rate\":180},{\"title\":\"OpenWeatherMap\",\"slug\":\"openweathermap\",\"url\":\"http:\/\/openweathermap.org\/\",\"crawl_rate\":360},{\"title\":\"Weather Underground\",\"slug\":\"wunderground\",\"url\":\"https:\/\/www.wunderground.com\/?apiref=fc30dc3cd224e19b\",\"crawl_rate\":720},{\"title\":\"World Weather Online\",\"slug\":\"world-weather-online\",\"url\":\"http:\/\/www.worldweatheronline.com\/\",\"crawl_rate\":360},{\"title\":\"Yahoo\",\"slug\":\"yahoo\",\"url\":\"http:\/\/weather.yahoo.com\/\",\"crawl_rate\":180}],\"title\":\"Helsinki\",\"location_type\":\"City\",\"woeid\":565346,\"latt_long\":\"60.171162,24.932581\",\"timezone\":\"Europe\/Helsinki\"}","Dublin":"{\"consolidated_weather\":[{\"id\":6607022384480256,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"WNW\",\"created\":\"2017-12-02T03:23:29.289120Z\",\"applicable_date\":\"2017-12-02\",\"min_temp\":5.1360000000000001,\"max_temp\":7.7620000000000005,\"the_temp\":8.1899999999999995,\"wind_speed\":8.4190109076719946,\"wind_direction\":287.73661023319818,\"air_pressure\":1034.1500000000001,\"humidity\":92,\"visibility\":12.245983456613377,\"predictability\":71},{\"id\":4755690153312256,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"WNW\",\"created\":\"2017-12-02T03:23:32.221240Z\",\"applicable_date\":\"2017-12-03\",\"min_temp\":6.4759999999999991,\"max_temp\":9.2099999999999991,\"the_temp\":9.7100000000000009,\"wind_speed\":9.3758354976537017,\"wind_direction\":286.9331916960233,\"air_pressure\":1034.9850000000001,\"humidity\":92,\"visibility\":13.412918555635091,\"predictability\":73},{\"id\":6349104766517248,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T03:23:35.558380Z\",\"applicable_date\":\"2017-12-04\",\"min_temp\":4.9539999999999997,\"max_temp\":8.7859999999999996,\"the_temp\":8.4849999999999994,\"wind_speed\":7.9688969750656167,\"wind_direction\":270.99724938854274,\"air_pressure\":1039.25,\"humidity\":91,\"visibility\":13.252915473633978,\"predictability\":71},{\"id\":5430694461308928,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T03:23:38.702780Z\",\"applicable_date\":\"2017-12-05\",\"min_temp\":5.3239999999999998,\"max_temp\":9.4439999999999991,\"the_temp\":9.2149999999999999,\"wind_speed\":10.128310614197089,\"wind_direction\":228.58211127790196,\"air_pressure\":1034.3299999999999,\"humidity\":85,\"visibility\":17.128718285214347,\"predictability\":71},{\"id\":4952581453381632,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"SW\",\"created\":\"2017-12-02T03:23:41.870320Z\",\"applicable_date\":\"2017-12-06\",\"min_temp\":9.0999999999999996,\"max_temp\":11.139999999999999,\"the_temp\":12.365,\"wind_speed\":13.362694793532171,\"wind_direction\":218.81647975650836,\"air_pressure\":1017.8049999999999,\"humidity\":87,\"visibility\":10.006561679790027,\"predictability\":73},{\"id\":5327049652699136,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"W\",\"created\":\"2017-12-02T03:23:44.675560Z\",\"applicable_date\":\"2017-12-07\",\"min_temp\":4.7400000000000002,\"max_temp\":10.672000000000001,\"the_temp\":12.81,\"wind_speed\":15.755835321721149,\"wind_direction\":266.50807436938447,\"air_pressure\":1013.51,\"humidity\":81,\"visibility\":null,\"predictability\":73}],\"time\":\"2017-12-02T04:36:47.478020Z\",\"sun_rise\":\"2017-12-02T08:18:49.916321Z\",\"sun_set\":\"2017-12-02T16:09:47.929303Z\",\"timezone_name\":\"LMT\",\"parent\":{\"title\":\"Ireland\",\"location_type\":\"Country\",\"woeid\":23424803,\"latt_long\":\"53.419609,-8.240550\"},\"sources\":[{\"title\":\"BBC\",\"slug\":\"bbc\",\"url\":\"http:\/\/www.bbc.co.uk\/weather\/\",\"crawl_rate\":180},{\"title\":\"Forecast.io\",\"slug\":\"forecast-io\",\"url\":\"http:\/\/forecast.io\/\",\"crawl_rate\":480},{\"title\":\"HAMweather\",\"slug\":\"hamweather\",\"url\":\"http:\/\/www.hamweather.com\/\",\"crawl_rate\":360},{\"title\":\"Met Office\",\"slug\":\"met-office\",\"url\":\"http:\/\/www.metoffice.gov.uk\/\",\"crawl_rate\":180},{\"title\":\"OpenWeatherMap\",\"slug\":\"openweathermap\",\"url\":\"http:\/\/openweathermap.org\/\",\"crawl_rate\":360},{\"title\":\"Weather Underground\",\"slug\":\"wunderground\",\"url\":\"https:\/\/www.wunderground.com\/?apiref=fc30dc3cd224e19b\",\"crawl_rate\":720},{\"title\":\"World Weather Online\",\"slug\":\"world-weather-online\",\"url\":\"http:\/\/www.worldweatheronline.com\/\",\"crawl_rate\":360},{\"title\":\"Yahoo\",\"slug\":\"yahoo\",\"url\":\"http:\/\/weather.yahoo.com\/\",\"crawl_rate\":180}],\"title\":\"Dublin\",\"location_type\":\"City\",\"woeid\":560743,\"latt_long\":\"53.343761,-6.249530\",\"timezone\":\"Europe\/Dublin\"}","Vancouver":"{\"consolidated_weather\":[{\"id\":4612061346660352,\"weather_state_name\":\"Heavy Rain\",\"weather_state_abbr\":\"hr\",\"wind_direction_compass\":\"ESE\",\"created\":\"2017-12-02T03:41:02.749440Z\",\"applicable_date\":\"2017-12-01\",\"min_temp\":4.1579999999999995,\"max_temp\":6.7759999999999989,\"the_temp\":5.9649999999999999,\"wind_speed\":5.0551388319414619,\"wind_direction\":105.33146806969988,\"air_pressure\":1009.86,\"humidity\":92,\"visibility\":7.4887656088443491,\"predictability\":77},{\"id\":5850322698240000,\"weather_state_name\":\"Light Rain\",\"weather_state_abbr\":\"lr\",\"wind_direction_compass\":\"NE\",\"created\":\"2017-12-02T03:41:06.057710Z\",\"applicable_date\":\"2017-12-02\",\"min_temp\":2.6139999999999999,\"max_temp\":6.1399999999999997,\"the_temp\":5.6550000000000002,\"wind_speed\":3.9956221792794082,\"wind_direction\":42.381495570252319,\"air_pressure\":1013.22,\"humidity\":86,\"visibility\":14.223186590312574,\"predictability\":75},{\"id\":6251025967087616,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"NNE\",\"created\":\"2017-12-02T03:41:08.626560Z\",\"applicable_date\":\"2017-12-03\",\"min_temp\":1.4499999999999997,\"max_temp\":5.2479999999999993,\"the_temp\":4.8499999999999996,\"wind_speed\":2.2850908135305814,\"wind_direction\":14.238394702779383,\"air_pressure\":1025.3499999999999,\"humidity\":79,\"visibility\":13.453618368726636,\"predictability\":73},{\"id\":6091700665581568,\"weather_state_name\":\"Showers\",\"weather_state_abbr\":\"s\",\"wind_direction_compass\":\"NNE\",\"created\":\"2017-12-02T03:41:12.061190Z\",\"applicable_date\":\"2017-12-04\",\"min_temp\":2.036,\"max_temp\":5.1500000000000004,\"the_temp\":5.1999999999999993,\"wind_speed\":2.0773832062292215,\"wind_direction\":21.960966375527697,\"air_pressure\":1021.51,\"humidity\":72,\"visibility\":14.276935198441103,\"predictability\":73},{\"id\":6024357457428480,\"weather_state_name\":\"Heavy Cloud\",\"weather_state_abbr\":\"hc\",\"wind_direction_compass\":\"N\",\"created\":\"2017-12-02T03:41:14.269160Z\",\"applicable_date\":\"2017-12-05\",\"min_temp\":2.1020000000000003,\"max_temp\":5.9339999999999993,\"the_temp\":5.4950000000000001,\"wind_speed\":2.7860125778191365,\"wind_direction\":356.40366234073866,\"air_pressure\":1024.355,\"humidity\":71,\"visibility\":19.886985007555872,\"predictability\":71},{\"id\":5278173461741568,\"weather_state_name\":\"Light Cloud\",\"weather_state_abbr\":\"lc\",\"wind_direction_compass\":\"NNE\",\"created\":\"2017-12-02T03:41:17.793250Z\",\"applicable_date\":\"2017-12-06\",\"min_temp\":2.2999999999999998,\"max_temp\":7.4560000000000004,\"the_temp\":2.4300000000000002,\"wind_speed\":2.7726744810307804,\"wind_direction\":21.16721174114366,\"air_pressure\":1016.85,\"humidity\":76,\"visibility\":null,\"predictability\":70}],\"time\":\"2017-12-01T20:36:51.926320-08:00\",\"sun_rise\":\"2017-12-01T07:46:50.684834-08:00\",\"sun_set\":\"2017-12-01T16:16:59.456680-08:00\",\"timezone_name\":\"LMT\",\"parent\":{\"title\":\"Canada\",\"location_type\":\"Country\",\"woeid\":23424775,\"latt_long\":\"56.954681,-98.308968\"},\"sources\":[{\"title\":\"BBC\",\"slug\":\"bbc\",\"url\":\"http:\/\/www.bbc.co.uk\/weather\/\",\"crawl_rate\":180},{\"title\":\"Forecast.io\",\"slug\":\"forecast-io\",\"url\":\"http:\/\/forecast.io\/\",\"crawl_rate\":480},{\"title\":\"HAMweather\",\"slug\":\"hamweather\",\"url\":\"http:\/\/www.hamweather.com\/\",\"crawl_rate\":360},{\"title\":\"Met Office\",\"slug\":\"met-office\",\"url\":\"http:\/\/www.metoffice.gov.uk\/\",\"crawl_rate\":180},{\"title\":\"OpenWeatherMap\",\"slug\":\"openweathermap\",\"url\":\"http:\/\/openweathermap.org\/\",\"crawl_rate\":360},{\"title\":\"Weather Underground\",\"slug\":\"wunderground\",\"url\":\"https:\/\/www.wunderground.com\/?apiref=fc30dc3cd224e19b\",\"crawl_rate\":720},{\"title\":\"World Weather Online\",\"slug\":\"world-weather-online\",\"url\":\"http:\/\/www.worldweatheronline.com\/\",\"crawl_rate\":360},{\"title\":\"Yahoo\",\"slug\":\"yahoo\",\"url\":\"http:\/\/weather.yahoo.com\/\",\"crawl_rate\":180}],\"title\":\"Vancouver\",\"location_type\":\"City\",\"woeid\":9807,\"latt_long\":\"49.267239,-123.145264\",\"timezone\":\"America\/Vancouver\"}"}'); die();
