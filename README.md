opSkinThemePlugin
======================

## 機能概要
OpenPNEのテーマを選択したり、簡単に追加できるようにするプラグインです。

 * テーマ変更機能
 * テーマプレビュー機能
 * テーマ情報表示機能


## スクリーンショット
### 設定フォーム
![SS](http://suzuki-mar.github.com/opSkinThemePlugin/doc/img/setting.png)
### サンプルテーマ
![SS](http://p.pne.jp/d/201307081300.png) ![SS](http://p.pne.jp/d/201307081258.png)
![SS](http://p.pne.jp/d/201307081259.png)

## インストール方法
1. 以下のコマンドを実行して、プラグインをインストールしてください。
 * ./symfony opPlugin:install opSkinThemePlugin -r 1.0.0
2. 以下のコマンドを実行し、opSkinThemePluginのwebディレクトリ以下のファイルを公開ディレクトリにコピーしてください
 * ./symfony plugin:publish-assets

## プラグインの使用方法

### スキンテーマを有効にする
管理画面にログイン後、スキンプラグイン設定画面にアクセスします。(プラグイン設定 -> スキンプラグイン設定)  
  スキンプラグイン設定画面で、opSkinThemePluginを有効にします。

### 使用するテーマを選択する
スキンテーマを有効にした後に、スキンプラグイン設定画面からopSkinThemePluginの設定画面にアクセスします。  
  opSkinThemePluginの設定画面から、使用するテーマを選択してください。       
  [テーマの作成方法について](https://github.com/tejimaya/opSkinThemePlugin/wiki/%E3%83%86%E3%83%BC%E3%83%9E%E3%81%AE%E4%BD%9C%E6%88%90%E6%96%B9%E6%B3%95)


## 更新履歴
### 1.0.0
* テーマの追加
* 正式リリース

### 1.0.0 alpha
* ceruleanを中心に大幅なデザインの変更(bootstrap v2.3.2に対応)
* タイムライン部分のテーマが変更されない場合は、opTimelinePluginの更新をお願いします

### 0.9.4 alpha
* プレビューのアクセス方法の修正

### 0.9.3 alpha
* GitHub Pagesで使用するファイルを削除した

### 0.9.2 alpha
* ドキュメントのリンクをGitHub Pagesのリポジトリで管理するようにした

### 0.9.1 alpha
* OpenPNEの標準テーマを作成した
* 今までのOpenPNEのHTMLとBootstrapに対応したHTMLを共存できるようにした

### 0.9 alpha
* テーマを選択して、スキンを変更することができるようにした
* 簡易テーマプレビュー機能の追加
* サンプルテーマの作成 (cerulean, superhero, united)



## 要望・フィードバック
要望・フィードバックは #opSkinThemePlugin のハッシュタグをつけてつぶやいてください。             
  GitHubのアカウントを持っている人は [issues](https://github.com/tejimaya/opSkinThemePlugin/issues)にチケットを作成してください。
