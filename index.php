<?php


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}


function isBot(string $userAgent)
{
	$bots = [
		'googlebot',
		'bingbot',
		'slurp',
		'duckduckbot',
		'baiduspider',
		'yandexbot',
		'sogou',
		'exabot',
		'facebot',
		'ia_archiver',
		'mj12bot',
		'ahrefsbot',
		'semrushbot'
	];

	$userAgent = strtolower($userAgent ?? ''); // Accessing $_SERVER here

	foreach ($bots as $bot) {
		if (strpos($userAgent, $bot) !== false) {
			return true; // It's a bot
		}
	}

	return false; // It's a human (probably)
}



if (isBot($_SERVER['HTTP_USER_AGENT'] ?? '')) {
	exit;
} else {
	include_once 'render.php';
}
