    <table class="bawas form-table">
      <tr valign="top">
        <th scope="row"><h3><?php _e( 'Use "last" redirection', 'baw_as' ); ?></h3></th>
        <td>
        <label><input type="text" tabindex="1" name="baw-as_uselast" id="baw-as_uselast" value="<?php echo get_option( 'baw-as_uselast'); ?>" /></label> <em><?php _e( 'Leave it blank if you do not want.', 'baw_as' ); ?></em>
        <br />
        <?php _e( 'Type here a special keyword (like "last") to automatically redirect users on your last published post.', 'baw_as' ); ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'You can use a special keyword to redirect your visitors to the last post/page/other_stuff you published.<br />', 'baw_as' );
         _e( 'Take care, if you change this keyword, the old one won\'t work anymore.', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Post types for "last" redirection', 'baw_as' ); ?></h3></th>
        <td style="line-height:3em">
        <?php
        $post_types = get_post_types( '', 'names' );
        $my_post_types = (array)get_option( 'baw-as_posttypes' );
        $forbidden_pt = array( 'revision', 'nav_menu_item', BAWAS_POST_TYPE );
        foreach ( $post_types as $post_type ) {
          if ( !in_array( $post_type, $forbidden_pt ) )
          {
            echo '<label class="bawasnewcb">';
            echo '<input type="checkbox" class="bawasdnone" name="baw-as_posttypes[]" value="' . $post_type . '"';
            if ( in_array( $post_type, $my_post_types ) ) { echo ' checked="checked"'; }
            echo '/><a tabindex="3" class="bawasnewcb button';
            if ( in_array( $post_type, $my_post_types ) ) { echo '-primary'; }
            echo '">' . ucwords( $post_type ) . '</a>';
            echo '</label> ';
          }
        }
        if ( get_option( 'baw-as_uselast') != '' ) {
         if ( get_option( 'baw-as_replacepermalink' ) == 'on' ) {
          remove_filter( 'post_link', 'bawas_get_permalink' );
         }
         $lp = get_permalink( bawas_posts( true ) );
         if ( get_option( 'baw-as_replacepermalink' ) == 'on' ) {
          add_filter( 'post_link', 'bawas_get_permalink' );
         }
          echo '<br /><br />' . __( 'Last published ', 'baw_as' ) . implode( __(' or ', 'baw_as' ), $my_post_types ) . ' : <a href="' . $lp . '">' . $lp . '</a>';
         } ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) {
         echo '<div class="bawashelper">';
         _e( 'Here you can check the allowed post type included for the "last" redirection.<br />', 'baw_as' );
         _e( 'Even your own post types are available !', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><h3><?php _e( 'Special redirection pagination', 'baw_as' ); ?></h3></th>
        <td>
        <label><input type="text" tabindex="5" size="4" maxlength="2" name="baw-as_howmanypage" value="<?php echo get_option( 'baw-as_howmanypage' ); ?>" /> <?php _e( 'How many URLs by page do you want to see in the special redirections tab ?', 'baw_as' ); ?></label>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ){
         echo '<div class="bawashelper">';
         _e( 'Set here how many redirection by page you want to see when you are in the Special Redirections tab.<br />', 'baw_as' );
         _e( 'Also, this number will be used to load more redirection at once.', 'baw_as' );
         echo '</div>';
        } ?>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row" style="width: 135px !important;"><h3><?php _e( 'Special redirections', 'baw_as' ); ?></h3>
        <?php
        echo '<a href="javascript:void(0)" onclick="jQuery(\'div#sorts\').slideToggle()" class="bawasnewcb button"><img class="bawasinside" src="'.BAWAS_IMAGES_URL.'expand.png" alt="expand" title="'.__('Sorts', 'baw_as' ).'" /> '.__('Sorts', 'baw_as' ).'</a>';
        echo '<br /><br />';
        echo '<a href="javascript:void(0)" onclick="jQuery(\'div#filters\').slideToggle()" class="bawasnewcb button"><img class="bawasinside" src="'.BAWAS_IMAGES_URL.'expand.png" alt="expand" title="'.__('Filters', 'baw_as' ).'" /> '.__('Filters', 'baw_as' ).'</a>';
        ?>
        </h3></th>
        <td id="lists">
        <div class="bawastools" id="sorts">
        <?php _e('Sort by', 'baw_as'); ?> :
        <label><input type="radio" name="baw-as_sortbyorder" value="asc" class="bawasdnone" <?php checked( get_option( 'baw-as_sortbyorder' ), 'asc' ); ?>/><a class="bawasnewcb button<?php if (get_option( 'baw-as_sortbyorder' ) == 'asc' ) {echo '-primary';} ?>" id="radio" name="baw-as_sortbyorder"><?php _e( 'ASC', 'baw_as' ); ?></a></label>
        <label><input type="radio" name="baw-as_sortbyorder" value="desc" class="bawasdnone" <?php checked( get_option( 'baw-as_sortbyorder' ), 'desc' ); ?>/><a class="bawasnewcb button<?php if (get_option( 'baw-as_sortbyorder' ) == 'desc' ) {echo '-primary';} ?>" name="baw-as_sortbyorder" id="radio" name="baw-as_sortbyorder"><?php _e( 'DESC', 'baw_as' ); ?></a></label>
        &nbsp;&nbsp;&rarr;&nbsp;&nbsp;
        <label><input type="radio" name="baw-as_sortby" value="date" class="bawasdnone" <?php checked( get_option( 'baw-as_sortby' ), 'date' ); ?>/><a class="bawasnewcb button<?php if (get_option( 'baw-as_sortby' ) == 'date' ) {echo '-primary';} ?>" id="radio" name="baw-as_sortby" onclick="bawas_sortThis('date')"><?php _e( 'Date', 'baw_as' ); ?></a></label>
        <label><input type="radio" name="baw-as_sortby" value="url" class="bawasdnone" <?php checked( get_option( 'baw-as_sortby' ), 'url' ); ?>/><a class="bawasnewcb button<?php if (get_option( 'baw-as_sortby' ) == 'url' ) {echo '-primary';} ?>" id="radio" name="baw-as_sortby" onclick="bawas_sortThis('url')"><?php _e( 'URL', 'baw_as' ); ?></a></label>
        <label><input type="radio" name="baw-as_sortby" value="keyword" class="bawasdnone" <?php checked( get_option( 'baw-as_sortby' ), 'keyword' ); ?>/><a class="bawasnewcb button<?php if (get_option( 'baw-as_sortby' ) == 'keyword' ) {echo '-primary';} ?>" id="radio" name="baw-as_sortby" onclick="bawas_sortThis('keyword')"><?php _e( 'Keywords', 'baw_as' ); ?></a></label>
        <label><input type="radio" name="baw-as_sortby" value="clic" class="bawasdnone" <?php checked( get_option( 'baw-as_sortby' ), 'clic' ); ?>/><a class="bawasnewcb button<?php if (get_option( 'baw-as_sortby' ) == 'clic' ) {echo '-primary';} ?>" id="radio" name="baw-as_sortby" onclick="bawas_sortThis('clic')"><?php _e( 'Clics', 'baw_as' ); ?></a></label>
        </div>
        <div class="bawastools" id="filters">
        <?php _e('Filter by', 'baw_as'); ?> :
        <label><input type="radio" name="baw-as_filterby" value="as_posts" class="bawasdnone" /><a class="bawasnewcb button" id="radio" name="baw-as_filterby"><?php _e( 'URL', 'baw_as' ); ?></a></label>
        <label><input type="radio" name="baw-as_filterby" value="as_keys" class="bawasdnone" checked="checked" /><a class="bawasnewcb button-primary" id="radio" name="baw-as_filterby"><?php _e( 'Keyword', 'baw_as' ); ?></a></label>
        &nbsp;&nbsp;&rarr;&nbsp;&nbsp;
        <input type="text" class="bawaslocase" name="baw-as_filterbytxt" id="baw-as_filterbytxt" onkeyup="bawas_filterThis(this.value)"/>
        </div>
        <div class="dndcont">
        <?php
          bawas_show_stats( BAWAS_MODE_SETTINGS );
        ?>
        </div>
        <div class="bawastools bawasdblock bawastacenter">
          <span class="btnmoreredirections" rel="<?php echo get_option( 'baw-as_howmanypage'); ?>"><img class="bawasinside" src="<?php echo BAWAS_IMAGES_URL; ?>more.png"> <?php echo get_option( 'baw-as_howmanypage').' '.__('more redirections', 'baw_as' ); ?></span> -
          <span class="btnmoreredirections" rel="99999"><img class="bawasinside" src="<?php echo BAWAS_IMAGES_URL; ?>more.png"> <?php _e( 'All redirections', 'baw_as' ); ?> (<span id="nballredirections">...</span>)</span>
        </div>
        <a class="button" class="bawasnou" href="javascript:void(0);" id="btn_add"><img class="bawasinside" src="<?php echo BAWAS_IMAGES_URL.'add.png'; ?>" alt="add" title="<?php _e( 'Add a new rule', 'baw_as' ); ?>" /> <?php _e( 'Add a new rule', 'baw_as' ); ?></a><br />
       </td>
       </tr>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) { ?>
        <tr valign="top">
        <td colspan="2">
        <?php
         echo '<div class="bawashelper">';
         _e( 'This is a huge part of the plugin :<br />', 'baw_as' );
         _e( 'Here you can create/delete external redirection within YOUR WordPress website/blog. Statistics are included, possibility to share.<br />', 'baw_as' );
         _e( 'First things first ; Little description :<br />', 'baw_as' );
         echo '<a href="javascript:void(0);" class="button"><img alt="add" src="'.BAWAS_IMAGES_URL.'add.png" class="bawasinside"> '.__('Add a new rule', 'baw_as' ).'</a> : ';
         _e( 'This button is used to add new rules, a rule is a new external redirection.<br />', 'baw_as' );
         echo '<a class="button"><img alt="del" src="'.BAWAS_IMAGES_URL.'del.png" class="bawasinside"></a> <input type="text" size="25" /> <span class="bawasarr">&rarr;</span> <input type="text" size="15"> ';
         _e( 'Then you\'ve got 1 delete button, it\'s used to delete an existing rule. Take care, if this link is known by anyone (including search engines), this link will be broken !<br />', 'baw_as' );
         _e( 'You\'ve got also 2 text inputs. The first input must contains the URL. The second, the keyword (it can be a number <sup>(1)</sup>)<br />', 'baw_as' );
         _e( 'Near that you\'ve got 1 picture : ', 'baw_as' );
         echo '<img alt="valid" src="'.BAWAS_IMAGES_URL.'valid.png"> ; <img width="18" alt="novalid" src="'.BAWAS_IMAGES_URL.'notvalid.png">';
         _e( ' : A green one means the URL is correct (HTTP 200,301,302) and a red one an incorrect URL (HTTP 404).<br />', 'baw_as' );
         _e( 'Then the Stats button : ', 'baw_as' );
         echo '<a class="button" href="javascript:void(0)"><img alt="stat" src="'.BAWAS_IMAGES_URL.'stat.png" class="bawasinside"> X clics</a>';
         _e( ' : You can quickly see how much clic have been made on this link and ... clic it to slide down the statistics panel !<br />', 'baw_as' );
         _e( 'Following this, you\'ve got your sharer buttons, you can set them in the "Advanced settings" tab.<br />', 'baw_as' );
         echo '<br />';
         _e( 'In the stats panel you\'ve got some others informations like referers <sup>(2)</sup>, date of first and last access, post type, different link informations, a chart and a reset button.<br />', 'baw_as' );
         _e( 'The reset button ... resets the stats ! A confirmation popup will appear before the erase.<br />', 'baw_as' );
         _e( 'The "External link" is YOUR link, the one you set earlier.<br />', 'baw_as' );
         _e( 'The "Real permalink" is the FINAL url link, example, if you set up a bit.ly link, you\'ll see here the real url hidden behind bit.ly.<br />', 'baw_as' );
         _e( 'The first "Short permalink" is the post ID, the others are yours, you can set more than 1 keyword for same final URL.<br />', 'baw_as' );
         _e( 'And "Referer list" contains all the referers who drives visitors to your link.<br />', 'baw_as' );
         echo '<img src="'.BAWAS_IMAGES_URL.'miniget.gif" />' . __( 'This picture is a button, clic it to get a popup to easily copy this link.', 'baw_as' );
         _e( 'The pie chart is a little representation of this referers.<br />', 'baw_as' );
         _e( 'Below this, here\'s the pagination, you can change this in the "General settings" tab.<br />', 'baw_as' );
         echo '<br />';
         _e( 'Tips : Do not loop your posts with numbered keywords !<br />', 'baw_as' );
         _e( 'Tips : Avoid link your own post in this screen, use the real post keyword to do this (Clic on "Edit post" to undertand).<br />', 'baw_as' );
         echo '<br />';
         _e( '<sup>1</sup> : If you set up a number as keyword, you have to know that the numbered permalink of this post ID will not be available anymore !<br />', 'baw_as' );
         _e( 'Example : <em>http://mywebsite.com/123</em> is my "Hello world!" post. I set a "123" keyword for my "Good bye!" post (real ID 456). So now <em>http://mywebsite.com/123</em> is linked to the 456 post ID ! Keywords have priority on real IDs !<br />', 'baw_as' );
         _e( '<sup>2</sup> : A referer is something that drives a visitor to your website.<br />', 'baw_as' );
         echo '</div>';
        } ?>
        <?php if ( get_option( 'baw-as_usehelp' ) == 'on' ) { ?>
       </td>
     </tr>
     <?php } ?>
   </table>