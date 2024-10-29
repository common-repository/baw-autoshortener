<?php
if ( !defined( 'ABSPATH' ) )
	wp_die( __( 'Please do not access this file directly. Thanks!') );

function bawas_esc_untrail( $url )
{
  return esc_url_raw( untrailingslashit( $url ) );
}

function bawas_get_shares( $url, $mode )
{
  $a = explode( "\n", get_option( 'baw-as_sharedomains' ) );
  foreach( $a as $val )
  {
    if( $val != '' ) {
      $val = esc_attr( $val );
      $parsed = parse_url( $val );
      $stitle = ucwords( bawas_get_hostname( $parsed['host'] ) );
      if( $mode == BAWAS_MODE_SETTINGS ){
        $sname = '';
      }else{
        $sname = $stitle;
      }
      $shares[] = array( 'val' => trim( $val ),
                         'sharelink' => str_replace( '{%URL%}', $url, $val ),
                         'stitle' => $stitle,
                         'favicon' => baw_get_favicon( $val, true ),
                         'sname' => $sname
                       );
    }
  }
  return $shares;
}

function bawas_write_links( $i, $a_postsids, $a_keywords, $mode, $refs, $refapi, $first, $last, $stats1, $stats2, $allclics, $clics )
{
  global $oddcolor;
  $links = '';
  $addnkw = '';
  $favicon = '';
  $j = 0;
  $url302 = get_post_meta( $a_postsids, '_baw-as-302url', true );
  if ( $url302 == '') {
    $posttitle = esc_attr( get_the_title( $a_postsids ) );
  }else{
     $cleanurls = parse_url( $url302 );
     $cleanurl = esc_attr( $cleanurls['host'] );
     $favicon = '<img src="http://www.google.com/s2/favicons?domain=' . $cleanurl . '" /> ';
     $posttitle = esc_url_raw( $url302 );
  }
  if ( $mode == BAWAS_MODE_SETTINGS ) {
    $speid = ' id="more_' . $i . '"';
  }
  if ( $mode != BAWAS_MODE_INFOS ) {
    $links .= '<span class="bawasbefstats"><a href="javascript:void(0)" class="button"' . $speid . ' rel="' . $i . '" title="' . __( 'Stats', 'baw_as' ) . '" ><img class="bawasinside" id="clic_' . $a_postsids . '" src="' . BAWAS_IMAGES_URL . 'stat.png" alt="stat" /> ' . $clics . '</a>';
    if ( $mode == BAWAS_MODE_SETTINGS ) {
      $links .= '&nbsp;<input type="button" class="button" value="' . __('Get link', 'baw_as' ) . '" onclick="prompt(\'' . __( 'Here is your short link !', 'baw_as' ) . '\',\'' . wp_get_shortlink( $a_postsids ) . '\');"/>';
    }
    $links .= '</span>';
  }else{
    $links .= '<span class="bawasbefstats"><h1>' . $favicon . $posttitle . '</h1> <h2><img class="bawasinside" src="' . BAWAS_IMAGES_URL . 'stat.png" alt="stat" /> ' . $clics . '</h2>';
    if ( $mode == BAWAS_MODE_INFOS && $url302 != '' ) {
      $links .= '<div style="position: relative; float: right; top: -75px;"><a rel="fancybox" class="fancybox" href="http://www.apercite.fr/api/apercite/800x600/oui/oui/' . esc_url( $url302 ). '?ext=.jpg" ><img alt="' . $url302 . ' ' . __('preview', 'baw_as') . '" title="' . $url302 . ' ' . __('preview', 'baw_as') . '" src="http://www.apercite.fr/api/apercite/200x125/oui/oui/' . $url302 . '" /></a></div>';
    }
    $links .= '</span>';
  }
  $shares = bawas_get_shares( trailingslashit( BAWAS_DEFURL ) . $a_keywords, $mode );
  $share = '';
  if( $shares != '' )
    foreach( $shares as $val ) {
      $share .= '&nbsp;<a href="' . $val['sharelink'] . '" target="_blank" title="' . $val['stitle'] . '" class="bawasshare button"><img src="' . $val['favicon'] . '" class="bawasinside" alt="favicon" /> ' . $val['sname'] . '</a>&nbsp;';
    }

  if ( $mode == BAWAS_MODE_SETTINGS ) {
    $links .= $share;
  }
  unset( $speid );
  if ( $mode == BAWAS_MODE_SETTINGS ) {
    $links .= '<div id="info_' . $i . '" class="bawasdnone">';
  } else {
    $links .= '<div id="info_' . $i . '">';
  }
  if ( $mode != BAWAS_MODE_INFOS && intval( $clics ) > 0 ) {
    $chco_color = sprintf( "%06X", mt_rand( 0, 0xFFFFFF ) );
    if ( $url302 == '' ) {
      $size = '300x200';
    }else{
      $size = '250x140';
    }
    $links .= '<span class="bawasfright">';
    $links .= '<img src="http://chart.apis.google.com/chart?chs=' . $size . '&chco=' . $chco_color . '&chf=bg,s,' . $oddcolor . '&cht=p&chd=t:' . $stats1 . '&chl=' . $stats2 . '&chdl=' . $refapi . '&chdlp=b" />';
    $links .= '<div class="bawasfix"></div>';
    $ajaxnonce = wp_create_nonce( 'reset_stat_' . $a_postsids );
    $links .= '<a title="Reset stats" id="' . $a_postsids.  '" rel="' . $ajaxnonce . '" class="button bawasresetstats" href="javascript:void(0)"><img alt="stat" src="' . BAWAS_IMAGES_URL . 'stat.png" class="bawasinside"> Reset stats</a>';
    $links .= '</span>';
  }
  if ( $mode != BAWAS_MODE_INFOS ) {
    $links.= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'type.png" alt="post type" title="' . __( 'Post type', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Post type', 'baw_as' ) . '</em> : ' . ucwords( get_post_type( $a_postsids ) ) . '</div>';
  }
  $getcomments = array( 'post_id' => $a_postsids, 'status' => 'approve' );
  if ( $url302 == '' ) {
    $links .= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'comment.png" alt="comments" title="' . __( 'Comments', 'baw_as' ) . '" /> <em class="bawastitle">'.__('Comments', 'baw_as').'</em> : ' . count( get_comments( $getcomments ) ) . ' ' . __( 'comment(s)', 'baw_as' ) . '</div>';
    $links .= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'date.png" alt="date" title="' . __( 'Post date', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Post date', 'baw_as' ) . '</em> : ' . get_the_time( 'l d F Y', $a_postsids ) . '</div>';
  }
  if ( intval( $clics ) > 0 ) {
    $links .= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'date.png" alt="date" title="' . __( 'First access', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'First access', 'baw_as' ) . '</em> : ' . $first . '</div>';
    $links .= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'date.png" alt="date" title="' . __( 'Last access', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Last access', 'baw_as' ) . '</em> : ' . $last . '</div>';
  }
  if ( $url302 != '' ) {
    $links .= '<div><img src="' . BAWAS_IMAGES_URL . 'bchain.png" alt="chain" title="'.__('External link', 'baw_as').'" /> <em class="bawastitle">' . __( 'External link', 'baw_as' ) . '</em> : <a href="' . esc_url_raw( $url302 ) . '" target="_blank">' . esc_url_raw( $url302 ) . '</a></div>';
  } else {
    $links .= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'post.png" alt="post" title="' . __( 'Post title', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Post title', 'baw_as' ) . '</em> : ' . get_the_title( $a_postsids ) . '</div>';
    $temp_permalink = get_permalink( $a_postsids );
    $links .= '<div><img src="' . BAWAS_IMAGES_URL . 'bchain.png" alt="chain" title="'.__('Big permalink', 'baw_as').'" /> <em class="bawastitle">' . __( 'Big permalink', 'baw_as' ) . '</em> : <a href="' . $temp_permalink . '" target="_blank">' . $temp_permalink . '</a></div>';
  }
  if ( $mode == BAWAS_MODE_INFOS && $url302 != '' ) {
    $url = $url302;
    $realurl = baw_get_my_headers( $url );
    if( $realurl != $url302 ){
      $realurl = esc_url_raw( $realurl );
      $links .= '<div><img src="' . BAWAS_IMAGES_URL . 'bchain.png" alt="chain" title="' . __( 'Real final link', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Real final link', 'baw_as' ) . '</em> : <a href="' . $realurl . '" target="_blank">' . $realurl . '</a></div>';
    }
  }
  $links .= '<div id="oldkeyword_">&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'schain.png" alt="chain" title="' . __( 'Short permalink', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Short permalink', 'baw_as' ) . '</em> : <a href="' . BAWAS_DEFURL . '/' . $a_postsids . '" target="_blank">' . BAWAS_DEFURL . '/' . $a_postsids . '</a>';
  $links .= ' <img class="miniget" src="' . BAWAS_IMAGES_URL . 'miniget.gif" alt="get" title="' . __( 'Get link', 'baw_as' ) . '" onclick="prompt(\'' . __( 'Here is your short link !', 'baw_as' ) . '\',\'' . BAWAS_DEFURL . $a_postsids . '\');" /><span></span>';
  $links .= '</div>';
  $specialkeywords = get_post_meta( $a_postsids, '_baw-as-specialkeyword', false );
  $ajaxnonce = wp_create_nonce( 'delete_kw' );
  $j=0;
  foreach( $specialkeywords as $val ) {
    if( $mode == BAWAS_MODE_INFOS || $j > -1 ){
      $links .= '<div id="oldkeyword_' . $j . '">&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'schain.png" alt="chain" title="' . __( 'Short permalink', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Short permalink', 'baw_as' ) . '</em> : <a href="' . BAWAS_DEFURL . '/' . $val . '" target="_blank">' . BAWAS_DEFURL . '/' . $val . '</a>';
      $links .= ' <img class="miniget" src="' . BAWAS_IMAGES_URL . 'miniget.gif" alt="get" title="' . __( 'Get link', 'baw_as' ) . '" onclick="prompt(\'' . __( 'Here is your short link !', 'baw_as' ) . '\',\'' . BAWAS_DEFURL . $val . '\');" /><span></span>';
      if ( ( $mode == BAWAS_MODE_SETTINGS && $j > 0 ) || $mode == BAWAS_MODE_METABOX ) {
        $links .= ' <img id="minidel_' . $j . '" src="' . BAWAS_IMAGES_URL . 'minidel.png" alt="del" title="' . __( 'Remove this entry', 'baw_as' ) . '" onclick="bawas_delkw(\'' . $val . '\', \'' . $ajaxnonce . '\', \'' . $a_postsids . '\', \'' . $j . '\')" /><span></span>';
      }
      $links .= '</div>';
    }
    $j++;
  }
  unset( $specialkeywords );
  unset( $j );
  if ( $mode != BAWAS_MODE_INFOS && intval( $clics ) > 0 ) {
    $links .= '<div><nobr>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'ref.png" alt="referers" title="' . __( 'Referers', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Referers list', 'baw_as' ) . '</em> : <select><option>' . __( 'All', 'baw_as' ) . ' (' . $allclics . ')</option>' . $refs . '</select></nobr></div>';
  }else{
    if ( get_post_meta( $a_postsids, '_baw-as-specialkeyword', true ) != '' ) {
      $links .= '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'ref.png" alt="share" title="' . __( 'Share', 'baw_as' ) . '" /> <em class="bawastitle">' . __( 'Share', 'baw_as' ) . '</em> : ' . $share . '</div>';
      $links .= '&nbsp;';
    }
  }
  if ( $mode == BAWAS_MODE_SETTINGS ) {
    $links.= '</div>';
  }
  echo '<div id="span_' . $i . '" clic="' . intval( $clics ) . '" date="' . $a_postsids . '" url="' . esc_url_raw( $url302 ) . '" keyword="' . $a_keywords . '" class="onlybottom">';
  if ( $mode == BAWAS_MODE_SETTINGS ) {
    $ajaxnonce = wp_create_nonce( 'delete_kw' );
    echo '<a href="javascript:void(0);" class="button" onclick="bawas_delkw(\'' . $a_keywords . '\', \'' . $ajaxnonce . '\', ' . $a_postsids . ', ' . $i . ', \'settings\');"><img class="bawasinside" id="imgdel_' . $i . '" src="' . BAWAS_IMAGES_URL . 'del.png" alt="del" title="' . __( 'Remove this entry', 'baw_as' ) . '" /></a>&nbsp;';
    echo '<input type="text" value="' . esc_url_raw( $url302 ) . '" title="' . esc_url_raw( $url302 ) . '" size="25" name="as_posts" id="as_posts_' . $i . '" readonly="true" />&nbsp;';
    echo '<span class="bawasarr">&rarr;</span>&nbsp;';
    echo '<input type="text" name="as_keys" size="15" id="as_keys_' . $i . '" value="' . $a_keywords . '" title="' . $a_keywords . '" class="bawaslocase" readonly="true" />&nbsp;';
  }
  if ( $mode == BAWAS_MODE_METABOX ) {
    $addnkw = bawas_include_addnkw( $a_postsids );
  }
  echo $links . $addnkw . '</div>';
  if ( $mode == BAWAS_MODE_METABOX ) {
    echo '</div>';
  }
  echo '</div>';
}

function bawas_include_addnkw( $a_postsids )
{
  global $post;
  $add_new_keyword_nonce = wp_create_nonce( 'add_new_keyword' );
  $ajaxnonce2 = wp_create_nonce( 'delete_kw' );
  $addnkw = '<div>&nbsp;&nbsp;<img src="' . BAWAS_IMAGES_URL . 'expand.png" alt="add" class="bawasinside" title="' . __( 'Add a new keyword', 'baw_as' ) . '" /> ';
  $addnkw .= '<em class="bawastitle">' . __( 'Add a new keyword', 'baw_as' ) . '</em> : ';
  $addnkw .= '<input type="text" name="newkeyword" id="newkeyword" /> <a class="button" id="imgaddnkw_0" onclick="bawas_addnkw(jQuery(\'#newkeyword\').val(), \'' . $add_new_keyword_nonce . '\', \'' . $post->ID . '\', \'' . $ajaxnonce2 . '\', \'\', \'0\')">' . __( 'Add', 'baw_as' ) . '</a> ';
  $addnkw .= '<span id="addnkw"></span></div>';
  if( get_option( 'baw-as_usehelp' ) == 'on' ) {
    $addnkw .= '<div class="bawashelper">';
    $addnkw .= __( 'Here you can quickly add keywords for a post/page/custom post.', 'baw_as' ) . '<br />';
    $addnkw .= __( 'You can also delete keywords and share links.', 'baw_as' ) . '<br />';
    $addnkw .= __( 'Also you\'ve got a stats screen, his help is in the "Special redirections" tab in <a href="admin.php?page=baw_as_config">the settings</a>.', 'baw_as' );
    $addnkw .=  '</div>';
  }
  return $addnkw;
}

function bawas_sanitize_title( $str )
{
  return sanitize_title( esc_attr( strip_tags( trim( $str ) ) ) );
}

function bawas_show_stats( $mode )
{
   global $wpdb;
   $sortby = esc_attr( get_option( 'baw-as_sortby' ) );
   $sortbyorder = esc_attr( get_option( 'baw-as_sortbyorder' ) );
   $res = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT p.ID, p.post_name AS K, pm.meta_value AS V FROM '.$wpdb->postmeta.' pm, '.$wpdb->posts.' p WHERE p.post_type=%s AND p.ID=pm.post_id AND pm.meta_key=\'_baw-as-302url\' ORDER BY %s %s', BAWAS_POST_TYPE, $sortby, $sortbyorder ) );
   for( $i = 0 ; $i < count( $res ) ; $i++ )
   {
     bawas_get_and_write_links( $i, $res[$i]->ID, $res[$i]->K, $mode );
   }
}

