<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
 * opSkinThemePlugin actions.
 *
 * @package OpenPNE
 * @subpackage opSkinThemePlugin
 * @author suzuki_mar <supasu145@gmail.com>
 * @author Kaoru Nishizoe <nishizoe@tejimaya.com>
 */
class opSkinThemePluginActions extends sfActions
{
  /**
   * @var opThemeAssetSearcher
   */
  private $searcher;

  /**
   * @var opThemeConfig
   */
  private $config;

  public function preExecute()
  {
    parent::preExecute();

    $this->searcher = new opThemeAssetSearcher();
    $this->config = new opThemeConfig();
  }

  /**
   * Executes index action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $themeObjects = $this->searcher->getThemes();
    $this->validThemeObjects = $themeObjects['valid'];
    $this->invalidDirNames = $themeObjects['invalid']['invalid'];
    $this->invalidMainCss = $themeObjects['invalid']['maincss'];
    $this->config->removeUsedThemeName(array_merge($this->invalidDirNames, $this->invalidMainCss), $this->validThemeObjects);
    $this->usedThemeName = $this->config->getUsedThemeName();
    if (null === $this->usedThemeName)
    {
      $this->existsUsedTheme = true;
    }
    else
    {
      $this->existsUsedTheme = $this->searcher->existsAssetsByThemeName($this->config->getUsedThemeName());
    }

    $this->form = new opThemeActivationForm(array(), array('themes' => $this->validThemeObjects));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($this->request->getParameter('theme_activation'));
      if ($this->form->isValid())
      {
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->form->save();
      }
      else
      {
        $this->getUser()->setFlash('error', $this->form->getErrorSchema()->getMessage());
      }
      $this->redirect('opSkinThemePlugin/index');
    }
  }
}