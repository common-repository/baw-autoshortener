<?php
/*
Plugin Name: BAW Auto Shortener
Plugin URI: http://www.boiteaweb.fr/
Description: Permet de creer des url reduites pour son site/blog.
Version: 1.0.2
Author: Juliobox
Author URI: http://www.BoiteaWeb.fr
License: GPLv2
*/
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( 'defines.inc.php' );
require_once( 'functions.inc.php' );
require_once( 'baw_functions.inc.php' );

function bawas_l10n_init()
{
  load_plugin_textdomain( 'baw_as', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

function baw_auto_shortener( $dummy = '', $id = 0, $onlykw = false )
{
  global $post;
  $real = intval( $id ) > 0 ? $id : $post->ID;
  $kw = get_post_meta( $real, '_baw-as-lastkeyword', true ) <> '' ? get_post_meta( $real, '_baw-as-lastkeyword', true ) : get_post_meta( $real, '_baw-as-specialkeyword', true );
  if (get_option( 'baw-as_usekeywordsaslink' ) == 'on' && $kw != '' ){
    $real = $kw;
  }
  if ( !$onlykw ) {
    return trailingslashit( BAWAS_DEFURL ) . $real;
  }else{
    return $real;
  }
}

function bawas_get_shortlink( $dummy, $postID )
{
  return baw_auto_shortener( $dummy, $postID );
}

function bawas_the_shortlink()
{
  echo baw_auto_shortener();
}

function bawas_create_menu() 
{
if ( !defined( 'BAW_MENU' ) ) {
  define( 'BAW_MENU', true );
  add_menu_page( 'BoiteAWeb.fr', 'BoiteAWeb', 'manage_options', 'baw_menu', 'baw_about', plugins_url('/images/icon.png', __FILE__) );
}
  add_submenu_page( 'baw_menu', __('Auto Shortener', 'baw_as'), __('Auto Shortener', 'baw_as'), 'install_plugins', 'baw_as_config', 'bawas_page' );
}

function bawas_dashboard_widget()
{
  global $wpdb;
  $howmanystats = (int)get_option( 'baw-as_howmanystats' );
  $classs = array();  $classs[] = 'content';  $classs[] = 'discussion'; // Ne pas traduire // Do not translate
  $titles = array();  $titles[] = __( 'Top clics', 'baw_as' ) . ' (' . $howmanystats . ')'; $titles[] = __( 'Last links', 'baw_as' ) . ' (' . $howmanystats . ')';
  $res = array();
  $res[] = $wpdb->get_results( $wpdb->prepare( 'SELECT p.ID, pm.meta_value as Clics FROM ' . $wpdb->posts . ' p, ' . $wpdb->postmeta . ' pm WHERE p.ID=pm.post_id AND pm.meta_key="_baw-as-clic" AND pm.meta_value>0 ORDER BY CAST(pm.meta_value AS SIGNED) DESC, p.ID DESC LIMIT 0,%d', $howmanystats ) );
  $res[] = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT p.ID FROM ' . $wpdb->posts . ' p, ' . $wpdb->postmeta . ' pm WHERE p.ID=pm.post_id AND pm.meta_key="_baw-as-specialkeyword" GROUP BY p.ID ORDER BY p.ID DESC LIMIT 0,%d', $howmanystats ) );
  for( $i = 0 ; $i < 2 ; $i++ ){
  ?>
  	<div class="table table_<?php echo $classs[$i]; ?>">
    	<p class="sub"><?php echo $titles[$i] ?></p>
    	<table>
      	<tbody>
  <?php
if ( count( $res[$i] ) > 0 )
{
    foreach( $res[$i] as $val )
    {
      $b_permalink = esc_url_raw( get_post_meta( $val->ID, '_baw-as-302url', true ) );
      if ( $b_permalink == '') {
        $b_permalink = get_permalink( $val->ID );
      }
      $title = get_the_title( $val->ID ) != BAWAS_POST_TYPE ? get_the_title( $val->ID ) : get_post_meta( $val->ID, '_baw-as-302url', true );
      if ( strlen( $title ) > 32 ) {
        $title = substr( $title, 0, 18 ) . '...' . substr( $title, -8 );
      }
      $clics = get_post_meta( $val->ID, '_baw-as-clic', true) > 0 ? get_post_meta( $val->ID, '_baw-as-clic', true ) : 0;
    ?>
            <tr>
              <td class="first b b-posts">
                <a title="<?php printf( _n( '%s clic', '%s clics', $clics, 'baw_as' ) ); ?>"><?php echo $clics; ?></a>
              </td>
              <td class="t posts">
                <a href="<?php echo $b_permalink; ?>"><?php echo $title; ?></a>
              </td>
            </tr>
    <?php
    }
  }else{ ?>
          <tr>
            <td class="first b b-posts">
              -
            </td>
            <td class="t posts">
              <?php _e( 'Nothing, just wait ;)', 'baw_as' ); ?>
            </td>
          </tr>
<?php
  }
  ?>
      	</tbody>
      </table>
  	</div>
<?php } ?>
    <div class="versions">
    	<p>
        <?php echo '<a class="button" href="' . admin_url( 'admin.php?page=baw_as_config') . '"><img class="bawasinside" src="' . BAWAS_IMAGES_URL . 'icon.png" alt="settings" title="' . __( 'Settings', 'baw_as' ) . '" /> ' . __( 'Settings', 'baw_as' ) . '</a>'; ?>
      </p>
      <span id="wp-version-message"><?php _e( 'You are using BAW AS version', 'baw_as' ); ?> <span class="b"><?php echo BAWAS_VERSION; ?></span>.
  	 <br class="clear">
    </div>
<?php
}

function bawas_dashboard_widget_setup()
{
  wp_add_dashboard_widget( 'bawas_dashboard_widget', __( 'BAW Auto Shortener Statistics' ), 'bawas_dashboard_widget' );
}

function bawas_postid_show( $actions, $post )
{
  $kw = get_post_meta( $post->ID, '_baw-as-specialkeyword', false );
  $kws = implode( '</option><option>', $kw );
  if ( count( $kw ) > 0 ){
    $actions['edit'] = '<span class="bawasdnone"><span class="bawas">' . count( $kw ) . ':<select id="bawasselectedkeyword_' . $post->ID . '"><option>' . $kws . '</option></select><br /><input type="button" class="button" value="' . __('Get link', 'baw_as' ) . '" onclick="prompt(\'' . __( 'Here is your short link !', 'baw_as' ) . '\',\'' . trailingslashit(BAWAS_DEFURL) . '\'+jQuery(\'#bawasselectedkeyword_' . $post->ID . '\').val());"/></span></span>' . $actions['edit'];
  }else{
    $actions['edit'] = '<span class="bawasdnone"><span class="bawas">' . __( 'None', 'baw_as' ) . '</span></span>' . $actions['edit'];}
  return $actions;
}

function bawas_pageid_show( $actions, $page )
{
  $kw = get_post_meta( $page->ID, '_baw-as-specialkeyword', false );
  $kws = implode( '</option><option>', $kw );
  if ( count( $kw ) > 0 ){
    $actions['edit'] = '<span class="bawasdnone"><span class="bawas">' . count( $kw ) . ':<select id="bawasselectedkeyword_' . $post->ID . '"><option>' . $kws . '</option></select><br /><input type="button" value="' . __( 'Get link', 'baw_as' ) . '" onclick="prompt(\'' . __('Here is your short link !', 'baw_as' ).'\',\'' . trailingslashit(BAWAS_DEFURL) . '\'+jQuery(\'#bawasselectedkeyword_' . $post->ID . '\').val());"/></span></span>' . $actions['edit'];
  }else{
    $actions['edit'] = '<span class="bawasdnone"><span class="bawas">' . __('None', 'baw_as' ) . '</span></span>' . $actions['edit'];}
  return $actions;
}

function bawas_write_stats()
{
  global $wpdb;
  $URI = ''; $from = ''; $query_args = ''; $is_correct = false; $get_infos = false;
  bawas_query_var( $URI, $from, $is_correct, $get_infos, $query_args );
  if ( $from != '' && $is_correct && !$get_infos ) {
    $res = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . ' p, ' . $wpdb->postmeta . ' pm WHERE meta_key="%s" and meta_value="%s" and p.ID=pm.post_id', '_baw-as-specialkeyword', $from ) );
    if ( $from == get_option( 'baw-as_uselast' ) && ( get_option( 'baw-as_uselast' ) != '' ) ){
     $p = bawas_posts( true );
    }else{
     $p = (int)$res[0]->ID > 0 ? (int)$res[0]->ID : $from;
    }
    if ( $p > 0 ) {
      $refs = parse_url( strtolower( $_SERVER['HTTP_REFERER'] ) );  // Ne pas traduire ! // Do not translate !
      $refderef = esc_attr( $refs[host] ) != '' ? esc_attr( $refs[host] ) : 'noref';
      switch( get_option( 'baw-as_reflevel' ) ){
        case 'medium': $referer = esc_attr( $refs[host] . $refs[path] ); break;
        case 'high': $referer = esc_attr( $refs[host] . $refs[path] . '?' . $refs[query] . $refs[fragment] ); break;
        default: $referer = $refderef; // case 'low'
      }
      $referer = ( $referer == '' ) || ( $referer == '?' ) ? 'noref' : $referer;
      $clics = get_post_meta( $p, '_baw-as-clic', true ) + 1;
      update_post_meta( $p, '_baw-as-clic', $clics );
      update_post_meta( $p, '_baw-as-last', date( 'l d F Y' ) );
      if (get_post_meta( $p, '_baw-as-first', true) == '' ) {
        add_post_meta( $p, '_baw-as-first', date( 'l d F Y' ) );
      }
      $actual_referers = get_post_meta( $p, '_baw-as-referers', false );
      if ( !in_array( $refderef, $actual_referers ) ) {
        add_post_meta( $p, '_baw-as-referers', $refderef );
      }
      $refderef = bawas_sanitize_title( $refderef );
      add_post_meta( $p, '_baw-as-ref-' . $refderef, $referer );
    }
  }
}