function bawas_get_and_write_links( $i, $id, $kw, $mode )
{
  $refs = __( 'None', 'baw_as' ); $first = ''; $last = ''; $stats = array(); $allclics = 0;
  $refapi = implode( '|', (array)get_post_meta( $id, '_baw-as-referers', false ) );
  $refapi = substr( str_replace( '|noref|', '|' . esc_attr( __( 'No referer', 'baw_as' ) ) . '|', '|' . $refapi . '|' ), 1, -1 );
  $clics = (int)bawas_return_stat( $id, $refs, $first, $last, $stats, $allclics );
  $stats1 = implode( ',', $stats );
  $stats2 = implode( '|', $stats );
  if ( $clics > 1 ) {
    $clics = sprintf( __( '%s clics', 'baw_as' ), $clics );
  }else{
    $clics = sprintf( __( '%s clic', 'baw_as' ), $clics );
  }
  bawas_write_links( $i, $id, $kw, $mode, $refs, $refapi, $first, $last, $stats1, $stats2, $allclics, $clics );
}

function bawas_wp_insert_post( $name )
{
  global $wpdb, $current_user, $post;
  if( $current_user->ID > 0 ) {
    $post = array(
       'comment_status' => 'closed',
       'ping_status' => 'closed',
       'post_author' => $current_user->ID,
       'post_content' => BAWAS_POST_TYPE,
       'post_excerpt' => BAWAS_POST_TYPE,
       'post_name' => $name,
       'post_status' => 'private',
       'post_title' => BAWAS_POST_TYPE,
       'post_type' => BAWAS_POST_TYPE
    );
    $ID =  wp_insert_post( $post );
    // Ne peut pas utiliser wpdb->update car LIKE et LENGTH. // Can not use wpdb->update because of LIKE and LENGTH.
    $res = $wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->posts . ' SET post_name="feed" WHERE post_name LIKE "feed-%" AND LENGTH(post_name)=6 AND post_type=%s', BAWAS_POST_TYPE ) );
    $res = $wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->posts . ' SET post_name="rss" WHERE post_name LIKE "rss-%" AND LENGTH(post_name)=5 AND post_type=%s', BAWAS_POST_TYPE ) );
    return $ID;
  }else{
    return 0;
  }
}

