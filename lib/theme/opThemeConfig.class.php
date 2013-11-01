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
 * @subpackage opSkinThemeConfig
 * @author suzuki_mar <supasu145@gmail.com>
 * @author Kaoru Nishizoe <nishizoe@tejimaya.com>
 */
class opThemeConfig
{
  public function save($themeName)
  {
    $snsConfigTable = Doctrine::getTable('SnsConfig')->retrieveByName('Theme_used');
    if (null === $snsConfigTable)
    {
      $snsConfigTable = new SnsConfig();
      $snsConfigTable->setName('Theme_used');
    }
    $snsConfigTable->setValue($themeName);
    $snsConfigTable->save();

    return true;
  }

  public function getUsedThemeName()
  {
    $snsConfigTable = Doctrine::getTable('SnsConfig')->retrieveByName('Theme_used');
    if (null === $snsConfigTable)
    {
      return null;
    }

    return $snsConfigTable->getValue();
  }

  public function removeUsedThemeName(array $invalidThemeNames, $validThemeNames)
  {
    $snsConfigTable = Doctrine::getTable('SnsConfig')->retrieveByName('Theme_used');
    if (null !== $snsConfigTable)
    {
      $usedThemeName = $snsConfigTable->getValue();
    }

    foreach ($invalidThemeNames as $invalidThemeName)
    {
      if ($invalidThemeName == $usedThemeName)
      {
        $snsConfigTable->setName('Theme_used');
        $validThemeName = '';
        foreach ($validThemeNames as $key => $value)
        {
          $validThemeName = $key;
          break;
        }
        $snsConfigTable->setValue($validThemeName);
        $snsConfigTable->save();
        break;
      }
    }

    return true;
  }
}
