<table class="bawas form-table">
    <tr valign="top">
    <th scope="row"><h3><?php _e( 'Advanced helper', 'baw_as' ); ?></h3></th>
    <td>
      <label class="bawasnewcb"><input type="checkbox" class="bawasdnone" name="baw-as_usehelp" id="baw-as_usehelp" <?php checked( get_option( 'baw-as_usehelp' ), 'on' ); ?> /><?php if (get_option( 'baw-as_usehelp' ) == 'on' ){echo '<a tabindex="2" class="bawasnewcb button-primary">'.__('Yes', 'baw_as' ).'</a>';}else{echo '<a tabindex="2" class="bawasnewcb button">'.__('No', 'baw_as' ).'</a>';}?></label>
      <?php _e( 'If "Yes", some help box will appears below the settings fields.', 'baw_as' );
       echo '<div class="bawashelper">';
       _e( 'This is the kind of help box ;)', 'baw_as' );
       echo '</div>';
      ?>
    </td>
    </tr>
    <tr valign="top">
    <th scope="row"><h3><?php _e( 'Help me !', 'baw_as' ); ?></h3></th>
    <td>
     <strong><?php _e( 'This plugin is free, neither you can make a donation, even 1&#36;/1&euro; is good !', 'baw_as' ); ?></strong>
     <br />
     <?php _e( 'I\'m gonna make a donation, soon, i promise ! ', 'baw_as' ); ?><label class="bawasnewcb"><input class="bawasdnone" type="checkbox" name="baw-as_donate" <?php checked( get_option( 'baw-as_donate' ), 'on' ); ?>/><?php if (get_option( 'baw-as_donate' ) == 'on' ){echo '<a class="bawasnewcb button-primary">'.__('Yes', 'baw_as' ).'</a>';}else{echo '<a class="bawasnewcb button">'.__('No', 'baw_as' ).'</a>';}?></label><?php if (get_option( 'baw-as_donate' ) == 'on' ){_e( '<strong>Thank you !!</strong>', 'baw_as' );} ?>
     <br /><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KJGT942XKWJ6W" rel="nofollow" target="_blank"><img src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" /></a></td>
    </tr>
    <tr valign="top">
    <th scope="row"><h3><?php _e( 'Help me again !', 'baw_as' ); ?></h3></th>
    <td>
      <label class="bawasnewcb"><input type="checkbox" class="bawasdnone" name="baw-as_showadslink" id="baw-as_showadslink" <?php checked( get_option( 'baw-as_showadslink' ), 'on' ); ?> /><?php if (get_option( 'baw-as_showadslink' ) == 'on' ){echo '<a tabindex="3" class="bawasnewcb button-primary">'.__('Yes', 'baw_as' ).'</a>';}else{echo '<a tabindex="3" class="bawasnewcb button">'.__('No', 'baw_as' ).'</a>';}?></label>
      <?php _e( 'As you know this plugin is free, can you show my plugin link in your blog footer ?', 'baw_as' ); ?>
        <?php if (get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'The same link as this one in admin area will be displayed in your website footer.', 'baw_as' );
         echo '</div>';
        } ?>
    </td>
    </tr>
    <tr valign="top">
    <th scope="row"><h3><?php _e( 'Need more help ?', 'baw_as' ); ?></h3></th>
    <td>
     <p><a href="http://code.google.com/p/autoshortener/" class="button" target="_blank"><?php _e( 'Visit', 'baw_as' ); ?> Support (code.google.com)</a></p>
     <p><a href="http://www.autoshort.com/faq<?php _e( '-en', 'baw_as' ); ?>" class="button" target="_blank"><?php _e( 'Visit', 'baw_as' ); ?> AutoShort.com/faq<?php _e( '-en', 'baw_as' ); ?></a></p>
    </td>
    </tr>
</table>