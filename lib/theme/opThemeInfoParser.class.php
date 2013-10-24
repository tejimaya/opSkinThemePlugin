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
 * @subpackage opSkinThemePlugin
 * @author suzuki_mar <supasu145@gmail.com>
 * @author Kaoru Nishizoe <nishizoe@tejimaya.com>
 */
class opThemeInfoParser
{
  /**
   * @var opThemeAssetSearcher
   */
  private $searcher;

  public function __construct()
  {
    $this->searcher = new opThemeAssetSearcher();
  }
 
  public function parseInfoFileByThemeName($themeName)
  {
    $file_headers = array(
      'theme_name'  => 'Theme Name',
      'theme_uri'   => 'Theme URI',
      'description' => 'Description',
      'author'      => 'Author',
      'author_uri'  => 'Author URI',
      'version'     => 'Version',
    );
    $infoPath = $this->searcher->getThemePath().'/'.$themeName.'/css/main.css';
    $headerData = $this->get_file_data($infoPath, $file_headers);

    return $headerData;
  }

  /**
   * Retrieve metadata from a file.
   *
   * Searches for metadata in the first 8kiB of a file, such as a plugin or theme.
   * Each piece of metadata must be on its own line. Fields can not span multiple
   * lines, the value will get cut at the end of the first line.
   *
   * If the file data is not within that first 8kiB, then the author should correct
   * their plugin file and move the data headers to the top.
   *
   * @see http://codex.wordpress.org/File_Header
   * @see https://github.com/WordPress/WordPress/blob/master/wp-includes/functions.php#L3675
   *
   * @since 2.9.0
   * @param string $file Path to the file
   * @param array $default_headers List of headers, in the format array('HeaderKey' => 'Header Name')
   * @return array $all_headers array
   */
  private function get_file_data( $file, $default_headers)
  {
    // We don't need to write to the file, so just open for reading.
    $fp = fopen( $file, 'r' );

    // Pull only the first 8kiB of the file in.
    $file_data = fread( $fp, 8192 );

    // PHP will close file handle, but we are good citizens.
    fclose( $fp );

    // Make sure we catch CR-only line endings.
    $file_data = str_replace( "\r", "\n", $file_data );

    $all_headers = $default_headers;

    foreach ( $all_headers as $field => $regex )
    {
      if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
      {
        $all_headers[ $field ] = htmlspecialchars($this->_cleanup_header_comment( $match[1] ));
      }
      else
      {
        $all_headers[ $field ] = '';
      }
    }

    return $all_headers;
  }

  /**
   * Strip close comment and close php tags from file headers used by WP.
   * See http://core.trac.wordpress.org/ticket/8497
   *
   * @since 2.8.0
   *
   * @see https://github.com/WordPress/WordPress/blob/master/wp-includes/functions.php#L3609
   *
   * @param string $str
   * @return string
   */
  private function _cleanup_header_comment($str)
  {
    return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));
  }
}