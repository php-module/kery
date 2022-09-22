<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Kery\Dialects\MySQL\Ext\WildCard
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\Kery\Dialects\MySQL\Ext\WildCard {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\Packs\Kery\Dialects\MySQL\Ext\WildCard\Where')){
  /**
   * @trait Where
   * Base internal trait for the
   * Kery\Dialects\MySQL\Ext\WildCard module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   */
  trait Where {
    /**
     * @method string readWhereWildCard
     */
    private static function readWhereWildCard ($where) {
      if (!(is_array($where) && $where))
        return;

      $where = self::groupNestedConditions ($where);

      $wheres_str = ' where (';
      # $where => The array containg the
      # coditions options to be used as
      # an argument to filter each row
      # from the table.
      # $key => a number or string
      # it is field name if it is a string
      # , otherwise it is a group.
      foreach ($where as $key => $option) {
        if (is_int($key) && (is_array($option) && $option)) {
          #if (!$option)
          # continue;

          $wheres_str .= '(';

          foreach ($option as $field => $value) {
            if (is_array($value) && $value) {
              $v = '';
              #
              #...
              $wheres_str .= '(';

              foreach ($value as $key => $val) {
                if (is_string($key) && $key) {
                  $v_ = is_array($val) ? self::tupple ($val) : (
                    '\'' . (string)($val) . '\''
                  );

                  $wheres_str .= (
                    '`'.(string)($field).'` '.$key.' ' . $v_ . ' and '
                  );
                }
              }

              $wheres_str = preg_replace('/(\s*and\s*)$/i', ') and ',
                $wheres_str
              );

            } else {
              $wheres_str .= (
                '`'.(string)($field).'` = \''.(string)($value).'\' and '
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
                        is_array($kv) ? self::tupple ($kv) : (
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
                      is_array ($val) ? self::tupple ($val) : (
                        '`'.$val.'`'
                      )
                    ) . ' or '
                  );
                }
              } elseif (is_string($op)) {
                $wheres_str .= (
                  '`'.$key.'` '.$op.' ' . (
                    is_array($val) ? self::tupple ($val) : (
                      '\''.$val.'\''
                    )
                  ) . ' or '
                );
              }
            }

            # ... Close pharentesis
            $wheres_str = preg_replace ('/(\s*or\s*)$/i', ') or ',
              $wheres_str
            );
          } else {
            $wheres_str .= (
              '`'.(string)($key).'` = \''.(string)($option).'\' or '
            );
          }
        }
      }

      return preg_replace ('/(\s*(or)\s*)$/i', ')', $wheres_str);
    }

    private static function groupNestedConditions ($where) {
      $conditions = [];
      $topLevelConditions = [];

      foreach ($where as $key => $value) {
        if (is_string ($key)) {
          $topLevelConditions [$key] = $value;
        } else {
          $conditions [$key] = $value;
        }
      }

      return array_merge ([$topLevelConditions], $conditions);
    }

  }}
}
