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
  if (!trait_exists('Sammy\Packs\Kery\Dialects\MySQL\Ext\WildCard\Group')){
  /**
   * @trait Group
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
  trait Group {
    /**
     * @method string readGroupWildCard
     */
    private static function readGroupWildCard ($group) {
      if (!(is_array($group) && $group))
        return '';
      $group_count = count ($group);
      $group_str = ' group by ';

      #$group = self::groupNestedConditions ($group);

      #print_r($group);

      #exit (0);

      foreach ($group as $key => $val) {
        if (is_int ($key) && is_string ($val)) {
          $group_str .= '`'.strtolower ($val).'`,';
        } elseif (is_string ($key)) {
          if (!(is_array ($val) && $val)) {
            $group_str .= '`'.strtolower ($key).'`,';
            continue;
          }

          $val = self::groupNestedGroupingConditions ($val);

          $group_str .= '`'.strtolower ($key).'` having (';
          $valLen = count($val);

          for ($i = 0; $i < $valLen; $i++) {
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
                  '`'.$key.'` '.$op.' '.self::tupple ($v).' and '
                );
              } else {
                $group_str .= (
                  '`'.$key.'` '.$op.' \''.(string)($v).'\' and '
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


    /**
     * @method array groupNestedGroupingConditions
     */
    private static function groupNestedGroupingConditions ($group) {
      $rules = [];
      $topLevelRules = [];

      foreach ($group as $key => $value) {
        if (is_string ($value)) {
          $topLevelRules [$key] = $value;
        } else {
          $rules [$key] = $value;
        }
      }

      return array_merge ([$topLevelRules], $rules);
    }
  }}
}
