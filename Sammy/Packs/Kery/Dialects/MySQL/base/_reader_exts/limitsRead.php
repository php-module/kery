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

	if (!(trait_exists('Sammy\\Packs\\Kery\\Reader\\LimitWC'))) {
	trait LimitWC {
		private static function limitsRead ($limits) {
			if (is_array($limits) && call_user_func_array('\are_numeric', $limits)) {
				return stripcslashes(
					' limit ' . join(', ',
						$limits
					)
				);
			} elseif (is_numeric($limits)) {
				return stripcslashes(
					' limit ' . $limits
				);
			}
		}
	}}
}
