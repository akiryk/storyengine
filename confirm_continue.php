<?php
  /**
   * User can decide if current chapter should end a thread or not
   * That is, will there be two more options for continuing the story.
   * First, see if the box is already checked.
   * If it is, set $options_display to display: none so that options
   * will be hidden by default.
   */
?>

<div id="endpoint-wrapper">
  <input type="checkbox" id="endpoint" name="endpoint" value="true"
    <?php $endpoint == 1 ? print "checked" : print ""; ?>
  />
  <label for="endpoint">End the story here.</label>
</div><!-- endpoint -->
