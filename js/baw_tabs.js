jQuery( document ).ready( function() {

  jQuery( 'a[class^="bawtab"]' ).click( function() {
     jQuery( 'input#whichtab' ).val( jQuery( this ).attr( 'rel' ) );
     jQuery( 'div.icon32[id^="icon-"]' ).attr( 'id', jQuery( this ).attr( 'value' ) );
   });
  jQuery( 'table.bawas.form-table tr:odd' ).css( 'background-color', baw_tabs_l10n.oddcolor );
  jQuery( 'table.bawas.form-table td' ).css( 'vertical-align', 'middle' );
  var ind_d = parseInt( baw_tabs_l10n.currenttab );
  jQuery( 'div.bawtab ul.bawtabs li[class^="bawtab"] a' ).eq( ind_d ).addClass( 'bawtabcurrent' );
  jQuery( 'div.icon32[id^="icon-"]' ).attr( 'id', jQuery( 'a.bawtabcurrent' ).attr( 'value' ) );
  jQuery( 'div.bawtab div[class^="bawtab"]' ).hide();
  jQuery( 'div.bawtab div[class^="bawtab"]' ).eq( ind_d ).show();
  jQuery( 'div.bawtab ul li a:not(.notme)' ).not( '.bawasnewcb' ).click( function() {
    var theClass = jQuery( this ).attr( 'class' ).slice( 0,8 );
  	jQuery( 'div.bawtab div[class^="bawtab"]:visible:not(.'+theClass+')' ).fadeOut( 'fast', function() {
      	jQuery( 'div.bawtab div[class^="bawtab"]:hidden.'+theClass ).fadeIn( 'fast' );
      });
  	jQuery( 'div.bawtab ul.bawtabs li a' ).removeClass( 'bawtabcurrent' );
  	jQuery( this ).addClass( 'bawtabcurrent' );
	});

});