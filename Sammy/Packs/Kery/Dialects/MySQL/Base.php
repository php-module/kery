<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Kery\Dialects\MySQL
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
namespace Sammy\Packs\Kery\Dialects\MySQL {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\Packs\Kery\Dialects\MySQL\Base')){
  /**
   * @trait Base
   * Base internal trait for the
   * Kery\Dialects\MySQL module.
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
  trait Base {
    use Ext\Create;
    use Ext\Read;
    use Ext\Update;
    use Ext\Destroy;

    use Ext\WildCard\Filter;
    use Ext\WildCard\Group;
    use Ext\WildCard\Limit;
    use Ext\WildCard\Order;
    use Ext\WildCard\Where;

    protected static function tupple ($array = null) {
      if (!(is_array($array) && $array)) {
        return '()';
      }

      $array_len = count ($array);
      $tupple_str = '(';

      for ($i = 0; $i < $array_len; $i++) {
        $current_item = $array [ $i ];

        if (is_array ($current_item)) {
          $tupple_str .= self::tupple ($current_item) . ', ';
        } else {
          $tupple_str .= ('\''.(string)($current_item).'\', ');
        }
      }

      return preg_replace ( '/(,\s*)$/', '',
        $tupple_str
      ) . ')';
    }

    /**
     * @method boolean areNumbers
     */
    protected static function areNumbers (array $values) {
      foreach ($values as $value) {
        if (!is_numeric ($value))
          return false;
      }

      return true;
    }

    protected static function data ($data) {
      return '\''. (string) ($data) .'\'';
    }
  }}
}
