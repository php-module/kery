<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Kery\Dialects\MySQL\Ext
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
namespace Sammy\Packs\Kery\Dialects\MySQL\Ext {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\Packs\Kery\Dialects\MySQL\Ext\Create')){
  /**
   * @trait Create
   * Base internal trait for the
   * Kery\Dialects\MySQL\Ext module.
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
  trait Create {
    /**
     * @method string Create
     *
     * - Generate an insertion query based on the
     * - given informations from the '$datas' parameter
     * - wish should be the last one.
     *
     * @param array|null $options options
     * @param array $datas
     *  - Sent datas to the query
     */
    public static function Create ($tableName, $options = []) {
      $argsCount = func_num_args ();
      # Assume that '$options' whas not sent
      # if there is not more that three arguments
      # sent for current method.
      # $datas should be avaluated as the argument.
      $datas = array_merge ($options, []);
      /**
       * On condition that there is more or
       * equal than three arguments, evaluate
       * $datas as the last one.
       */
      if ($argsCount >= 3) {
        $datas = func_get_arg (-1 + $argsCount);
      } else {
        $options = [ 'bind' => false ];
      }

      if (!(is_array ($datas) && $datas)) {
        return null;
      }

      $into = $tableName;
      $bind = self::activedOption ($options, 'bind');

      $privateFields = preg_split ('/\s+/', '@into');

      $q_str = 'insert into `' . $into . '` ';
      $fields = '(';
      $values = '(';

      foreach ($datas as $field => $value) {
        if (!in_array ($field, $privateFields)) {
          $_value = join ('', [
            '\'', self::bindValue ($value), '\', '
          ]);

          $fields .= '`'.$field.'`, ';
          $values .= $bind ? $_value : '?, ';
        }
      }

      $fields = preg_replace ('/(,\s*)$/', ')', $fields);
      $values = preg_replace ('/(,\s*)$/', ')', $values);

      return ($q_str .= ($fields . ' values ' . $values));
    }

    protected static function bindValue ($value) {
      return addslashes (stripcslashes ($value));
    }

    /**
     * @method boolean atcivedOption
     */
    protected static function activedOption ($options, $option) {
      return ( boolean ) (
        is_array ($options) &&
        is_string ($option) &&
        isset ($options [$option]) &&
        is_bool ($options [$option]) &&
        $options [$option]
      );
    }
  }}
}
