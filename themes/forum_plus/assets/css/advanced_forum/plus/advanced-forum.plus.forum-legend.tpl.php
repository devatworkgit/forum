<?php

/**
 * @file
 * Theme implementation to show forum legend.
 *
 */
?>

<div class="forum-list-icon-legend clearfix">

  <div id="forum-list-legend-header"><?php print t("Information"); ?></div>

  <div id="forum-list-legend-sub-header" class="forum-list-legend-sub-header">
    <?php print t('Icon Legend'); ?>
  </div>

  <div class="forum-list-legend-item">
    <span class="forum-list-icon-wrapper">
      <span class="forum-list-icon-new-posts">
      </span>
    </span>

    <span class="forum-list-text-legend-new-posts">
      <?php print t('New posts'); ?>
    </span>
  </div>

  <div class="forum-list-legend-item">
    <span class="forum-list-icon-wrapper">
      <span class="forum-list-icon-default">
      </span>
    </span>

    <span class="forum-list-text-legend-default">
      <?php print t('No new posts'); ?>
    </span>
  </div>
</div>