function bawas_posts( $force = false )
{
  global $post, $wpdb, $current_user, $wp_query;
  $URI = ''; $from = ''; $query_args = ''; $is_correct = false; $get_infos = false;
  bawas_query_var( $URI, $from, $is_correct, $get_infos, $query_args );
  if ( isset( $_GET['bawas_bookmarklet'] ) && isset( $_GET['u'] ) && isset( $_GET['n'] ) ) { // Bookmarklet
    $book_ok = __( 'Error : You do not have the permission to do that.', 'baw_as' );
    $word = bawas_sanitize_title( $_GET['k'] );
    $url = esc_url_raw( $_GET['u'] );
    if ( $_GET['n'] === get_option('baw-as_bookmarklet_nonce' ) && $_GET['u'] != 'null' )
    {
      $book_ok = __( 'Error : Keyword already exists.', 'baw_as' );
      if ( ( ( $word=='') && bawas_keyword_is_ok( $word, true ) ) ||
           ( ( $word!='') && bawas_keyword_is_ok( $word, get_option( 'baw-as_usegeneratednameforbookmarklet' ) == 'on' ) )
           ) {
        $book_ok = __( 'The new keyword have been added !', 'baw_as' );
        $theID = bawas_wp_insert_post( $word );
        if ( $theID > 0) {
          add_post_meta( $theID, '_baw-as-302url', $url );
          add_post_meta( $theID, '_baw-as-specialkeyword', $word );
          delete_post_meta( $theID, '_baw-as-lastkeyword' );
          add_post_meta( $theID, '_baw-as-lastkeyword', $word );
        }
      }
    }
  $shares = bawas_get_shares( trailingslashit( BAWAS_DEFURL ) . $word, BAWAS_MODE_INFOS );
  $share = '';
  if( $shares != '' )
    foreach( $shares as $val ) {
      $share .= '&nbsp;<a href="' . $val['sharelink'] . '" target="_blank" title="' . $val['stitle'] . '" class="bawasshare button"><img src="' . $val['favicon'] . '" class="bawasinside" alt="favicon" /> ' . $val['sname'] . '</a>&nbsp;';
    }
    $args = array( 'response' => '200' );
    wp_die( '<img src="' . BAWAS_IMAGES_URL . 'mini_logo.png" /><br />' . $book_ok . ' <br /><input type="text" size="50" value="' . BAWAS_DEFURL . '/' . $word . '" /><br /><br />' . $share . '<br/><br/>' . '<a href="' . admin_url('admin.php?page=baw_as_config') . '">&laquo ' . __('Back', 'baw_as') . '</a>', BAWAS_FULLNAME . ' : Bookmarklet link creation', $args );
  }else
  if ( ( $from!='' && $is_correct ) || $force ){
    // "last" redirection
    if ( $force || ( $from == get_option( 'baw-as_uselast' ) && ( get_option( 'baw-as_uselast' ) != '') && !$get_infos ) ) {
     $args['showposts'] = 1;
     $args['post_type'] = (array)get_option( 'baw-as_posttypes' );
     $p = query_posts( $args );
     while ( have_posts() ) : the_post();
      if ( !$force ) {
        bawas_write_stats();
        wp_redirect( get_permalink() . $query_args, 301 );
        exit;
        break;
      }else{
       return $post->ID;
      }
     endwhile;
    }else{ // Autres redirections // Other redirections
     // Demande d'info avec + // Asking for info with +
     if ( $from != '' && $get_infos && $is_correct ) {
      if ( $from == get_option( 'baw-as_uselast' ) && ( get_option( 'baw-as_uselast' ) != '' ) ) {
        $ID = bawas_posts( true );
      }else{
       $res = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->postmeta . ' pm, ' . $wpdb->posts . ' p WHERE p.ID=pm.post_id AND pm.meta_key=%s AND meta_value=%s LIMIT 1', '_baw-as-specialkeyword', $from ) );
       $ID = $res[0]->ID;
      }
      $ID = $ID > 0 ? $ID : $from;
      if ( get_permalink( $ID ) != '' ) {
        // Creation de la page info // Template info page
        DEFINE( 'BAWAS_IN_INFOS', true );
        include( 'infos.php' );
        // Fin de creation // End of template
        exit;
        die();
      }
     }else // Ni "last", ni info // neither last", neighter info
     if ( $from != '' && $is_correct ) {
       $res = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ID FROM ' . $wpdb->posts . ' WHERE post_name="%s" UNION SELECT DISTINCT post_id AS ID FROM ' . $wpdb->postmeta . ' WHERE meta_key="%s" AND meta_value="%s"', $from, '_baw-as-specialkeyword', $from ) );
       $ID = $res[0]->ID > 0 ? $res[0]->ID : $from;
       $url302 = esc_url_raw(get_post_meta( $ID, '_baw-as-302url', true ));
       if ( $url302 != '' ) {
         bawas_write_stats();
         wp_redirect( $url302 . $query_args, 301 );
         exit;
       }else{
         $res = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . ' p, ' . $wpdb->postmeta . ' pm WHERE meta_key="%s" and meta_value="%s" and p.ID=pm.post_id', '_baw-as-specialkeyword', $from ) );
         $ID = $res[0]->ID > 0 ? $res[0]->ID : $from;
         if ( (int)$ID > 0 && ( get_permalink( $ID ) != '' ) ) {
            bawas_write_stats();
            wp_redirect( get_permalink($ID) . $query_args, 301 );
            exit;
         }else{
           // Ne rien faire, laisser WordPress faire son travail // Nothing to do, let WordPress do his job
         }
       }
     }
    }
  }
}

