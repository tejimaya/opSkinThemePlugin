<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
 * search for assets of the theme's files
 *
 * @package OpenPNE
 * @subpackage opSkinThemePlugin
 * @author suzuki_mar <supasu145@gmail.com>
 * @author Kaoru Nishizoe <nishizoe@tejimaya.com>
 */
class opThemeAssetSearcher extends opInstalledPluginManager
{
  private $webPath;
  private $themePath;

  /**
   * constructor of opThemeAssetSearcher
   *
   * @param null $webPath public directory of opSkinThemePlugin
   * @param null $themePath directory that contains the theme directory
   */
  public function __construct($webPath = null, $themePath = null)
  {
    if (null == $webPath)
    {
      $webPath = sfConfig::get('sf_web_dir').'/opSkinThemePlugin';
    }
    if (null == $themePath)
    {
      $themePath = __DIR__.'/../../web';
    }

    $this->webPath = $webPath;
    $this->themePath = $themePath;
  }

  /**
   * 指定されたテーマディレクトリ名のテーマが正しいテーマかどうかを判定する。
   *
   * @param $themeDirName String テーマディレクトリ名
   * @return boolean 指定されたテーマディレクトリ名がただし場合はtrue、それ以外はfalse
   */
  public function isAvailableTheme($themeDirName)
  {
    // ディレクトリ名が指定されていない
    if (null === $themeDirName)
    {
      return false;
    }

    // ディレクトリが存在しない
    if (!file_exists($this->getWebDir().'/'.$themeDirName))
    {
      return false;
    }

    $themeObjects = $this->getThemes();
    $validThemeObjects = $themeObjects['valid'];
    if (!isset($validThemeObjects[$themeDirName]))
    {
      return false;
    }

    return true;
  }

  public function existsAssetsByThemeName($themeName)
  {
    if (null === $themeName)
    {
      return false;
    }

    $themeName = $this->getWebDir().'/'.$themeName;

    return file_exists($themeName);
  }

  /**
   * Find theme alternative if you can not use the selected theme
   */
  public function findSubstitutionTheme()
  {
    $themeObjects = $this->getThemes();
    $validThemeObjects = $themeObjects['valid'];
    $selectThemeDirName = '';
    foreach ($validThemeObjects as $key => $value)
    {
      $selectThemeDirName = $key;
      break;
    }
    return str_replace($this->getWebDir(), '', $selectThemeDirName);
  }

  public function findAssetsPathByThemeNameAndType($themeName, $type)
  {
    $pattern = $this->getWebDir().'/'.$themeName.'/'.$type.'/'.'*.'.$type;

    $files = array();
    foreach (glob($pattern) as $fileName)
    {
      $files[] = str_replace($this->getWebDir(), '/opSkinThemePlugin', $fileName);
    }

    if (empty($files))
    {
      return false;
    }

    return $files;
  }

  public function getThemePath()
  {
    return $this->themePath;
  }

  public function getWebDir()
  {
    return $this->webPath;
  }

  /**
   * Get all of the theme list that have been installed.
   *
   * @return array all of theme list that have been installed.
   */
  public function getThemes()
  {
    $allDirNames = $this->_getAllDirectoryNames();
    $validDirNames = array();
    $invalidDirNames = array();

    // validate the directory names
    foreach ($allDirNames as $dirName)
    {
      // validate the directory names is Alphanumeric
      if (preg_match("/^[a-zA-Z0-9_-]+$/", $dirName))
      {
        $validDirNames[] = $dirName;
      }
      else
      {
        $invalidDirNames[] = $dirName;
      }
    }

    $themeObjects = array();
    $notExistsMainCssDirNames = array();
    // validate the directory names has main.css
    foreach ($validDirNames as $dirName)
    {
      //it is not treated as a theme directory that there is no main.css
      $mainCssPath = $this->getThemePath().'/'.$dirName.'/css/main.css';
      if (file_exists($mainCssPath))
      {
        $themeObj = new opTheme($dirName);
        if (null != $themeObj->getThemeName() && null != $themeObj->getAuthor())
        {
          $themeObjects[$dirName] = new opTheme($dirName);
        }
        else
        {
          $notExistsMainCssDirNames[] = $dirName;
        }
      }
      else
      {
        $notExistsMainCssDirNames[] = $dirName;
      }
    }
    $notThemeDirNames = array('invalid' => $invalidDirNames, 'maincss' => $notExistsMainCssDirNames);

    return array('valid' => $themeObjects, 'invalid' => $notThemeDirNames);
  }

  /**
   * Get all of the directory names that have been installed.
   *
   * @return array all of directory names that have been installed.
   */
  private function _getAllDirectoryNames()
  {
    $pattern = $this->getThemePath().'/*';

    $dirNames = array();
    foreach (glob($pattern, GLOB_ONLYDIR) as $dirPath)
    {
      $dirNames[] = pathinfo($dirPath, PATHINFO_FILENAME);
    }

    return $dirNames;
  }
}