    <table class="bawas form-table">
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Bookmarklet secret key', 'baw_as' ); ?></h3></th>
        <td>
        <input type="text" tabindex="1" name="baw-as_bookmarklet_nonce" value="<?php echo get_option( 'baw-as_bookmarklet_nonce'); ?>" /><br />
        <em><?php _e( 'Security key for bookmarklet creation. Do not change it until you really need to.', 'baw_as' ); ?></em>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'This key is used to secure your bookmarklet links.<br />', 'baw_as' );
         _e( '1) If you need to change it (for security reason), leave it blank (recommanded to generate a new great random value).<br />', 'baw_as' );
         _e( '2) Reload the page.<br />', 'baw_as' );
         _e( '3) Update your bookmarklet buttons into your webbrowser toolbar.<br />', 'baw_as' );
         _e( '4) The key will be included to the new bookmarklet link.', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Fordidden keywords', 'baw_as' ); ?></h3></th>
        <td>
        <input type="text" tabindex="2" size="60" name="baw-as_fordibben_keywords" value="<?php echo get_option( 'baw-as_fordibben_keywords'); ?>" /> <em><?php _e( 'Comma separated.', 'baw_as' ); ?></em><br />
        <em><?php _e( 'These keywords can be selected or auto generated.', 'baw_as' ); ?></em>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'It can be disturbing for your visitors to see some crappy words in your short links. Here you can type the forbidden words, they never be auto generated and you cannot use them to create a keyword.<br />', 'baw_as' );
         _e( 'BUT, if the keyword "hello" exists and you add it to the forbidden list, it\'s to late, of course...', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Keep query vars', 'baw_as' ); ?></h3></th>
        <td>
        <label class="bawasnewcb"><input class="bawasdnone" type="checkbox" name="baw-as_keepqueryvars" <?php checked( get_option( 'baw-as_keepqueryvars'), 'on' ); ?> /><?php if ( get_option( 'baw-as_keepqueryvars' ) == 'on' ) {echo '<a tabindex="3" class="bawasnewcb button-primary">' . __( 'Yes', 'baw_as' ) . '</a>';}else{echo '<a tabindex="3" class="bawasnewcb button">' . __( 'No', 'baw_as' ) . '</a>';}?></label> <?php _e("Do you want to keep the query vars in your links ? ", 'baw_as'); ?><br />
        <em><?php _e( 'Ex. : http://mywebsite.com/goo/boiteaweb.php?var=julio forwards on http://www.google.com/boiteaweb.php?var=julio.<br />The anchor, like #q=mysearch will always be kept, even on "No" selection.', 'baw_as' ); ?></em>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'You can also do this :<br />', 'baw_as' );
         _e( 'http://mywebsite.com/goo/directory/anotherone/page.php?var=value#anchor<br />', 'baw_as' );
         _e( 'The final URL will be :<br />', 'baw_as' );
         _e( 'http://www.google.com/directory/anotherone/page.php?var=value#anchor', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Referer detail', 'baw_as' ); ?></h3></th>
        <td>
          <ul>
            <li><span class="bawastitle"><label class="bawasnewcb"><input type="radio" class="bawasdnone" name="baw-as_reflevel" value="low"<?php checked( get_option( 'baw-as_reflevel' ), 'low' ); ?> /> <a tabindex="3" class="bawasnewcb <?php if ( get_option( 'baw-as_reflevel' ) == 'low' ) {echo 'button-primary';}else{echo 'button';} ?>" id="radio" name="baw-as_reflevel"><?php _e( 'Low', 'baw_as' ); ?></a></label></span> &rarr; <em><?php _e('http://www.website.com', 'baw_as'); ?></em></li>
            <li><span class="bawastitle"><label class="bawasnewcb"><input type="radio" class="bawasdnone" name="baw-as_reflevel" value="medium"<?php checked( get_option( 'baw-as_reflevel' ), 'medium' );?> /> <a tabindex="3" class="bawasnewcb <?php if ( get_option( 'baw-as_reflevel' ) == 'medium' ) {echo 'button-primary';}else{echo 'button';} ?>" id="radio" name="baw-as_reflevel"><?php _e( 'Medium', 'baw_as' ); ?></a></label></span> &rarr; <em><?php _e('http://www.website.com/directory/page.php', 'baw_as'); ?></em></li>
            <li><span class="bawastitle"><label class="bawasnewcb"><input type="radio" class="bawasdnone" name="baw-as_reflevel" value="high"<?php checked( get_option( 'baw-as_reflevel' ), 'high' );?> /> <a tabindex="3" class="bawasnewcb <?php if ( get_option( 'baw-as_reflevel' ) == 'high' ) {echo 'button-primary';}else{echo 'button';} ?>" id="radio" name="baw-as_reflevel"><?php _e( 'High', 'baw_as' ); ?></a></label></span> &rarr; <em><?php _e('http://www.website.com/directory/page.php?args1=true&arg2=string', 'baw_as'); ?></em></li>
          </ul>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'The referer is something that drives a visitor to your website.<br />', 'baw_as' );
         _e( 'Sometimes there is no referer set, this means that the link have been hit in the address bar.<br />', 'baw_as' );
         _e( 'This setting is the referer log level. "Low" or "Medium" is recommanded.', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Create share domains', 'baw_as' ); ?></h3></th>
        <td>
        <textarea tabindex="4" cols="80" rows="6" name="baw-as_sharedomains"><?php echo get_option( 'baw-as_sharedomains'); ?></textarea><br />
        <em><?php _e( 'One per line. Use {%URL%} for parsing method.', 'baw_as' ); ?> Ex: <code>http://jcray.com/share/?url=<strong>{%URL%}</strong></code></em>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'You can add you own sharer here ! Example :<br />', 'baw_as' );
         echo '<code>http://jcray.com/share/?url={%URL%}</code>.' . __( '(This website exists ! Visit it !)', 'baw_as' );
         _e( '<br />This links will be shown for every short links in info URL (those finishing with a "+"), and on every external links in "Special Redirections", and again in every post/page. ', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Check duplicates', 'baw_as' ); ?></h3></th>
        <td>
        <label class="bawasnewcb"><input class="bawasdnone" type="checkbox" name="baw-as_checkduplicates" <?php checked( get_option( 'baw-as_checkduplicates'), 'on' ); ?> /><?php if ( get_option( 'baw-as_checkduplicates' ) == 'on' ) {echo '<a tabindex="3" class="bawasnewcb button-primary">' . __( 'Yes', 'baw_as' ) . '</a>';}else{echo '<a tabindex="3" class="bawasnewcb button">' . __( 'No', 'baw_as' ) . '</a>';}?></label> <?php _e('Do you want to check duplicate slug/ID ?', 'baw_as'); ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'If your permalink structure is "%post_name%" and if you create a post named "login" and if you already have a keyword "login" used in a short url, this post won\'t be reacheable. So activate this option will warn you about duplicate slugs/IDs. This may create a huge traffic (5 UNION requests)', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
    </table>