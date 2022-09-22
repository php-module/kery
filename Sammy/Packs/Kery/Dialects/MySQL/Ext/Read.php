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
  if (!trait_exists('Sammy\Packs\Kery\Dialects\MySQL\Ext\Read')){
  /**
   * @trait Read
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
  trait Read {
    /**
     * [Read description]
     * @param array $datas Sent datas to the query
     */
    public static function Read ($tableName, $query = null) {
      if (!is_array ($query)) {
        $query = array ();
      }

      $datas = self::readQuery ($query);

      #exit (json_encode($datas))

      /**
       * [$filter description]
       * @var string
       * Filter're whole the fields
       * being filtered for the given
       * table. This is '*' by default
       * in order getting whole the fields
       * in it.
       */
      $filter = '*';
      /**
       * [$where description]
       * @var string
       */
      $where = '';
      /**
       * [$group description]
       * @var string
       */
      $group = '';
      /**
       * [$order description]
       * @var string
       */
      $order = '';
      /**
       * [$limit description]
       * @var string
       */
      $limit = '';

      if (isset($datas['@filter']) && is_array($datas['@filter']))
        $filter = self::readFilterWildCard ($datas['@filter']);

      if (isset($datas['where']) && is_array($datas['where']))
        $where = self::readWhereWildCard ($datas['where']);

      if (isset($datas['group']) && is_array($datas['group']))
        $group = self::readGroupWildCard ($datas['group']);

      if (isset($datas['order']) && is_array($datas['order']))
        $order = self::readOrderWildCard ($datas['order']);

      if (isset($datas['limit']) && is_array($datas['limit']))
        $limit = self::readLimitWildCard ($datas['limit']);

      return join ('', [
        'select ',
        $filter,
        ' from',
        " `{$tableName}`",
        $where,
        $group,
        $order,
        $limit,
      ]);
    }

    /**
     * @method array readQuery
     */
    public static function readQuery ($query) {
      $datas = ['@filter' => []];

      if (!(is_array ($query) && $query)) {
        return $datas;
      }

      $funcRe = '/^([a-zA-Z0-9_]+)\s*\(\s*\)\s*$/';

      foreach ($query as $key => $value) {
        /**
         * Verify is the current key is an int data
         * , so... Assume value is a data to filter
         */
        $keyIsNumberOrString = ( boolean ) (
          is_int ($key) || is_string ($key)
        );

        /**
         *
         *
         */
        if ($keyIsNumberOrString && is_string ($value)) {
          $keyName = is_int ($key) ? $value : $key;
          $datas ['@filter'][$keyName] = $value;
        } elseif (preg_match ($funcRe, $key, $funcNameMatch)) {
          $funcName = strtolower ($funcNameMatch [1]);

          $datas [ $funcName ] = $value;
        } elseif (is_string ($key) && is_array ($value)) {
          #echo $key, "<br />";
        }
      }

      return $datas;
    }
  }}
}
