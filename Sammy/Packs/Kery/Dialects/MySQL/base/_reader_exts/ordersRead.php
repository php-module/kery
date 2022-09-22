<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Kery\Reader
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Kery\Reader {

	if (!(trait_exists('Sammy\\Packs\\Kery\\Reader\\OrderWC'))) {
	trait OrderWC {
		private static function ordersRead ($orders) {
			if (!(is_array($orders) && $orders))
				return;

			/**
			 * [$orders_str description]
			 * @var [type]
			 */
			$orders_str = (
				' order by '
			);

			foreach ($orders as $f => $o) {
				if (is_int($f)) {
					$field = \str($o);
					$order = 'asc';
				} else {
					$field = \str($f);
					$order = \str($o);
				}
				$orders_str .= (
					'`'.$field.'` ' . $order . ', '
				);
			}

			return ($orders_str = preg_replace('/(,\s*)$/', '',
				$orders_str
			));
		}
	}}
}
