<?php

if( !function_exists( 'baw_get_favicon' ) ) {
  function baw_get_favicon( $url, $nocurl=false )
  {
    if( !$nocurl ) {
      $url = baw_get_my_headers( $url );
    }
    $url = parse_url( $url );
    $url = esc_attr( $url['host'] );
    return 'http://www.google.com/s2/favicons?domain=' . $url;
  }
}

if( !function_exists( 'baw_valid_url' ) ) {
  function baw_valid_url( $url )
  {
    $response = wp_remote_head( $bad_url, array( 'timeout' => 1 ) );
    return !is_wp_error( $response );
  }
}

if( !function_exists( 'baw_get_headers_curl' ) ) {
  function baw_get_headers_curl( &$url )
  {
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_HEADER, true );
    curl_setopt( $ch, CURLOPT_NOBODY, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 2 );
    $r = curl_exec( $ch );
    $r = split( "\n", $r );
    return $r;
  }
}

if( !function_exists( 'baw_get_my_headers' ) ) {
  function baw_get_my_headers( &$url )
  {
   $go = 1;
   $i = 1;
   while ( $go )
   {
    $headers = baw_get_headers_curl( $url );
    $go = baw_get_next_location( $headers );
    if ( $go ) {
      $url = trim( $go );
    }
    $i++;
   }
   return $url;
  }
}

if( !function_exists( 'baw_get_next_location' ) ) {
  function baw_get_next_location( $headers )
  {
    $count = count( $headers );
    for ( $i = 0 ; $i < $count ; $i++) {
      if ( strpos( $headers[$i], "ocation:" ) ) {
        $url = substr( $headers[$i], 10 );
        break;
      }
    }
    if ( $url ) {
      return $url;
    }else{
      return 0;
    }
  }      
}

?>