<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Kery
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Kery {

	if (!(function_exists('Sammy\\Packs\\Kery\\data'))) {
	/**
	 * [data description]
	 * @param  string $data [description]
	 * @return [type]       [description]
	 */
	function data ($data = '') {
		return '\''.\str($data).'\'';
	}}
}