function bawas_page() {
  global $wpdb;
?>
<div class="wrap">
<div class="icon32" id="icon-options-general"><br></div>
<h2><?php _e('BAW Auto Shortener', 'baw_as' ); ?></h2>
<?php
if ( isset( $_GET['settings-updated'] ) ) {
  bawas_write_warning( 'up', __('Settings saved.', 'baw_as'), 'updated' );
}
?>
<form method="post" action="options.php" id="bawas_form">

<div class="bawtab">
  <input type="hidden" id="whichtab" name="baw-as_whichtab" value="<?php echo (int)get_option( 'baw-as_whichtab' ); ?>"/>
	<ul class="bawtabs">
 	  <li class="bawtab01"><a class="bawtab01" value="icon-options-general" href="#tab01" rel="1"><h2><?php _e( 'General settings', 'baw_as' ); ?></h2></a></li>
 	  <li class="bawtab02"><a class="bawtab02" value="icon-themes" href="#tab02" rel="2"><h2><?php _e( 'Advanced settings', 'baw_as' ); ?></h2></a></li>
 	  <li class="bawtab03"><a class="bawtab03" value="icon-tools" href="#tab03" rel="3"><h2><?php _e( 'Tools', 'baw_as' ); ?></h2></a></li>
 	  <li class="bawtab04"><a class="bawtab04" value="icon-link" href="#tab04" rel="4"><h2><?php _e( 'Special redirections', 'baw_as' ); ?></h2></a></li>
 	  <li class="bawtab05"><a class="bawtab05" value="icon-edit-comments" href="#tab05" rel="5"><h2><?php _e( 'Help/FAQ', 'baw_as' ); ?></h2></a></li>
	</ul>

	<div class="bawtab01">
		<?php include( 'includes/general-settings.inc.php' ); ?>
	</div>

	<div class="bawtab02">
		<?php include( 'includes/advanced-settings.inc.php' ); ?>
	</div>

	<div class="bawtab03">
		<?php include( 'includes/tools.inc.php' ); ?>
	</div>

	<div class="bawtab04">
		<?php include( 'includes/special-redirections.inc.php' ); ?>
	</div>

	<div class="bawtab05">
		<?php include( 'includes/help.inc.php' ); ?>
	</div>

    <?php settings_fields( 'bawas-settings-group' ); ?>

    <p class="submit bawasfleft">
    <input type="submit" tabindex="32767" class="button-primary" value="<?php _e( 'Save Changes', 'baw_as' ); ?>" />
    <span class="bawasdup_red bawasdnone" id="errordup"><?php _e( 'Error, there is some duplicate keywords', 'baw_as' ); ?></span>
    </p>

</div>

</form>
</div>
<?php
}

