<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Class that manages the set value of the theme (such as a theme to be used)
 *
 * @package OpenPNE
 * @subpackage theme
 * @author suzuki_mar <supasu145@gmail.com>
 */
class opThemeConfig
{
  public function findUseTheme()
  {
    if ($this->_findThemeUsedInstance() === null)
    {
      return null;
    }

    return $this->_findThemeUsedInstance()->getValue();
  }

  public function registeredUsedTheme()
  {
    return ($this->_findThemeUsedInstance() !== null);
  }

  public function unRegisteredIsTheme()
  {
    return!($this->registeredUsedTheme());
  }

  public function save($ThemeName)
  {
    if ($this->registeredUsedTheme())
    {
      $themeUsed = $this->_findThemeUsedInstance();
    }
    else
    {
      $themeUsed = new SnsConfig();
      $themeUsed->setName('Theme_used');
    }

    $themeUsed->setValue($ThemeName);
    $themeUsed->save();
    return true;
  }

  private function _findThemeUsedInstance()
  {
    $snsConfigTable = Doctrine::getTable('SnsConfig');
    return $snsConfigTable->retrieveByName('Theme_used');
  }
}
