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

	if (!(trait_exists('Sammy\\Packs\\Kery\\Reader\\FilterWC'))) {
	trait FilterWC {
		private static function filtersRead ($filter) {
			if (!(is_array($filter) && $filter))
				return '*';
			$filter_count = count($filter);
			$filter_str = '';

			for ($i = 0; $i < $filter_count; $i++) {
				$filter_str .= ( is_string($filter[$i]) &&
					!empty(trim($filter[$i])) ? '`'.trim($filter[$i]).'`,' : ''
				);
			}

			$filter_str = preg_replace ('/(,\s*)$/', '',
				$filter_str
			);

			return (
				$filter_str
			);
		}
	}}
}

