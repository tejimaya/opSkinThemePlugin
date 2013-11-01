<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
 * opSkinThemePlugin preview actions.
 *
 * @package OpenPNE
 * @subpackage opSkinThemePlugin
 * @author suzuki_mar <supasu145@gmail.com>
 */
class skinPreviewActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $themeSearcher = new opThemeAssetSearcher();

    $this->themeName = $request->getParameterHolder()->get('theme_name');
    if (null === $this->themeName)
    {
      $this->forward404('Request parameter id does not exist.');
    }

    $this->isExistsTheme = $themeSearcher->isAvailableTheme($this->themeName);

    $this->emptyThemeName = ($this->themeName === null);
  }
}