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

	if (!(trait_exists('Sammy\\Packs\\Kery\\Reader\\WhereWC'))) {
	trait WhereWC {
		private static function wheresRead ($where) {
			if (!(is_array($where) && $where))
				return;

			$wheres_str = ' where (';
			# $where => The array containg the
			# coditions options to be used as
			# an argument to filter each row
			# from the table.
			# $key => a number or string
			# it is field name if it is a string
			# , otherwise it is a group.
			foreach ($where as $key => $option) {
				if (is_int($key) && is_array($option)) {
					#if (!$option)
					#	continue;

					$wheres_str .= '(';
					foreach ($option as $field => $value) {
						if (is_array($value) && $value) {
							$v = '';
							#
							#...
							$wheres_str .= '(';

							foreach ($value as $key => $val) {
								if (is_string($key) && $key) {
									$v_ = is_array($val) ? \Tupple($val) : (
										'\'' . \str($val) . '\''
									);

									$wheres_str .= (
										'`'.\str($field).'` '.$key.' ' . $v_ . ' and '
									);
								}
							}

							$wheres_str = preg_replace('/(\s*and\s*)$/i', ') and ',
								$wheres_str
							);

						} else {
							$wheres_str .= (
								'`'.\str($field).'` = \''.\str($value).'\' and '
							);
						}
					}

					$wheres_str = preg_replace('/(\s*and\s*)$/i', ') or ',
						$wheres_str
					);

				} elseif (is_string($key)) {
					if (is_array($option) && $option) {

						$wheres_str .= '(';
						# $op => operator
						# $val => value
						foreach ($option as $op => $val) {
							if (is_int($op)) {
								# ...
								if (is_array($val) && $val) {

									$wheres_str .= '(';
									# $k => key
									# $kv => $keyValue
									foreach ($val as $k => $kv) {
										$wheres_str .= (
											'`'.$key.'` '.$k.' ' . (
												is_array($kv) ? \Tupple($kv) : (
													'\''.$kv.'\''
												)
											) . ' and '
										);
									}

									# ...
									$wheres_str = (
										preg_replace(
											'/(\s*and\s*)$/i', ') or ',
											$wheres_str
										)
									);
								} else {
									$wheres_str .= (
										'`'.$key.'` = ' . (
											is_array($val) ? \Tupple($val) : (
												'`'.$val.'`'
											)
										) . ' or '
									);
								}
							} elseif (is_string($op)) {
								$wheres_str .= (
									'`'.$key.'` '.$op.' ' . (
										is_array($val) ? \Tupple($val) : (
											'\''.$val.'\''
										)
									) . ' or '
								);
							}
						}


						# ... Close pharentesis
						$wheres_str = preg_replace('/(\s*or\s*)$/i', ') or ',
							$wheres_str
						);
					} else {
						$wheres_str .= (
							'`'.\str($key).'` = \''.\str($option).'\' or '
						);
					}
				}
			}

			return preg_replace('/(\s*(or)\s*)$/i', ')',
				$wheres_str
			);
		}
	}}
}