function bawas_return_stat( $from, &$refs, &$first, &$last, &$stats, &$allclics )
{
  $referers = get_post_meta( $from, '_baw-as-referers', false );
  $refs = '';
  $allclics = 0;
  if ( count( $referers ) > 0 ) {
    for ( $i = 0 ; $i < count ( $referers ) ; $i++ ) {
      $allrefs = get_post_meta( $from, '_baw-as-ref-' . bawas_sanitize_title( $referers[$i] ), false );
      $stats[] = count( $allrefs );
      $allclics = $allclics + count( $allrefs );
      krsort( $allrefs );
      $refrefs = array_count_values( $allrefs );
      $thereferer = str_replace( 'noref', esc_attr( __( 'No referer', 'baw_as' ) ), $referers[$i] );
      $refs .= '<optgroup label=" ' . $thereferer . ' (' . count( $allrefs ) . ')">';
      foreach( $refrefs as $key => $value ) {
        $thekey = str_replace( 'noref', esc_attr( __( 'No referer', 'baw_as' ) ), $key );
        $refs .= '<option> ->' . esc_attr( $thekey ) . ' (' . esc_attr( $value ) . ')</option>';
      }
      $refs .= '</optgroup>';
    }
     $first = get_post_meta( $from, '_baw-as-first', true ) != '' ? get_post_meta( $from, '_baw-as-first', true ) : __( 'Not yet !', 'baw_as' );
     $last = get_post_meta( $from, '_baw-as-last', true ) != '' ? get_post_meta( $from, '_baw-as-last', true ) : __( 'Not yet !', 'baw_as' );
     return get_post_meta( $from, '_baw-as-clic', true );
  }
}

