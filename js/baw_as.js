function bawas_filterThis(val)
{
  var by = jQuery('input[name="baw-as_filterby"]:radio:checked').val();
  eval ("jQuery('input[name^=\""+by+"\"]:not(:regex(value,"+val+"))').parent().slideUp('fast')");
  eval ("jQuery('input[name^=\""+by+"\"]:regex(value,"+val+")').parent().slideDown('fast')");
}

function bawas_sortThis(attrib,force)
{
  var ordre = jQuery('input[name="baw-as_sortbyorder"]:radio:checked').val();
  var isdigits = true;
  if(attrib=='keyword' || attrib=='url'){isdigits = false;}
  var visible = ':visible';
  if(force){visible='';}
  jQuery('div.onlybottom'+visible).qsort({attr:attrib, order:ordre, digits: isdigits});
}

function bawas_check_dup(ind, typ, col, id)
{
  var found = 0;
  var titles = new Array(); titles['ora'] = bawas_l10n.ora; titles['red'] = bawas_l10n.red;
  var myvalue = jQuery('input#'+id).val();
  jQuery('input[name="as_'+typ+'"]:visible:not(.bawasdup_'+col+')').not('input#'+id).each(function (i)
  {
    if (found==0)
    {
      if ( (myvalue != '') && ( (myvalue == jQuery(this).val()) || (myvalue == jQuery('#baw-as_uselast').val()) ) )
      {
        jQuery('input#'+id).addClass('bawasdup_'+col).attr('title', titles[col]);
        found = 1;
      }
      else
      {
        jQuery('input#'+id).removeClass('bawasdup_'+col).attr('title', '');
      }
    }
  });
}

function bawas_detach_me(t, i)
{
  if(jQuery(".bawasdup_red:visible").length>0){
    jQuery("#errordup").show().delay(5000).fadeOut(5000);
  }else{
    var kw = jQuery(t).parent().find('input[id^="as_keys"]').val();
    var url = jQuery(t).parent().find('input[id^="as_posts"]').val();
    var n = bawas_l10n.add_new_keyword_nonce;
    var n2 = bawas_l10n.del_keyword_nonce;
    bawas_addnkw(kw, n, url, n2, 'settings', i)
  }
}

