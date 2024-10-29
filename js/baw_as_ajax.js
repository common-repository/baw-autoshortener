function bawas_addnkw(val, n, id, n2, area, i)
{
  var d = {
		action: 'bawas_add_new_keyword',
		word: val,
		postID: id,
		nonce: n,
		area: area
 	 };
   jQuery('#imgaddnkw_'+i).html('<img class="bawasinside" src="'+bawas_ajax_l10n.BAWAS_IMAGES_URL+'ajax.gif" />');
	 jQuery.post(ajaxurl, d, function(r) {
      jQuery('#imgaddnkw_'+i).text(bawas_ajax_l10n.Add);
      if(r.ok==1)
      {
        if(n2!=0)
        {
          if(area=='settings'){
            var t = jQuery('div[id="span_'+i+'"]');
            jQuery(t).slideUp('fast', function(){
              jQuery(t).detach().appendTo('.dndcont')
               .slideDown('fast');
              jQuery(t).addClass('onlybottom');
              jQuery(t).attr('keyword', r.word);
              jQuery(t).attr('date', r.ID);
              jQuery(t).attr('clic', '0');
              jQuery(t).find('em').remove();
              jQuery(t).find('input[id="as_keys_'+i+'"]').val(r.word);
              jQuery(t).find('input').attr('readonly', 'true');
              jQuery(t).find('input').each(function(){jQuery(this).attr('title', jQuery(this).val());});
              jQuery(t).find('br').remove();
              jQuery(t).find('a:last').remove();
              jQuery(t).find('input:not(:first)').after(r.img);
              jQuery(t).find('a:first').attr('onclick', 'javascript:void();return true;').click(function(){
                bawas_delkw(val, n2, r.ID, i, 'settings');
                });
              jQuery('<span class="bawasbefstats"><a class="button" href="javascript:void(0)"><img alt="stat" src="'+bawas_l10n.BAWAS_IMAGES_URL+'stat.png" class="bawasinside"> 0 clic</a></span> <em>'+bawas_l10n.reload_view_stats+'</em>').appendTo(jQuery(t));
            });
          }else{
            var nboldkw = jQuery('div[id^="oldkeyword_"]').length+1;
            jQuery('#imgaddnkw_'+i).after('<span><img src="'+bawas_ajax_l10n.BAWAS_IMAGES_URL+'valid.png" class="bawasinside" alt="valid" /> '+r.word+'</span>').next('span').delay(1000).fadeOut(3000);
            jQuery('div[id^="oldkeyword_"]:last').after(jQuery('div[id^="oldkeyword_"]:last').clone());
            jQuery('div[id^="oldkeyword_"]:last').hide().slideDown('slow').attr('id', 'oldkeyword_'+nboldkw)
              .find('a').attr('href', bawas_ajax_l10n.miniurl+'/'+r.word).text(bawas_ajax_l10n.miniurl+'/'+r.word)
              .parent().find('img:last').attr('id', 'minidel_'+nboldkw).attr('onclick', 'javascript:void();return true;').css('display', 'inline').click(function(){
                bawas_delkw(val, n2, id, nboldkw);
                });
          }
        }else{
         jQuery('#imgaddnkw_'+i).after('<span><img src="'+bawas_ajax_l10n.BAWAS_IMAGES_URL+'valid.png" class="bawasinside" alt="valid" /> '+r.word+'</span>').next('span').delay(1000).fadeOut(3000);
        }
      }
      else
      {
        if(n2!=0)
        {
         jQuery('#imgaddnkw_'+i).after('<span><img src="'+bawas_ajax_l10n.BAWAS_IMAGES_URL+'del.png" class="bawasinside" alt="not valid" /> '+val+'</span>').next('span').delay(1000).fadeOut(3000);
        }
        else{
         jQuery('#imgaddnkw_'+i).after('<span><img src="'+bawas_ajax_l10n.BAWAS_IMAGES_URL+'del.png" class="bawasinside" alt="not valid" /> '+val+'</span>').next('span').delay(1000).fadeOut(3000);
        }
      }
      jQuery('input#newkeyword').val('');
	 });
}

function bawas_delkw(val, n, id, num, area)
{
  if (val.replace(/^\s+/g, '').replace(/\s+$/g, '') != '') {
    var d = {
      action: 'bawas_del_keyword',
      word: val,
      postID: id,
      nonce: n,
      area: area
    };
    if (area == 'settings') {
      jQuery('img#imgdel_' + num).attr('src', bawas_l10n.BAWAS_IMAGES_URL + 'ajax.gif');
    } else {
      jQuery('img#minidel_' + num).attr('src', bawas_l10n.BAWAS_IMAGES_URL + 'miniajax.gif').attr('title', '').css('cursor', 'default');
    }
    jQuery.post(ajaxurl, d, function (r) {
      if (area == 'settings') {
        if (r.ok != 1) {
          jQuery('img#imgdel_' + num).attr('src', bawas_l10n.BAWAS_IMAGES_URL + 'del.png');
        } else {
          jQuery('#span_' + num).css({'background': '#ffa0a0', 'border-color': '#ff0000'}).delay(150).slideUp('slow', function(){
            jQuery(this).remove()
          });
        }
      } else {
        if (r.ok != 1) {
          jQuery('img#minidel_' + num).attr('src', bawas_l10n.BAWAS_IMAGES_URL + 'minidel.png').attr('title', bawas_l10n.Remove_this_entry).css('cursor', 'pointer').next('span:first').html('<span class="bawasdup_red">' + r.ok + '</span>');
        } else {
          jQuery('div#oldkeyword_' + num).hide('slow', function () {
            jQuery('div#oldkeyword_' + num).remove();
          });
        }
      }
    });
  }
}

function bawas_reset_stat_ajax(x, n)
{
    var d = {
  		action: 'bawas_reset_stats',
  		postID: x,
  		nonce: n
   	 };
   	if(confirm(bawas_ajax_l10n.Are_you_sure)){
         jQuery("img#clic_"+x).attr('src', bawas_ajax_l10n.BAWAS_IMAGES_URL+'ajax.gif');
  	 jQuery.post(ajaxurl, d, function(r) {
          if(r==0){
            jQuery("img#clic_"+x).parent().html('<img class="bawasinside" id="clic_'+x+'" src="'+bawas_ajax_l10n.BAWAS_IMAGES_URL+'stat.png" alt="stat" /> 0 '+bawas_ajax_l10n.CLIC).click().unbind('click');
          }
          jQuery("img#clic_"+x).attr('src', bawas_ajax_l10n.BAWAS_IMAGES_URL+'stat.png');
  	 });
    }
}