function bawas_keyword_is_ok( &$kw, $force_next = false )
{
  global $wpdb;
  $res = $wpdb->get_results( $wpdb->prepare( 'SELECT CONCAT(GROUP_CONCAT(DISTINCT meta_value),\',\',GROUP_CONCAT(DISTINCT post_name)) AS Vals FROM ' . $wpdb->postmeta . ' pm, ' . $wpdb->posts . ' p WHERE pm.meta_key=%s AND post_status=%s', '_baw-as-specialkeyword', 'publish' ) );
  $list = $res[0]->Vals . ',' . esc_attr( get_option( 'baw-as_uselast' ) ) . ',' . esc_attr( get_option( 'baw-as_fordibben_keywords' ) ) . ',' . get_option( 'tag_base' ) . ',' . get_option( 'category_base' );
  if( ( ( $kw == '' ) && $force_next ) || strstr( ',' . $list . ',', ',' . $kw . ',' ) != '' ) {
    if( $force_next ) {
      $next = esc_attr( get_option( 'baw-as_generated_name' ) );
      while ( strstr( ',' . $list . ',', ',' . $next . ',' ) != '' ) {
        $next++;
      }
      $kw = $next;
      $next++;
      update_option( 'baw-as_generated_name', $next );
      return true;
    }else{
      return false;
    }
  }else{
    return true;
  }
}