if ( !function_exists( 'baw_about' ) ) 
{
  function baw_about() {
    include( 'about.php' );
  }
}

function baw_as_default_values()
{
  if ( get_option( 'baw-as_bookmarklet_nonce' ) == '' ) {
    $news = array( 'baw' => 'http://www.boiteaweb.fr',
                   'twbaw' => 'http://twitter.com/boiteaweb/',
                   'twbawas' => 'http://twitter.com/autoshort/',
                   'bawas' => 'http://baw.li/bawas',
                   'login' => site_url() . '/wp-admin/',
                   's' => site_url() . '/?s='
                 );
    foreach( $news as $word => $url )
    {
      $ID = bawas_wp_insert_post( $word );
      if ( $ID > 0 ) {
        add_post_meta( $ID, '_baw-as-302url', $url );
        add_post_meta( $ID, '_baw-as-specialkeyword', $word );
        delete_post_meta( $ID, '_baw-as-lastkeyword' );
        add_post_meta( $ID, '_baw-as-lastkeyword', $word );
      }
    }
  }

  if ( get_option( 'baw-as_donate') == '' ) {
    add_option( 'baw-as_donate', '' );
  }
  if ( get_option( 'baw-as_keepqueryvars' ) == '' ) {
    add_option( 'baw-as_keepqueryvars', 'on' );
  }
  if ( get_option( 'baw-as_usehelp' ) == '' ) {
    add_option( 'baw-as_usehelp', 'on' );
  }
  if ( get_option( 'baw-as_posttypes' ) == '' ) {
    add_option( 'baw-as_posttypes', array( 'post' ) );
  }
  if ( get_option( 'baw-as_sortbyorder' ) == '' ) {
    add_option( 'baw-as_sortbyorder', 'desc' );
  }
  if ( get_option( 'baw-as_sortby') == '' ) {
    add_option( 'baw-as_sortby', 'date' );
  }
  if ( get_option( 'baw-as_sharedomains') == '' ) {
    add_option( 'baw-as_sharedomains', "http://facebook.com/sharer.php?u={%URL%}\nhttp://twitter.com/?status={%URL%}" );
  }
  if ( get_option( 'baw-as_fordibben_keywords' ) == '' ) {
    add_option( 'baw-as_fordibben_keywords', 'sex,sexe,porn,porno,dick,cunt,fuck,gay,lesb' );
  }
  if ( get_option( 'baw-as_whichtab' ) == '' ) {
    add_option( 'baw-as_whichtab', '1' );
  }
  if ( get_option( 'baw-as_usekeywordsaslink' ) == '' ) {
    add_option( 'baw-as_usekeywordsaslink', 'on' );
  }
  if ( get_option( 'baw-as_autokeyword' ) == '' ) {
    add_option( 'baw-as_autokeyword', 'on' );
  }
  if ( get_option( 'baw-as_howmanystats' ) == '' ) {
    add_option( 'baw-as_howmanystats', '10' );
  }
  if ( get_option( 'baw-as_checkduplicates' ) == '' ) {
    add_option( 'baw-as_checkduplicates', '' );
  }
  if ( get_option( 'baw-as_howmanypage' ) == '' ) {
    add_option( 'baw-as_howmanypage', '10' );
  }
  if ( get_option( 'baw-as_url' ) == '' ) {
    add_option( 'baw-as_url', site_url() );
  }
  if ( get_option( 'baw-as_generated_name' ) == '' ) {
    add_option( 'baw-as_generated_name', 'a' );
  }
  if ( get_option( 'baw-as_bookmarklet_nonce' ) == '' ) {
    add_option( 'baw-as_bookmarklet_nonce', bawas_special_nonce( '' ) );
  }
  if ( get_option( 'baw-as_replacepermalink' ) == '' ) {
    add_option( 'baw-as_replacepermalink', 'on' );
  }
  if ( get_option( 'baw-as_uselast' ) == '' ) {
    add_option( 'baw-as_uselast', 'last' );
  }
  if ( get_option( 'baw-as_reflevel' ) == '' ) {
    add_option( 'baw-as_reflevel', 'low' );
  }
  if ( get_option( 'baw-as_usegeneratednameforbookmarklet' ) == '' ) {
    add_option( 'baw-as_usegeneratednameforbookmarklet', '' );
  }
}

