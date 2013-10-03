<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
* to parse the theme information reads the configuration file
*
* @package OpenPNE
* @subpackage theme
* @author suzuki_mar <supasu145@gmail.com>
*/
class opThemeInfoParser
{
  /**
   * @var opThemeAssetSearch
   */
  private $search;

  public function __construct()
  {
    $this->search = opThemeAssetSearchFactory::createSearchInstance();
  }

  private function getConfigNames()
  {
    return array(
        'Theme Name',
        'Theme URI',
        'Description',
        'Author',
        'Version',
    );
  }

  public function parseInfoFileByThemeName($themeName)
  {
    $infoPath = $this->search->getThemePath().'/'.$themeName.'/css/main.css';
    $fp = fopen($infoPath, 'r');

    if (!$fp)
    {
      fclose($fp);
      return false;
    }

    $configLines = $this->clipConfigLines($fp);

    if ($configLines === false)
    {
      return false;
    }

    return $this->parseConfigLines($configLines);
  }

  private function clipConfigLines($fp)
  {
    $commentLineStart = false;

    while (!feof($fp))
    {
      $line = trim(fgets($fp));

      if (strpos($line, '/*') !== false)
      {
        $commentLineStart = true;
      }

      if ($commentLineStart)
      {
        $configLines[] = $line;
      }

      if (strpos($line, '*/') !== false)
      {

        if ($this->isConfigLines($configLines))
        {
          //remove the start and end of the block
          array_shift($configLines);
          array_pop($configLines);

          fclose($fp);
          return $configLines;
        }

        $commentLineStart = false;
        $configLines = array();
      }
    }

    fclose($fp);
    return false;
  }

  private function parseConfigLines(array $configLines)
  {
    $configs = array();
    foreach ($this->getConfigNames() as $name)
    {
      foreach ($configLines as $line)
      {
        $configNameFiled = $name.':';

        if (strpos($line, $configNameFiled) !== false)
        {
          $value = str_replace($configNameFiled, '', $line);
          $key = $this->toConfigKeyByConfigName($name);

          $configs[$key] = $value;
        }
      }
    }

    return $configs;
  }

  private function toConfigKeyByConfigName($configName)
  {
    $configKey = '';

    $strs = preg_split("/[\s]+/", $configName, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($strs as $str)
    {
      //Garbled measures
      // specify the ASCII configuration name because it is all alphabetic
      $str = mb_convert_encoding($str, 'ASCII');
      $configKey .= strtolower($str).'_';
    }

    $configKey = substr($configKey, 0, -1);

    return $configKey;
  }

  private function isConfigLines(array $lines)
  {
    $configCount = count($this->getConfigNames());

    //add end and the beginning of the comment block
    if (count($lines) !== $configCount + 2)
    {
      return false;
    }

    $configLines = array();
    //ignore the comment block
    for ($i = 1; $i < count($lines) - 1; $i++)
    {
      $configLines[] = $lines[$i];
    }

    //It is an error If the desired item is not aligned
    foreach ($this->getConfigNames() as $name)
    {
      $existsConfig = false;

      foreach ($configLines as $line)
      {
        $configNameFiled = $name.':';

        if (strpos($line, $configNameFiled) !== false)
        {
          $existsConfig = true;
        }
      }

      if (!$existsConfig) {
        return false;
      }
    }

    return true;
  }
}