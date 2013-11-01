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
 * @subpackage opSkinThemePlugin
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

    $config = new opThemeConfig();
    $themeName = $config->getUsedThemeName();
    $themeSearcher = new opThemeAssetSearcher();
    if (!$themeSearcher->isAvailableTheme($themeName))
    {
      $themeName = $themeSearcher->findSubstitutionTheme();
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

    if (null === $themeName)
    {
      return false;
    }

    $themeSearcher = new opThemeAssetSearcher();
    if (!$themeSearcher->isAvailableTheme($themeName))
    {
      return false;
    }

    self::enableSkinByTheme($themeName);
  }

  private static function isPreviewModule()
  {
    return ('skinpreview' === sfContext::getInstance()->getModuleName());
  }

  private static function isFrontend()
  {
    return ('pc_frontend' === sfContext::getInstance()->getConfiguration()->getApplication());
  }

  public static function enableSkinByTheme($themeName)
  {
    $themeSearcher = new opThemeAssetSearcher();

    $assetsType = array('css', 'js');
    foreach ($assetsType as $type)
    {
      $filePaths = $themeSearcher->findAssetsPathByThemeNameAndType($themeName, $type);

      if (false !== $filePaths)
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

    if ('css' === $type)
    {
      foreach ($filePaths as $file)
      {
        $response->addStylesheet($file, 'last');
      }
    }

    if ('js' === $type)
    {
      foreach ($filePaths as $file)
      {
        $response->addJavaScript($file, 'last');
      }
    }
  }
}