function bawas_add_custom_box() 
{
    add_meta_box(
        'baw_as_meta_box_stats',
     __( 'BAW AS Stats', 'baw_as' ),
        'baw_as_meta_box',
        'post'
    );
    add_meta_box(
        'baw_as_meta_box_stats',
     __( 'BAW AS Stats', 'baw_as' ),
        'baw_as_meta_box',
        'page'
    );
}

function baw_as_meta_box()
{
  global $post;
  echo '<h4>' . __('Statistics', 'baw_as' ) . '</h4>';
  $refs = __('None', 'baw_as'); $first = ''; $last = ''; $stats = array(); $allclics = 0;
  $refapi = implode( '|', get_post_meta( $post->ID, '_baw-as-referers', false ) );
  $refapi = substr( str_replace( '|noref|', '|' . __( 'No referer', 'baw_as' ) . '|', '|' . $refapi . '|' ), 1, -1 );
  $clics = (int)bawas_return_stat( $post->ID, $refs, $first, $last, $stats, $allclics );
  $stats1 = implode( ',', $stats );
  $stats2 = implode( '|', $stats );
  $clics = sprintf(_n( '%s clic', '%s clics', $clics, 'baw_as' ), $clics );
  $chco_color = sprintf( "%06X", mt_rand( 0, 0xFFFFFF ) );
  $kw = baw_auto_shortener( '', 0, true );
  bawas_write_links( 0, $post->ID, $kw, BAWAS_MODE_METABOX, $refs, $refapi, $first, $last, $stats1, $stats2, $allclics, $clics );
}

function bawas_doublons_warning()
{
  global $wpdb;
  $req = 'SELECT GROUP_CONCAT(DISTINCT post_name) AS Vals FROM ' . $wpdb->posts . ' p WHERE p.post_name IN (SELECT p1.post_name FROM ' . $wpdb->posts . ' p1 WHERE p1.post_status="publish" OR p1.post_status="private" GROUP BY p1.post_name HAVING COUNT(*)>1)';
  $req.= ' UNION ALL ';
  $req.= 'SELECT GROUP_CONCAT(DISTINCT p2.ID) as Vals FROM ' . $wpdb->posts . ' p2, ' . $wpdb->postmeta . ' pm WHERE pm.meta_key="_baw-as-specialkeyword" AND pm.meta_value=ID AND p2.post_status="publish"';
  $req.= ' UNION ALL ';
  $req.= 'SELECT GROUP_CONCAT(DISTINCT p2.post_name) AS Vals FROM ' . $wpdb->posts . ' p2, ' . $wpdb->postmeta . ' pm WHERE pm.meta_key="_baw-as-specialkeyword" and pm.meta_value=p2.post_name AND pm.post_id!=p2.ID';
  $req.= ' UNION ALL ';
  $req.= 'SELECT GROUP_CONCAT(DISTINCT pm3.meta_value) AS Vals FROM ' . $wpdb->postmeta . ' pm3, ' . $wpdb->posts . ' p3 WHERE pm3.meta_key="_baw-as-specialkeyword" AND (pm3.meta_value="%s" OR pm3.meta_value="%s") AND pm3.post_id=p3.ID';
  $req.= ' UNION ALL ';
  $req.= 'SELECT GROUP_CONCAT(DISTINCT p4.post_name) AS Vals FROM ' . $wpdb->posts . ' p4, ' . $wpdb->postmeta . ' pm4 WHERE post_status="publish" AND (p4.post_name=%s OR p4.post_name=%s)';
  $res = $wpdb->get_results( $wpdb->prepare( $req, get_option( 'category_base' ), get_option( 'tag_base' ), get_option( 'category_base'), get_option('tag_base' ) ) );
  if ( $res[0]->Vals != '' || $res[1]->Vals != '' || $res[2]->Vals != '' || $res[3]->Vals != '' || $res[4]->Vals != '' ) {
    $res = $res[0]->Vals . ',' . $res[1]->Vals . ',' . $res[2]->Vals . ',' . $res[3]->Vals . ',' . $res[4]->Vals;
    if ( $res[0] == ',' ){ $res = substr( $res, 1 ); }
    if ( $res[strlen( $res )-1] == ',' ){ $res = substr( $res, 0, -1 ); }
    $res = str_replace( ',,', ',', $res );
    bawas_write_warning( 'doublon', __( 'Take care ! You\'ve got some duplicated keywords, slugs or ID : ', 'baw_as' ) . $res , 'error', true );
  }
}

