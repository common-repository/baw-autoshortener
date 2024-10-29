    <table class="bawas form-table">
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Short url', 'baw_as' ); ?></h3></th>
        <td>
        <input tabindex="1" type="text" name="baw-as_url" size="40" value="<?php echo BAWAS_DEFURL; ?>" /><br /><?php _e( 'Example <em>http://exte.rn</em> in place of <em>http://www.mywebsite.com</em>.', 'baw_as' ); ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
        echo '<div class="bawashelper">';
        _e( 'You can even set another external url you own like <em>http://exte.rn</em>.<br />', 'baw_as' );
        _e( 'In this case you have to add this line into your "exte.rn" .htaccess file :<br />', 'baw_as' );
        echo '<code>&lt;IfModule mod_rewrite.c&gt;<br />RewriteEngine On<br />RewriteBase /<br />RewriteRule ^(.*)$ ' . home_url() . '/$1 [L]<br />&lt;/IfModule&gt;</code>';
        echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Generate keywords for posts', 'baw_as' ); ?></h3></th>
        <td>
        <label class="bawasnewcb"><input class="bawasdnone" type="checkbox" name="baw-as_autokeyword"<?php checked( get_option( 'baw-as_autokeyword' ), 'on' ); ?> /><?php if ( get_option( 'baw-as_autokeyword' ) == 'on' ){echo '<a tabindex="3" class="bawasnewcb button-primary">' . __( 'Yes', 'baw_as' ) . '</a>';}else{echo '<a tabindex="3" class="bawasnewcb button">' . __( 'No', 'baw_as' ) . '</a>';}?></label> <?php _e( 'Do you want to auto-generate a keyword for every new published post/page ? ', 'baw_as' ); ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'If "yes", when a post/page will be published for the first time, a automatic generated keyword will be associated.', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Use keywords in links', 'baw_as' ); ?></h3></th>
        <td>
        <label class="bawasnewcb"><input class="bawasdnone" type="checkbox" name="baw-as_usekeywordsaslink"<?php checked( get_option( 'baw-as_usekeywordsaslink' ), 'on' ); ?> /><?php if ( get_option( 'baw-as_usekeywordsaslink' ) == 'on' ){echo '<a tabindex="3" class="bawasnewcb button-primary">' . __( 'Yes', 'baw_as' ) . '</a>';}else{echo '<a tabindex="3" class="bawasnewcb button">' . __( 'No', 'baw_as' ) . '</a>';}?></label> <?php _e( 'Do you want to replace numbers by keywords in Permalinks ? ', 'baw_as' ); ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'Little example with the post id "12345" with associated keyword "hello" :<br />', 'baw_as' );
         _e( 'If "no" is selected : http://mywebsite.com/12345<br />', 'baw_as' );
         _e( 'If "yes" is selected : http://mywebsite.com/hello<br />', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Stats in dashboard', 'baw_as' ); ?></h3></th>
        <td>
        <label><input type="text" tabindex="4" size="4" maxlength="2" name="baw-as_howmanystats" value="<?php echo get_option( 'baw-as_howmanystats' ); ?>" /> <?php _e( 'How many stats do you want to see in your dashboard ?', 'baw_as' ); ?></label>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'Set here how many top clic links and last created link you want to see when you are in the <a href="/wp-admin/index.php">dashboard</a>.', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
    </table>
