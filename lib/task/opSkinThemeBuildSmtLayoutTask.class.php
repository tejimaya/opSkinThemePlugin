<?php

class opSkinThemeBuildSmtLayoutTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace = 'opSkinThemePlugin';
    $this->name      = 'build-smt-layout';

    $this->briefDescription = 'スマートフォン用のレイアウトファイルを生成します';
    $this->detailedDescription = <<<EOF
スマートフォン用の CSS ファイルが二重に読み込まれるのを避けるため、
OpenPNE 本体側のレイアウトテンプレートの以下の記述を削除したファイルを生成します。

  <?php op_smt_use_stylesheet('...') ?>

apps/pc_frontend/templates/smtLayout*.php
↓
plugins/opSkinThemePlugin/apps/pc_frontend/templates/smtLayout*.php

バージョンアップ、カスタマイズ等により、OpenPNE 本体側のレイアウトテンプレートを
変更した際には再度このタスクを実行してください。
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $files = sfFinder::type('file')
      ->name('smtLayout*.php')
      ->maxdepth(1)
      ->in(sfConfig::get('sf_root_dir').'/apps/pc_frontend/templates');

    $targetDir = sfConfig::get('sf_root_dir').'/plugins/opSkinThemePlugin/apps/pc_frontend/templates';

    foreach ($files as $file)
    {
      $targetFile = $targetDir.'/'.basename($file);

      $contents = file_get_contents($file);
      $contents = preg_replace('/^\<\?php op_smt_use_stylesheet\(.* \?\>$/m', '', $contents);

      $this->logSection('write', $targetFile);
      file_put_contents($targetFile, $contents);
    }
  }
}