function bawas_uninstaller(){
  global $wpdb;
  $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->posts . ' WHERE post_type="%s"', BAWAS_POST_TYPE ) );
  $wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "baw-as%"' );
  $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "_baw-as%"' );
}

function bawas_register_settings()
{
  register_setting( 'bawas-settings-group', 'baw-as_showadslink' );
  register_setting( 'bawas-settings-group', 'baw-as_donate' );
  register_setting( 'bawas-settings-group', 'baw-as_keepqueryvars' );
  register_setting( 'bawas-settings-group', 'baw-as_usehelp' );
  register_setting( 'bawas-settings-group', 'baw-as_posttypes' );
  register_setting( 'bawas-settings-group', 'baw-as_sortbyorder' );
  register_setting( 'bawas-settings-group', 'baw-as_sortby' );
  register_setting( 'bawas-settings-group', 'baw-as_sharedomains' );
  register_setting( 'bawas-settings-group', 'baw-as_fordibben_keywords' );
  register_setting( 'bawas-settings-group', 'baw-as_url', 'bawas_esc_untrail' );
  register_setting( 'bawas-settings-group', 'baw-as_usekeywordsaslink' );
  register_setting( 'bawas-settings-group', 'baw-as_autokeyword' );
  register_setting( 'bawas-settings-group', 'baw-as_checkduplicates' );
  register_setting( 'bawas-settings-group', 'baw-as_howmanystats', 'intval' );
  register_setting( 'bawas-settings-group', 'baw-as_howmanypage', 'intval' );
  register_setting( 'bawas-settings-group', 'baw-as_whichtab', 'intval' );
  register_setting( 'bawas-settings-group', 'baw-as_replacepermalink' );
  register_setting( 'bawas-settings-group', 'baw-as_bookmarklet_nonce', 'bawas_special_nonce');
  register_setting( 'bawas-settings-group', 'baw-as_uselast', 'bawas_sanitize_title' );
  register_setting( 'bawas-settings-group', 'baw-as_reflevel' );
  register_setting( 'bawas-settings-group', 'baw-as_usegeneratednameforbookmarklet' );
}

function bawas_register_plugin_js_css()
{
  global $oddcolor;
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'jquery-ui-core' );
  wp_register_script( 'jquery-qsort', plugins_url( '/js/qsort.js', __FILE__ ) );
  wp_enqueue_script( 'jquery-qsort' );

  wp_register_style( 'bawas-css', plugins_url( '/css/baw_as.css', __FILE__ ) );
  wp_enqueue_style( 'bawas-css');
  $admincolor = get_user_meta( get_current_user_id(), 'admin_color', true );
  $admincolor = $admincolor == 'classic' ? 'classic' : 'fresh';
  wp_register_style( 'baw-tabs-css', plugins_url( '/css/baw_tabs_' . $admincolor . '.css', __FILE__ ) );
  wp_enqueue_style( 'baw-tabs-css');
  wp_register_style( 'baw-as-dash-widget', plugins_url( '/css/baw_as_dashboard_widget.css', __FILE__ ) );
  wp_enqueue_style( 'baw-as-dash-widget');

  wp_register_script( 'baw-tabs-js', plugins_url( '/js/baw_tabs.js', __FILE__ ) );
  wp_enqueue_script( 'baw-tabs-js');

  $currenttab = (int)get_option( 'baw-as_whichtab' ) - 1;
  $oddcolor = $admincolor == 'classic' ? 'EAF3FA' : 'F1F1F1';
	wp_localize_script( 'baw-tabs-js', 'baw_tabs_l10n',
                      array(
            			     'currenttab' => $currenttab,
            		    	 'oddcolor' => '#'.$oddcolor
	                    )
                    );
}

