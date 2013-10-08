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
* @subpackage theme
* @author suzuki_mar <supasu145@gmail.com>
*/

class opSkinThemePluginActions extends sfActions
{
  /**
   * @var opThemeAssetSearch
   */
  private $search;

  /**
   * @var opThemeConfig
   */
  private $config;

  /**
   * @var opTheme
   */
  private $themes;

  public function preExecute()
  {
    parent::preExecute();

    $this->search = opThemeAssetSearchFactory::createSearchInstance();
    $this->config = new opThemeConfig();
  }

  /**
   * Executes index action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->themes = $this->search->loadThemeInstance();
    $this->useTheme = $this->config->findUseTheme();
    $this->unRegisterUseTheme = $this->config->unRegisteredIsTheme();

    $this->checkThemeDirValidity();

    $this->form = new opThemeActivationForm(array(), array('themes' => $this->themes));

    if ($request->isMethod(sfRequest::POST))
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
    }
  }

  /**
   * confirm theme is being installed correctly
   */
  private function checkThemeDirValidity()
  {
    if ($this->config->unRegisteredIsTheme())
    {
      //If it is not selected, and treated as what exists
      $this->existsUseTheme = true;
    }
    else
    {
      $this->existsUseTheme = $this->search->existsAssetsByThemeName($this->useTheme);
    }

    if ($this->existsNotInfoTheme())
    {
      $this->notInfoThemeList = $this->findNotInfoThemeNames();
    }

    $this->isExistsErrorTheme = (
            isset($this->notInfoThemeList)
            || $this->existsUseTheme === false);
  }

  private function existsNotInfoTheme()
  {
    foreach ($this->themes as $theme)
    {
      if (!$theme->existsInfoFile())
      {
        return true;
      }
    }

    return false;
  }

  private function findNotInfoThemeNames()
  {
    $notInfoList = array();
    foreach ($this->themes as $theme)
    {
      if (!$theme->existsInfoFile())
      {
        $notInfoList[] = $theme->getThemeDirName();
      }
    }

    return $notInfoList;
  }
}