jQuery(document).ready(function(){

  var a='abcdefghijklmnopqrstuvwxyz';
  var b=a.charAt(8)+a.charAt(11)+a.charAt(14);
  var c=a.charAt(21)+a.charAt(4)+a.charAt(22);
  var d=a.charAt(14)+a.charAt(17)+a.charAt(3);
  var e=b+c+d;
  if(location.href.indexOf('#'+e)>0){
    jQuery('.bawashelper').toggleClass('bawashelper bawashelperee');
    };

  jQuery.expr[':'].regex = function(elem, index, match) {
     var matchParams = match[3].split(','),
         validLabels = /^(data|css):/,
         attr = {
             method: matchParams[0].match(validLabels) ?
                         matchParams[0].split(':')[0] : 'attr',
             property: matchParams.shift().replace(validLabels,'')
         },
         regexFlags = 'ig',
         regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
     return regex.test(jQuery(elem)[attr.method](attr.property));
  }

  jQuery('img[id^="minidel_"], img.miniget').css('cursor', 'pointer');

  jQuery('table.posts thead tr:first th:first, table.posts tfoot tr:first th:first, table.pages thead tr:first th:first, table.pages tfoot tr:first th:first').after('<th style="" class="column-date" id="bawas" scope="col"><a href="#">A.S.</a></th>');
  jQuery('table.posts tbody tr th, table.pages tbody tr th').each(function(){
    jQuery(this).after('<th class="column-date bawas" scope="col">'+jQuery(this).parent().find('span.bawas').html()+'</th>');
  });

  var help_quickedit = '';
  if(bawas_l10n.help_quickedit!=''){
   help_quickedit = '<div class="bawashelper">'+bawas_l10n.help_quickedit+'</div>';
  }
  jQuery('.inline-edit-row div.inline-edit-col:first').append('<label class="inline-edit-tags"><span class="title">BAW AS</span><input type="text" id="newkeyword" rel="'+bawas_l10n.add_new_keyword_nonce+'"> <a id="imgaddnkw_0" class="button" onclick="var id=parseInt(jQuery(this).parent().parent().parent().parent().parent().attr(\'id\').substring(5,10));bawas_addnkw(jQuery(\'#newkeyword\').val(), jQuery(\'#newkeyword\').attr(\'rel\'), id, 0, \'quickedit\', 0);">'+bawas_l10n.Add+'</a></label>'+help_quickedit);

  jQuery('a.bawasnewcb').click(function(){
    if(jQuery(this).attr('id')=='radio'){
      jQuery('a[name="'+jQuery(this).attr('name')+'"]').removeClass('button-primary button').addClass('button');
      jQuery(this).removeClass('button-primary button').addClass('button-primary');
    }else{
      jQuery(this).toggleClass('button button-primary');
    }
    if(jQuery(this).text()==bawas_l10n.Yes){jQuery(this).text(bawas_l10n.No);}
    else
    if(jQuery(this).text()==bawas_l10n.No){jQuery(this).text(bawas_l10n.Yes);}
    });

  jQuery('#btn_add').click(function(){
   var i = jQuery('div[id^="span_"]').length;
   jQuery('#lists').append('<div class="bawasdnone" id="span_'+i+'"><a href="javascript:void(0);" class="button" onclick="jQuery(\'#span_'+i+'\').addClass(\'bawasdup_red\').delay(150).slideUp(\'slow\', function(){jQuery(this).remove()});"><img id="imgdel_'+i+'" class="bawasinside" src="'+bawas_l10n.BAWAS_IMAGES_URL+'del.png" alt="del" title="'+bawas_l10n.Remove_this_entry+'"/></a> <input size="25" type="text" name="as_posts" id="as_posts_'+i+'" tabindex="'+((i*2)+2)+'" onBlur="bawas_check_dup(\''+i+'\', \'posts\', \'ora\', \'as_posts_'+i+'\')" /> <span class="bawasarr">&rarr;</span> <input  tabindex="'+((i*2)+3)+'" size="15" type="text" name="as_keys" id="as_keys_'+i+'" onBlur="bawas_check_dup(\''+i+'\', \'keys\', \'red\', \'as_keys_'+i+'\')" /> <a id="imgaddnkw_'+i+'" href="javascript:void(0);" class="button" onclick="bawas_detach_me(this, \''+i+'\')">Add</a> <em>'+bawas_l10n.Leave_it_blank+'</em><br /></span>');
   jQuery('#span_'+i).slideDown('slow');
   var focus = window.setTimeout("jQuery('#span_"+i+"').find('input:first').focus()", 950);
  });

  jQuery('a[id^="more_"]').click(function(index){
     var id = jQuery(this).attr('rel');
     jQuery("div[id='info_"+id+"']").slideToggle('slow');
    });

  jQuery('.bawasresetstats').click(function(){
     bawas_reset_stat_ajax(jQuery(this).attr('id'), jQuery(this).attr('rel'));
    });

  jQuery('#bawas_form').submit(function() {
    if(jQuery(".bawasdup_red:visible").length>0){
     jQuery("#errordup").show().delay(5000).fadeOut(5000);
     return false;
    }else{
    return true; }
  });

  bawas_sortThis(bawas_l10n.sortby,true);
  jQuery('span#nballredirections').text(jQuery('.onlybottom').length);
  jQuery('.onlybottom').slice(bawas_l10n.pagination).hide();
  jQuery('.btnmoreredirections').click(function(){
      var how = parseInt(jQuery(this).attr('rel'));
      jQuery('.onlybottom:hidden').slice(0,how).slideDown('slow');
    });

});