function bawas_register_admin_js_css()
{
  wp_enqueue_script( 'jquery' );

  wp_register_script( 'bawas-js', plugins_url( '/js/baw_as.js', __FILE__ ) );
  wp_enqueue_script( 'bawas-js' );

  wp_register_script( 'bawas-ajax', plugins_url( '/js/baw_as_ajax.js', __FILE__ ) );
  wp_enqueue_script( 'bawas-ajax');

	wp_localize_script('bawas-ajax', 'bawas_ajax_l10n', 
                      array(
	                    'BAWAS_IMAGES_URL' => BAWAS_IMAGES_URL,
	                    'miniurl' => BAWAS_DEFURL,
            			    'Remove_this_entry' => __( 'Remove this entry', 'baw_as' ),
            			    'Add' => __( 'Add', 'baw_as' ),
            			    'CLIC' => __(' clic', 'baw_as' ),
            			    'Are_you_sure' => __( 'Are you sure to delete ALL stats for this entry ? (not reversible)', 'baw_as' )
	                    )
                    );

  $add_new_keyword_nonce = wp_create_nonce( 'add_new_keyword' );
  $del_keyword_nonce = wp_create_nonce( 'delete_kw' );
  $help_quickedit = get_option( 'baw-as_usehelp' ) == 'on' ? __( 'Here you can quickly add keywords for a post/page/custom post.', 'baw_as' ) : '';
	wp_localize_script( 'bawas-js', 'bawas_l10n', 
                      array(
	                    'BAWAS_IMAGES_URL' => BAWAS_IMAGES_URL,
            		      'Remove_this_entry' => __('Remove this entry', 'baw_as'),
                			'Yes' => __('Yes', 'baw_as'),
                			'No' => __('No', 'baw_as'),
                			'Add' => __('Add', 'baw_as'),
                			'add_new_keyword_nonce' => $add_new_keyword_nonce,
            		    	'del_keyword_nonce' => $del_keyword_nonce,
                			'ora' => __('Its stats will be the same for all other keywords', 'baw_as'),
                			'red' => __('Duplicates keywords cannot works !', 'baw_as'),
                			'reload_view_stats' => __('Reload to view stats', 'baw_as'),
                			'sortby' => esc_attr( get_option('baw-as_sortby') ),
            		    	'pagination' => esc_attr( get_option('baw-as_howmanypage') ),
                			'help_quickedit' => $help_quickedit,
                			'Leave_it_blank' => __('Leave it blank to generate a key', 'baw_as')
	                    )
                    );

}

function bawas_check_versions() 
{
  global $wp_version;
	if ( version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'This plugin requires PHP 5.0 or more', 'baw_as' ) );
	}
	if ( version_compare( $wp_version, '3.1', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'This plugin requires WordPress 3.1 or more. Your version : ' . $wp_version, 'baw_as' ) );
	}
}

function bawas_admin_footer()
{
  echo __( 'This site is using', 'baw_as' ) . ' <a href="http://baw.li/bawas">BAW Auto Shortener</a> v ' . BAWAS_VERSION . ' | ';
}

function bawas_wp_footer() 
{
 if ( DEFINED( 'BAWAS_IN_INFOS' ) || get_option( 'baw-as_showadslink' ) == 'on' ) {
  echo __( 'This site is using', 'baw_as' ) . ' <a href="http://baw.li/bawas">BAW Auto Shortener</a> v ' . BAWAS_VERSION;
 }
}

function bawas_add_post_type() {
  register_post_type( BAWAS_POST_TYPE,
    array( 'label' => BAWAS_POST_TYPE,
     'singular_label' => BAWAS_POST_TYPE,
     'public' => False,
     'show_ui' => False,
     'capability_type' => 'post',
     'supports' => array(
      'custom-fields'
     ),
     'hierarchical' => false
    )
  );
}

function bawas_auto_add_keyword()
{
  global $post, $wpdb;
  if ( $post->post_status != 'publish' ) {
    $next = esc_attr( get_option( 'baw-as_generated_name' ) );
    bawas_keyword_is_ok( $next, true );
    add_post_meta( $post->ID, '_baw-as-specialkeyword', $next );
    delete_post_meta( $post->ID, '_baw-as-lastkeyword' );
    add_post_meta( $post->ID, '_baw-as-lastkeyword', $next );
  }
}

function bawas_reset_stats()
{
  if ( isset( $_POST['postID'] ) && isset( $_POST['nonce'] ) ) {
    $ID = (int)$_POST['postID'];
  }else{
    $ID = 0;
  }
  if ( wp_verify_nonce( $_POST['nonce'], 'reset_stat_' . $ID ) ) {
   check_ajax_referer( 'reset_stat_'.$ID, 'nonce' );
    $allreferers = get_post_meta( $ID, '_baw-as-referers', false );
    foreach( $allreferers as $oneref ) {
     delete_post_meta( $ID, '_baw-as-ref-'.bawas_sanitize_title( $oneref ) );
    }
    delete_post_meta( $ID, '_baw-as-referers' );
    delete_post_meta( $ID, '_baw-as-first' );
    delete_post_meta( $ID, '_baw-as-last' );
    delete_post_meta( $ID, '_baw-as-clic' );
    echo get_post_meta( $ID, '_baw-as-clic', true );
  }else{
    wp_die( __( 'You do not have the permission to do this', 'baw_as' ) );
  }
}

