<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
* Entity class of theme
*
* @package OpenPNE
* @subpackage theme
* @author suzuki_mar <supasu145@gmail.com>
*/
class opTheme
{
  public static function getInstance($name)
  {
    $instance = new self($name);
    return $instance;
  }

  private $themeDirName;

  private $themeInfo = array();

  public function __construct($name)
  {
    $this->themeDirName = $name;

    $parser = new opThemeInfoParser();
    $this->themeInfo = $parser->parseInfoFileByThemeName($this->themeDirName);

  }

  /**
   * for directory search
   */
  public function getThemeDirName()
  {
    return $this->themeDirName;
  }

  /**
   * Theme name in the theme file
   */
  public function getThemeName()
  {
    return $this->themeInfo['theme_name'];
  }

  public function getThemeURI()
  {
    return $this->themeInfo['theme_uri'];
  }

  public function getDescription()
  {
    return $this->themeInfo['description'];
  }

  public function getAuthor()
  {
    return $this->themeInfo['author'];
  }

  public function getVersion()
  {
    return $this->themeInfo['version'];
  }

  public function existsInfoFile()
  {
    return ($this->themeInfo !== false);
  }
}
