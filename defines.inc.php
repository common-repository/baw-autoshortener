<?php
DEFINE( 'BAWAS_VERSION', '1.0.2');
DEFINE( 'BAWAS_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );
DEFINE( 'BAWAS_IMAGES_URL', BAWAS_PLUGIN_URL . '/images/' );
if ( get_option( 'baw-as_url' ) != '' ) {
  DEFINE( 'BAWAS_DEFURL', esc_url_raw( untrailingslashit( get_option( 'baw-as_url' ) ) ) );
}else{
  DEFINE( 'BAWAS_DEFURL', site_url() );
}
DEFINE( 'BAWAS_MODE_SETTINGS', 0 );
DEFINE( 'BAWAS_MODE_METABOX', 1 );
DEFINE( 'BAWAS_MODE_INFOS', 2 );
DEFINE( 'BAWAS_FULLNAME', 'BAW AutoShortener' );
DEFINE( 'BAWAS_POST_TYPE', 'bawasposttype' );
$oddcolor = 'FFFFFF';
?>