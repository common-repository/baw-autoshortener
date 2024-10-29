    <table class="bawas form-table">
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Bookmarklet', 'baw_as' ); ?></h3></th>
        <td>
          <ul>
      			<li><strong><?php _e( 'Auto generated keyword bookmarklet', 'baw_as' ); ?></strong><br />
        			<em><?php _e( 'Drag and drop this button into your webbrowser toolbar', 'baw_as' ); ?></em><br />
              <a tabindex="6" class="button-primary notme" onclick="alert('<?php _e( 'Drag it into your toolbar !', 'baw_as' ); ?>');return false;" class="bookmarklet" href="javascript:var%20d=document,w=window,enc=encodeURIComponent,f='<?php echo site_url(); ?>',l=d.location,p='?u='+enc(l.href),n='&amp;n=<?php echo get_option( 'baw-as_bookmarklet_nonce'); ?>',u=f+p+n+'&amp;bawas_bookmarklet';try%7Bthrow('bawas' );%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0);">BAW AutoShort</a>
      			</li>

      			<li><strong><?php _e( 'Custom keyword bookmarklet', 'baw_as' ); ?></strong><br />
        			<em><?php _e( 'Drag and drop this button into your webbrowser toolbar', 'baw_as' ); ?></em><br />
              <a tabindex="7" class="button-primary notme" onclick="alert('<?php _e( 'Drag it into your toolbar !', 'baw_as' ); ?>');return false;" class="bookmarklet" href="javascript:var%20d=document,w=window,enc=encodeURIComponent,f='<?php echo site_url(); ?>',l=prompt(%22<?php _e( 'Confirm url', 'baw_as' ); ?>%22,d.location),k=prompt(%22<?php _e( 'Custom%20keyword', 'baw_as' ); ?>%22),k2=(k?'&amp;k='+k:%22%22),p='?u='+enc(l)+k2,n='&amp;n=<?php echo get_option( 'baw-as_bookmarklet_nonce'); ?>',u=f+p+n+'&amp;bawas_bookmarklet';try%7Bthrow('bawas');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0)">BAW CustomShort</a>
      			</li>
      		</ul>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'This buttons have to be drag/drop in your webbrowser toolbar, do not clic it, it\'s just useless.<br />', 'baw_as' );
         _e( 'Then, when you are on a website, reading a post/page, you can add it to YOUR short urls. Example :<br />', 'baw_as' );
         _e( 'I\'m on <em>http://secu.boiteaweb.fr/mylab/</em> and i want to share via my website.<br />', 'baw_as' );
         _e( '1) I can click on "BAW AutoShort" : An auto generated keyword will be used to share. Result : <em>http://mywebsite.com/abc</em><br />', 'baw_as' );
         _e( '2) I can click on "BAW CustomShort" : A popup will ask me the desired keyword and it will be used to share. Result : <em>http://mywebsite.com/mykeyword</em><br />', 'baw_as' );
         _e( 'Remember, when you change the bookmarklet private key, you have to drag/drop again this buttons (delete the older before).', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Auto generated keyword', 'baw_as' ); ?></h3></th>
        <td>
        <label class="bawasnewcb"><input class="bawasdnone" type="checkbox" name="baw-as_usegeneratednameforbookmarklet"<?php checked( get_option( 'baw-as_usegeneratednameforbookmarklet' ), 'on' ); ?> /><?php if ( get_option( 'baw-as_usegeneratednameforbookmarklet' ) == 'on' ){echo '<a tabindex="8" class="bawasnewcb button-primary">'.__('Yes', 'baw_as' ).'</a>';}else{echo '<a tabindex="8" class="bawasnewcb button">'.__('No', 'baw_as' ).'</a>';}?></label> <?php _e("For the bookmarklet, if the desired keyword already exists, it will be replaced by the next auto generated keyword.", 'baw_as' ); ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'If "yes", when you try to share an external URL via bookmarlet, if the keyword already exists, it will be replaced by the next auto generated keyword.<br />', 'baw_as' );
         _e( 'If "no", if the keyword already exists, the URL will not be shared and an error will be shown.<br />', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
    </table>