<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
* Theme applied event class
*
* @package OpenPNE
* @subpackage theme
* @author suzuki_mar <supasu145@gmail.com>
*/
class opThemeEvent
{
  public static function enableTheme(sfEvent $event)
  {
    if (!self::isFrontend())
    {
      return false;
    }

    if (self::isPreviewModule())
    {
      return false;
    }

    $themeInfo = new opThemeConfig();

    if ($themeInfo->unRegisteredIsTheme())
    {
      sfContext::getInstance()->getUser()->setFlash('error', sfContext::getInstance()->getI18n()->__('Theme is not registered.'), false);
      return false;
    }

    $themeName = $themeInfo->findUseTheme();
    $themeSearch = opThemeAssetSearchFactory::createSearchInstance();

    if (!$themeSearch->existsAssetsByThemeName($themeName))
    {
      $themeName = $themeSearch->findSubstitutionTheme();
    }

    self::enableSkinByTheme($themeName);
  }

  public static function enablePreviewTheme(sfEvent $event)
  {
    if (!self::isFrontend())
    {
      return false;
    }

    if (!self::isPreviewModule())
    {
      return false;
    }

    $request = sfContext::getInstance()->getRequest();
    $themeName = $request->getParameter('theme_name');

    if ($themeName === null)
    {
      return false;
    }

    $themeSearch = opThemeAssetSearchFactory::createSearchInstance();

    if (!$themeSearch->existsAssetsByThemeName($themeName))
    {
      return false;
    }

    self::enableSkinByTheme($themeName);
  }

  private static function isPreviewModule()
  {
    return (sfContext::getInstance()->getModuleName() === 'skinpreview');
  }

  private static function isFrontend()
  {
    $application = sfContext::getInstance()->getConfiguration()->getApplication();
    return ($application === 'pc_frontend');
  }

  public static function enableSkinByTheme($themeName)
  {
    $themeSearch = opThemeAssetSearchFactory::createSearchInstance();

    $assetsType = array('css', 'js');
    foreach ($assetsType as $type)
    {
      $filePaths = $themeSearch->findAssetsPathByThemeNameAndType($themeName, $type);

      if ($filePaths !== false)
      {
        self::includeCssOrJs($filePaths, $type);
      }
    }
  }

  /**
   * @todo process in the case of non-javascript or css
   */
  private static function includeCssOrJs($filePaths, $type)
  {
    $response = sfContext::getInstance()->getResponse();

    if ($type === 'css')
    {
      foreach ($filePaths as $file)
      {
        $response->addStylesheet($file, 'last');
      }
    }

    if ($type === 'js')
    {
      foreach ($filePaths as $file)
      {
        $response->addJavaScript($file, 'last');
      }
    }
  }
}