function bawas_add_new_keyword()
{
  global $wpdb;
  if ( isset( $_POST['word'] ) ) {
    $word = bawas_sanitize_title( $_POST['word'] );
  }else{
    $word = '';
  }
  $response = json_encode( array( 'ok' => '0' ) );
  if ( isset( $_POST['postID'] ) ) {
    $ID = (int)$_POST['postID'];
  }else{
    $ID = 0;
  }
  if ( isset( $_POST['area'] ) ) {
    $area = $_POST['area'];
  }else{
    $area = '';
  }
  $img = '';
  if ( $ID == 0 && isset( $_POST['postID'] ) ) {
    $url = $_POST['postID'];
    $head = wp_remote_get( $url, array( 'user-agent' => 'Wordpress/ ' . esc_url_raw( site_url() ) ) );
    if ( count ($head->errors ) == 0 ) {
      $img = ' <img src="' . BAWAS_IMAGES_URL . 'valid.png" alt="valid" title="' . __( 'Valid URL', 'baw_as' ) . '" />&nbsp;';
    }else{
      $img = ' <img src="' . BAWAS_IMAGES_URL . 'notvalid.png" width="18" alt="novalid" title="' . __( 'Invalid URL (404 ?)', 'baw_as' ) . '" />&nbsp;';
    }
    unset( $head );
  }
  if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'add_new_keyword' ) && bawas_keyword_is_ok( $word, true && $word == '' ) && ( (int)$ID > 0 || $url != '' ) ) {
    check_ajax_referer( 'add_new_keyword', 'nonce' );
    $res = $wpdb->get_results( $wpdb->prepare( 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key=%s AND meta_value LIKE %s AND meta_value!=""', '_baw-as-302url', $url ) );
    $ID = $res[0]->post_id > 0 ? $res[0]->post_id : $ID;
    if ( $ID == 0) {
      $ID = bawas_wp_insert_post( $word );
    }
    if ( $ID > 0 ){
      $response = json_encode( array( 'ok' => '1', 'ID' => $ID, 'word' => $word, 'img' => $img ) );
      if ( $res[0]->post_id == 0 ) {
        add_post_meta( $ID, '_baw-as-302url', $url );
      }
      add_post_meta( $ID, '_baw-as-specialkeyword', $word );
      delete_post_meta( $ID, '_baw-as-lastkeyword' );
      add_post_meta( $ID, '_baw-as-lastkeyword', $word );
    }
  }
  header( "Content-Type: application/json" );
  echo $response;
  exit;
}

function bawas_del_keyword()
{
  if ( isset( $_POST['word'] ) ) {
    $word = bawas_sanitize_title( $_POST['word'] );
  }else{
    $word = '';
  }
  $response = json_encode( array( 'ok'=>__('Error', 'baw_as') ) );
  if ( $word != '' ) {
    if ( isset( $_POST['postID'] ) ) {
      $ID = (int)$_POST['postID'];
    }else{
      $ID = 0;
    }
    if ( isset( $_POST['nonce'] ) && wp_verify_nonce($_POST['nonce'], 'delete_kw' ) ) {
      check_ajax_referer( 'delete_kw', 'nonce' );
      $response = json_encode( array( 'ok'=>'1' ) );
      delete_post_meta( $ID, '_baw-as-specialkeyword', $word );
      delete_post_meta( $ID, '_baw-as-lastkeyword', $word );
      if ( isset( $_POST['area'] ) && $_POST['area'] == 'settings' ) {
       delete_post_meta( $ID, '_baw-as-302url', $word );
       wp_delete_post( $ID, true );
      }
    }
  }
  header( "Content-Type: application/json" );
  echo $response;
  exit;
}

function bawas_settings_action_links( $links, $file ) 
{
  if ( strstr( __FILE__, $file ) != '' ) {
   $title_link = __( 'Settings', 'baw_as' );
   $settings_link = '<a href="' . admin_url( 'admin.php?page=baw_as_config' ) . '">' . $title_link . '</a>';
   array_unshift( $links, $settings_link );
  }
  return $links;
}

 add_action( 'init','bawas_l10n_init' );
 add_filter( 'the_shortlink', 'bawas_the_shortlink', 1 );
 add_filter( 'get_shortlink', 'bawas_get_shortlink', 1, 2 );
 add_filter( 'post_row_actions', 'bawas_postid_show', '10', '2' );
 add_filter( 'page_row_actions', 'bawas_pageid_show', '10', '2' );
 add_filter( 'plugin_action_links', 'bawas_settings_action_links', 10, 2 );
 if ( get_option( 'baw-as_checkduplicates' ) == 'on' ) {
   add_action( 'admin_notices', 'bawas_doublons_warning' );
 }
 add_action( 'admin_init', 'bawas_register_settings' );
 add_action( 'admin_init', 'bawas_register_admin_js_css' );
 add_action( 'admin_init', 'bawas_register_plugin_js_css' );
 add_action( 'in_admin_footer', 'bawas_admin_footer' );
 add_action( 'wp_footer', 'bawas_wp_footer' );
 add_action( 'add_meta_boxes', 'bawas_add_custom_box' );
 add_action( 'init', 'bawas_add_post_type' );
 add_action( 'init', 'bawas_posts' );
 if ( get_option( 'baw-as_autokeyword' ) == 'on' ) {
   add_action( 'publish_post', 'bawas_auto_add_keyword' );
 }
 add_action( 'admin_menu', 'bawas_create_menu' );
 add_action( 'wp_ajax_bawas_reset_stats', 'bawas_reset_stats' );
 add_action( 'wp_ajax_bawas_add_new_keyword', 'bawas_add_new_keyword' );
 add_action( 'wp_ajax_bawas_del_keyword', 'bawas_del_keyword' );
 add_action( 'wp_dashboard_setup', 'bawas_dashboard_widget_setup' );
 add_shortcode( 'bawas_shortlink', 'bawas_shortcode' );
 register_uninstall_hook( __FILE__, 'bawas_uninstaller' );
 register_activation_hook( __FILE__, 'baw_as_default_values' );
 register_activation_hook( __FILE__, 'bawas_check_versions' );

?>