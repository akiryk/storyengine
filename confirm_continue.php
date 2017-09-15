<?php
  /**
   * User can decide if current chapter should end a thread or not
   * That is, will there be two more options for continuing the story.
   * First, see if the box is already checked.
   * If it is, set $options_display to display: none so that options
   * will be hidden by default.
   */
?>

<div id="endpoint">
  <input class="endtarget" type="checkbox" name="endpoint" id="confirm-continue" <?php echo ($select_chapter['endpoint'] == 1 ? 'value="1" checked' : 'value="0"');?> />
  <label for="confirm-continue">End the story here.</label>
</div><!-- endpoint -->

