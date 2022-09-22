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
  if (!trait_exists('Sammy\Packs\Kery\Dialects\MySQL\Ext\Update')){
  /**
   * @trait Update
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
  trait Update {
    /**
     * [Update description]
     * @param array $datas Sent datas to the query
     */
    public static function Update ($tableName) {
      $args = func_get_args ();

      $datas = $args [-1 + count ($args)];

      if (!(is_array ($datas) && $datas)) {
        return null;
      }

      $datas = self::readQuery ($datas);
      $options = self::getOptions ($args);
      $datas = array_merge (['@filter' => []], $datas);

      $q_str = 'update `' . $tableName . '`';
      /**
       * [$where description]
       * @var string
       */
      $where = '';
      /**
       * [$set description]
       * @var string
       */
      $set = '';

      if (isset($datas['where']) && is_array($datas['where'])) {
        $where = self::readWhereWildCard ($datas['where']);
      }


      if (is_array($datas['@filter']) && $datas['@filter']) {
        $q_str .= ' set ';
        foreach ($datas ['@filter'] as $field => $value) {
          $q_str .= ('`'.strtolower ($field).'` = ' .
            self::data ($value, $options) . ' , '
          );
        }

        $q_str = preg_replace ('/(\s*,\s*)$/i', '', $q_str);
      }

      return join ('', [$q_str, $where]);
    }
  }}
}
