<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Form class of theme selection
 *
 * @package OpenPNE
 * @subpackage opSkinThemePlugin
 * @author suzuki_mar <supasu145@gmail.com>
 * @author Kaoru Nishizoe <nishizoe@tejimaya.com>
 */
class opThemeActivationForm extends sfForm
{
  const THEME_FILED_KEY = 'theme';

  public function configure()
  {
    $widgetOptions = array(
      'choices' => $this->findSelectThemes(),
      'multiple' => true,
      'expanded' => true,
      'renderer_options' => array('formatter' => array($this, 'formatter'))
    );

    $widgetOptions['multiple'] = false;
    $this->setWidget(self::THEME_FILED_KEY, new sfWidgetFormChoice($widgetOptions));

    $validatorOptions = array(
      'choices' => array_keys($this->findSelectThemes()),
      'multiple' => true,
      'required' => false,
    );
    $validatorOptions['multiple'] = false;
    $validatorOptions['required'] = true;

    $validatorMessages = array();
    $validatorMessages['required'] = 'You must activate only any theme.';

    $this->setValidator(self::THEME_FILED_KEY, new sfValidatorChoice($validatorOptions, $validatorMessages));
    $this->setDefault(self::THEME_FILED_KEY, $this->findDefaultThemeName());
    $this->widgetSchema->setNameFormat('theme_activation[%s]');
  }

  private function findSelectThemes()
  {
    $themes = $this->getOption('themes');

    $choices = array();
    foreach ($themes as $theme)
    {
      $choices[$theme->getThemeDirName()] = $theme->getThemeDirName();
    }

    return $choices;
  }

  public function findDefaultThemeName()
  {
    $themeConfig = new opThemeConfig();
    $defaultTheme = $themeConfig->getUsedThemeName();
    if (null === $defaultTheme)
    {
      if ($this->emptySelectTheme())
      {
        $defaultTheme = array_shift($this->findSelectThemes());
      }
    }

    return $defaultTheme;
  }

  private function emptySelectTheme()
  {
    $selectThemes = $this->findSelectThemes();
    return !(empty($selectThemes));
  }

  public function formatter($widget, $inputs)
  {
    if (empty($inputs))
    {
      return '';
    }

    $themes = $this->getOption('themes');

    $rows = array();
    foreach ($inputs as $id => $input)
    {
      $match = array();
      preg_match('/(.*_theme_)(.*)$/', $id, $match);
      $name = $match[2];
      $theme = $themes[$name];
      $rows[] = $this->createRowTag($widget, $input, $theme);
    }
    $rowString = implode($widget->getOption('separator'), $rows);

    return $rowString;
  }

  private function createRowTag($widget, $input, opTheme $theme)
  {
    $linkUrl = sfContext::getInstance()->getConfiguration()->generateAppUrl('pc_frontend', array('sf_route' => 'skin_preview', 'theme_name' => $theme->getThemeDirName()), true);
    $linkTag = '<a href="'.$linkUrl.'" target="_blank">'.sfContext::getInstance()->getI18n()->__('Preview').'</a>';

    if (0 === strpos($theme->getThemeURI(), 'http', 0))
    {
      $themeName = '<a href="'.$theme->getThemeURI().'" target="_blank">'.$theme->getThemeName().'</a>';
    }
    else
    {
      $themeName = $theme->getThemeName();
    }

    if (0 === strpos($theme->getAuthorURI(), 'http', 0))
    {
      $author = '<a href="'.$theme->getAuthorURI().'" target="_blank">'.$theme->getAuthor().'</a>';
    }
    else
    {
      $author = $theme->getAuthor();
    }

    $rowContents = array(
      'button' => $input['input'],
      'name' => $themeName,
      'author' => $author,
      'version' => $theme->getVersion(),
      'description' => $theme->getDescription(),
      'link' => $linkTag,
    );

    $rowContentTag = '';
    foreach ($rowContents as $content)
    {
      $rowContentTag .= $widget->renderContentTag('td', $content);
    }

    return $widget->renderContentTag('tr', $rowContentTag);
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    parent::bind($taintedValues, $taintedFiles);

    if (count($this->errorSchema))
    {
      $newErrorSchema = new sfValidatorErrorSchema($this->validatorSchema);
      foreach ($this->errorSchema as $name => $error)
      {
        if (self::THEME_FILED_KEY === $name)
        {
          $newErrorSchema->addError($error);
        }
        else
        {
          $newErrorSchema->addError($error, $name);
        }
      }
      $this->errorSchema = $newErrorSchema;
    }
  }

  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $value = $this->values[self::THEME_FILED_KEY];
    $skinThemeInfo = new opThemeConfig();

    return $skinThemeInfo->save($value);
  }
}