function bawas_special_nonce( $str )
{
  if ( $str == '' ) {
   $str = strtolower( wp_create_nonce( 'special_nonce' . time() ) );
  }
  return $str;
}

function bawas_write_warning( $id, $msg, $type, $withsettings=false )
{
  $msg = esc_attr( $msg );
  if ( $withsettings )
  {
    $gotosettings = ' <a href="' . admin_url( 'admin.php?page=baw_as_config' ) . '">' . __( 'Go to settings', 'baw_as' ) . '</a>';
  }else{
    $gotosettings = '';
  }
  echo '<div class="' . $type . '" id="bawaswarnrules_' . $id . '"><p><strong>' . BAWAS_FULLNAME . ' : </strong> ' . $msg . $gotosettings . ' <span onclick="jQuery(\'#bawaswarnrules_' . $id . '\').fadeOut(\'slow\')" style="cursor:pointer;float:right"><img src="' . BAWAS_IMAGES_URL . '/close.png" class="bawasinside" /></span></p></div>';
}

function bawas_query_var( &$URI, &$from, &$is_correct, &$get_infos, &$query_args )
{
  $parsed = parse_url( $_SERVER[ 'REQUEST_URI' ] );
  $query_args = $parsed['query'] != '' ? '?' . $parsed['query'] : '';
  $query_args.= $parsed['fragment'] != '' ? '#' . $parsed['fragment'] : '';
  $URIs = explode( '/', substr( $parsed['path'], 1 ) );
  $URI = $URIs[0];
  unset( $URIs[0] );
  $query_args = implode( '/', $URIs ) . $query_args;
  if( get_option( 'baw-as_keepqueryvars' ) != 'on' || strlen( $query_args == 1 ) ) {
    $query_args = '';
  }
  $get_infos = $URI[strlen( $URI )-1] == '+';
  $bad_req = strlen( strstr( $URI, '+' ) ) > 1;
  $URI = str_replace( '+', '', $URI );
  $is_correct = bawas_sanitize_title( $URI ) == $URI;
  if( !$bad_req ){
    $from = $URI;
  }else{
    $from = '';
  }
}

function bawas_get_hostname( $str )
{
  $info = pathinfo( $str );
  $name = $info['filename'];
  if ( strstr( $name, '.' ) != '' ) {
    $name = substr( strstr( $name, '.' ), 1 );
  }
  return esc_attr( $name );
}

function bawas_shortcode( $atts, $content = null )
{
  return wp_get_shortlink();
}
?>