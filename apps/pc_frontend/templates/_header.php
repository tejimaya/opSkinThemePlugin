<?php echo link_to($op_config['sns_name'], '@homepage', array('class' => 'brand')) ?>


<ul class="nav" id="localNav">
  <?php if(opToolkit::isSecurePage()): ?>
  <li id="notificationCenter">
    <a class="ncbutton">
      <i class="fa fa-envelope-o"></i>
      <i class="fa fa-users"></i>
      <b>@</b>
    </a>
    <div id="notificationCenterDetail" class="alert alert-block">
      <div id="notificationCenterDetailHeader">
        <?php echo __('Notification Center') ?>
      </div>
      <div id="notificationCenterLoading">
        <?php echo op_image_tag('ajax-loader.gif') ?>
      </div>
      <div id="notificationCenterError">
        <?php echo __('There is no new notification.') ?>
      </div>
    </div>
  </li>

  <script id="notificationCenterListTemplate" type="text/x-jquery-tmpl">
      <div class="{{if unread==false}}isread {{/if}}{{if category=="message" || category=="other"}}nclink {{/if}}push" data-notify-id="${id}" data-location-url="${url}" data-member-id="${member_id_from}">
        <div class="push_icon">
          <img src="${icon_url}" width="48">
        </div>
        <div class="push_content">
        {{if category=="link"}}
          {{if unread==false}}
          <?php echo __('%Friend% link request') ?>
          {{else}}
          <?php echo __('Do you accept %friend% link request?') ?>
          <div class="push_yesno">
            <button class="friend-accept">YES</button>
            <button class="friend-reject">NO</button>
            <div class="ncfriendloading"><?php echo op_image_tag('ajax-loader.gif') ?></div>
            <div class="ncfriendresultmessage"></div>
          </div>
          {{/if}}
        {{else}}
          ${body}
        {{/if}}
        </div>
      </div>
  </script>

  <script id="notificationCenterCountTemplate" type="text/x-jquery-tmpl">
    {{if message!==0}}
    <span id="nc_icon1">${message}</span>
    {{/if}}
    {{if link!==0}}
    <span id="nc_icon2">${link}</span>
    {{/if}}
    {{if other!==0}}
    <span id="nc_icon3">${other}</span>
    {{/if}}
  </script>
  <?php endif ?>

<?php
$context = sfContext::getInstance();
$module = $context->getActionStack()->getLastEntry()->getModuleName();
$localNavOptions = array(
  'is_secure' => opToolkit::isSecurePage(),
  'type'      => sfConfig::get('sf_nav_type', sfConfig::get('mod_'.$module.'_default_nav', 'default')),
  'culture'   => $context->getUser()->getCulture(),
);
if ('default' !== $localNavOptions['type'])
{
  $localNavOptions['nav_id'] = sfConfig::get('sf_nav_id', $context->getRequest()->getParameter('id'));
}
include_component('default', 'localNav', $localNavOptions);
?>
</ul>

<div id="globalNav">
<ul class="nav pull-right" >
<?php
$globalNavOptions = array(
  'type'      => opToolkit::isSecurePage() ? 'secure_global' : 'insecure_global',
  'culture'   => sfContext::getInstance()->getUser()->getCulture(),
);
include_component('default', 'globalNav', $globalNavOptions);
?>
</ul>
</div><!-- globalNav -->

<div id="topBanner">
<?php if ($sf_user->isSNSMember()): ?>
<?php echo op_banner('top_after') ?>
<?php else: ?>
<?php echo op_banner('top_before') ?>
<?php endif ?>
</div>
