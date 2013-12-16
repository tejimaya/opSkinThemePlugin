<?php slot('submenu') ?>
<?php include_partial('plugin/submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Setting of Skin Theme') ?></h2>

<?php if ((isset($invalidDirNames) && count($invalidDirNames) > 0) || (isset($invalidMainCss) && count($invalidMainCss) > 0) || !$existsUsedTheme): ?>
  <h3><?php echo __('Errors of Skin Theme') ?></h3>
  <?php if (isset($invalidDirNames) && count($invalidDirNames) > 0): ?>
  <p><?php echo __('directory name of the following is not recognized as a theme, because malformed.') ?></p>
  <p><?php echo __('you can use alphanumeric characters ,hyphen,underscore for the directory name of the theme.') ?></p>
  <table>
    <tr><th><?php echo __('Directory Name'); ?></th></tr>
  <?php foreach ($invalidDirNames as $invalidDirName): ?>
  <tr><td><?php echo $invalidDirName ?></td></tr>
  <?php endforeach; ?>
  </table>
  <br />
  <?php endif; ?>
  <?php if (isset($invalidMainCss) && count($invalidMainCss) > 0): ?>
    <p><?php echo __('directory name of the following is not recognized as a theme, because they has no "main.css" or "theme name" or "author".') ?></p>
    <table>
      <tr><th><?php echo __('Directory Name'); ?></th></tr>
      <?php foreach ($invalidMainCss as $invalidMainCssName): ?>
        <tr><td><?php echo $invalidMainCssName ?></td></tr>
      <?php endforeach; ?>
    </table>
    <br />
  <?php endif; ?>
  <?php if (!$existsUsedTheme): ?>
    <p><?php echo __('Does not exists %value% of used theme at public directory.', array('%value%' => $usedThemeName)) ?></p>
    <br />
  <?php endif; ?>
<?php endif; ?>

<p><?php echo __('You need only one is set to "Enable" any skins theme.') ?></p>
<?php echo $form->renderFormTag(url_for('opSkinThemePlugin/index')); ?>
<table>
<?php include_partial('themeSelectRows', array('form' => $form)); ?>
<tr>
<td colspan="7">
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Configuration changes') ?>" />
</td>
</tr>
</table>
</form>

<h2><?php echo __('How to add a theme') ?></h2>

<p><?php echo __('You can add themes, please created under the "plugins / opSkinThemePlugin / web" directory.') ?></p>
<p><?php echo __('Please refer to the README for detailed explanation of the theme plug-in.') ?></p>