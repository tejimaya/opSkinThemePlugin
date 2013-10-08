<h2><?php echo __('Setting of Skin Theme') ?></h2>

<?php if ($isExistsErrorTheme): ?>
  <h3><?php echo __('Errors of Skin Theme') ?></h3>

  <?php if (!$unRegisterUseTheme && !$existsUseTheme): ?>
    <p><?php echo __('Does not exists %value% of used theme at public directory.', array('%value%' => $useTheme)) ?></p>
    <?php if (isset($notInfoThemeList)): ?>
      <br />
    <?php endif; ?>
  <?php endif; ?>

  <?php if (isset($notInfoThemeList)): ?>
    <p><?php echo __('Information of the following themes is not set.') ?><br />
    <?php foreach ($notInfoThemeList as $theme): ?>
      <?php echo $theme.__('Theme') ?> <br />
    <?php endforeach; ?>
    </p>
  <?php endif; ?>

<br />
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
<p><?php echo __('After you add a theme, run the ". / Symfony publish-assets", please copy the public directory theme directory.') ?></p>
<p><?php echo __('Please refer to the README for detailed explanation of the theme plug-in.') ?></p>