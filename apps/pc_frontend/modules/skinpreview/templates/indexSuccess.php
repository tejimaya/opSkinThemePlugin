<?php if ($isExistsTheme): ?>
  <p><?php echo __('preview the "%value%" themes.', array('%value%' => $themeName)) ?></p>
<?php elseif ($emptyThemeName): ?>
  <p><?php echo__('Parameters of the theme name is empty.') ?></p>
<?php else: ?>
  <p><?php echo __('There is no "%value%" theme.', array('%value%' => $themeName)) ?></p>
<?php endif; ?>
