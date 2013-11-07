<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
include(dirname(__FILE__).'/../../bootstrap/database.php');

$t = new lime_test(1, new lime_output_color());
$t->is(opThemeConfig::save('autumn'), 'theme is autumn.');

