<!-- Ask if user wants to end story or keep it going -->

<div id="endpoint">
  <input class="endtarget" type="checkbox" name="endpoint" id="confirm-continue" <?php echo ($select_chapter['endpoint'] == 1 ? 'checked' : '');?> />
  <label for="confirm-continue">End the story here.</label>
</div><!-- endpoint -->

<div id="oldendpoint">
  <?php
    if (empty($select_chapter['endpoint'])){
      // box is checked  ?>
        <input class="endtarget" type="checkbox" name="endpoint" value="0" checked />
      <?php
    } else {
      if($select_chapter['endpoint'] == 0){
        // box is checked  ?>
        <input class="endtarget" type="checkbox" name="endpoint" value="0" checked />
      <?php } else { ?>
        <?php
          //box is not checked
          $options_display = "style='display: none'";
        ?>
        <input class="endtarget" type="checkbox" name="endpoint" value="1" />
      <?php }; ?>
    <?php }; ?>
  <label for="endpoint">Keep the story going?</label>
</div><!-- endpoint -->