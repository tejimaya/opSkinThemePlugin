<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
* Factory class for creating opThemeLoader
*
* @package OpenPNE
* @subpackage theme
* @author suzuki_mar <supasu145@gmail.com>
*/
class opThemeAssetSearchFactory
{
  /**
   * @return opSkinThemeAssetSearch
   */
  public static function createSearchInstance()
  {
    $loaderParams = array();
    $loaderParams['web_path']   = sfConfig::get('sf_web_dir').'/opSkinThemePlugin';
    $loaderParams['theme_path'] = __DIR__.'/../../web';

    return new opThemeAssetSearch($loaderParams);
  }
}