<?php

/**
 * @file
 * Theme implementation to show forum legend.
 *
 */
?>

<div class="forum-topic-legend clearfix">

  <div id="forum-list-legend-header"><?php print t("Information"); ?></div>

  <div id="forum-list-legend-sub-header" class="forum-list-legend-sub-header">
    <?php print t('Icon Legend'); ?>
  </div>

  <div class="topic-icon-new"><?php print t('New posts'); ?></div>
  <div class="topic-icon-default"><?php print t('No new posts'); ?></div>
  <div class="topic-icon-hot-new"><?php print t('Hot topic with new posts'); ?></div>
  <div class="topic-icon-hot"><?php print t('Hot topic without new posts'); ?></div>
  <div class="topic-icon-sticky"><?php print t('Sticky topic'); ?></div>
  <div class="topic-icon-closed"><?php print t('Locked topic'); ?></div>
</div>


