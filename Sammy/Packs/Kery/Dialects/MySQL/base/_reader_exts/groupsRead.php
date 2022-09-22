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

	if (!(trait_exists('Sammy\\Packs\\Kery\\Reader\\GroupWC'))) {
	trait GroupWC {
		private static function groupsRead ($group) {
			if (!(is_array($group) && $group))
				return '';
			$group_count = count($group);
			$group_str = ' group by ';

			foreach ($group as $key => $val) {
				if (is_int($key)) {
					$group_str .= '`'.\lower($val).'`,';
				} elseif (is_string($key)) {
					if (!(is_array($val) && $val)) {
						$group_str .= '`'.\lower($key).'`,';
						continue;
					}

					$group_str .= '`'.\lower($key).'` having (';
					$val_len = count($val);

					for ($i = 0; $i < $val_len; $i++) {
						if (!isset($val[ $i ]))
							continue;
						# each array inside the '$val'
						# array indicating an alternaive
						# for the 'having' condition
						$option = $val[ $i ];

						if (!(is_array($option) && $option))
							continue;

						$group_str .= '(';

						foreach ( $option as $oi => $v ) {

							if (is_int($oi)) {
								$op = is_array($v) ? 'in' : '=';
							} else {
								$op = $oi;
							}

							if (is_array($v)) {
								$group_str .= (
									'`'.$key.'` '.$op.' '.\Tupple($v).' and '
								);
							} else {
								$group_str .= (
									'`'.$key.'` '.$op.' \''.\str($v).'\' and '
								);
							}

						}

						# add the 'or' statement
						# at the final of the ?option
						# after removing the 'and'
						# keyword at the end of the
						# string
						$group_str = preg_replace('/(\s*and\s*)$/i', ') or ',
							$group_str
						);
					}

					$group_str = preg_replace('/(\s*or\s*)$/i', '),',
						$group_str
					);

					$group_str = preg_replace('/(\s*having\s*\()$/i', '',
						$group_str
					);
				}
			}

			return ($group_str = preg_replace('/(,\s*)$/', '',
				$group_str
			));
		}
	}}
}
