<?php
/**
 * The Front Controller for handling every request
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.2.9
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

putenv('CAKE_DISABLE_GLOBAL_FUNCS=1');

// For built-in server
if (PHP_SAPI === 'cli-server') {
	$_SERVER['PHP_SELF'] = '/' . basename(__FILE__);

	$url = parse_url(urldecode($_SERVER['REQUEST_URI']));
	$file = __DIR__ . $url['path'];
	if (! str_contains($url['path'], '..') && str_contains($url['path'], '.') && is_file($file)) {
		return FALSE;
	}
}

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}


$dir = dirname(__DIR__) . DS;

$awyissPath = $dir . 'vendor' . DS . 'awyiss' . DS . 'awyiss' . DS;
require $awyissPath . 'awyiss' . DS . 'I18n' . DS . 'functions.php';
require $awyissPath . 'awyiss' . DS . 'functions.php';

$loader = require $dir . 'vendor' . DS . 'autoload.php';

use Awyiss\Awyiss;
use Cake\Http\Server;

// Bind your application to the server.
$server = new Server(new Awyiss($awyissPath . 'awyiss' . DS . 'config', null, null, $loader));

// Run the request/response through the application and emit the response.
$server->emit($server->run());
