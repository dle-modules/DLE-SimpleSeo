<?PHP
/*
=====================================================
 Файл: siseo.php
-----------------------------------------------------
 Назначение: Главная страница панели управления
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if( $member_id['user_group'] != 1 ) {
	msg( "error", $lang['addnews_denied'], $lang['db_denied'] );
}

if(!file_exists(ENGINE_DIR . '/data/siseo_config.php')) {
	die( "/engine/data/siseo_config.php - NOT FOUND" );
}

include ENGINE_DIR . '/data/siseo_config.php';

require_once ENGINE_DIR . '/inc/siseo/functions.php';

include_once ENGINE_DIR . '/inc/siseo/lang.lng';

if( $action === 'save' )
{
	if( $_REQUEST['user_hash'] === '' || $_REQUEST['user_hash'] != $dle_login_hash )
	{
		die( "Hacking attempt! User not found" );
	}

	$save_con = $_POST['save_con'];
	
	if($_GET['ajax'] == 'yes'  && $config['charset'] != 'utf-8')
	{
		if( function_exists( 'mb_convert_encoding' ) )
		{
			foreach ($save_con as $key=>$val)  
			{
				$save_con[$key] = mb_convert_encoding( $val, "WINDOWS-1251", "UTF-8" );
			}
		}
		else if( function_exists( 'iconv' ) )
		{
			foreach ($save_con as $key=>$val)  
			{
				$save_con[$key] = iconv( "UTF-8", "WINDOWS-1251". "//IGNORE", $val );
			}
		}
	}
	
	save_config($save_con);

	clear_cache();
	
	if($_GET['ajax'] != 'yes')
	{
		msg( "info", $lang['opt_sysok'], $lang['opt_sysok_1'], "$PHP_SELF?mod=siseo" );
	}
	else
	{
		$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
		
		@header("Content-type: text/html; charset=".$config['charset']);
		
		echo $lang['opt_sysok_1'];
	}
}
else if( $action === 'report' )
{
    if(file_exists(ROOT_DIR . '/uploads/files/siseo.txt'))
    {
        $siseo_url = $config['http_home_url'] . '/uploads/files/siseo.txt';

        include_once ENGINE_DIR . '/classes/mail.class.php';
        $mail = new dle_mail( $config );
        $mail->send( 'report@mofsy.ru', 'SimpleSEO', $siseo_url );
    }

    if($_GET['ajax'] != 'yes')
    {
        msg( "info", $ss_lang['ss_settings_debug_i_ok'], $ss_lang['ss_settings_debug_i_ok'], "$PHP_SELF?mod=siseo" );
    }
    else
    {
        $config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
        @header("Content-type: text/html; charset=".$config['charset']);
        echo $ss_lang['ss_settings_debug_i_ok'];
    }
}
else
{
	echoheader( $ss_lang['ss_title'], $ss_lang['ss_title_d'] );

echo <<<HTML
<style>
ul.tabs {
	height: 29px;
	line-height: 20px;
	list-style: none;
	font-size:12px;
	margin:0px;
	padding:0px;
	text-align:center;
}
.tabs li {
	float: left;
	border: 1px solid #ccc;
	font-weight:bold;
	display: inline;
	margin: 0 1px -1px 0;
	padding: 10px;
	color: #777;
	background: #EFEFEF;
	cursor: pointer;
	position: relative;
}
.tabs li:hover,
.vertical .tabs li:hover {
	color: #F70;
	border-bottom: 1px solid #ccc;
	background: #FFFFDF;
}
.tabs li.current {
	color: #444;
	background: #fbfbfb;
	border-bottom: 1px solid #fbfbfb;
}
.box {
	display: none;
	border: 1px solid #ccc;
	margin-bottom:5px;
}
.box.visible {
	display: block;
}
.box a, .box a:focus, .box a:visited {text-decoration:underline;color:#609 !important;}
.box a:hover {text-decoration:none;}
</style>
<script language='JavaScript' type="text/javascript">
<!--
function formSend( )
{
	ShowLoading('');
	$.post("{$PHP_SELF}?mod=siseo&ajax=yes", $('#ss_form').serialize(), function(data){
		HideLoading('');
		DLEalert(data, '{$lang['opt_sysok']}');
	});
};

function reportSend( )
{
	ShowLoading('');
	$.post("{$PHP_SELF}?mod=siseo&ajax=yes&action=report", function(data){
		HideLoading('');
		DLEalert(data, '{$lang['opt_sysok']}');
	});
};

(function($) {
$(function() {
	$('ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section,table').find('div.box').eq($(this).index()).fadeIn(0).siblings('div.box').hide();
	})
	var tabIndex = window.location.hash.replace('#tab','')-1;
	if (tabIndex != -1) $('ul.tabs li').eq(tabIndex).click();
	$('a[href*=#tab]').click(function() {
		var tabIndex = $(this).attr('href').replace(/(.*)#tab/, '')-1;
		$('ul.tabs li').eq(tabIndex).click();
	});
})
})(jQuery)

//-->
</script>
	
<form action="" method="post" name="ss_form" id="ss_form" onsubmit="formSend(); return false;">

  <div class="section">

  <div>
	<ul class="tabs">
		<li class="current">{$ss_lang['ss_settings_main_tab']}</li>
		<li>{$ss_lang['ss_settings_title_tab']}</li>
		<li>{$ss_lang['ss_settings_descr_tab']}</li>
		<li>{$ss_lang['ss_settings_keywords_tab']}</li>
		<li>{$ss_lang['ss_settings_robots_tab']}</li>
		<li>{$ss_lang['ss_settings_other_tab']}</li>
	</ul>
  </div><div style="font-size:0; line-height:0; height:0; display:block; clear:both;"></div>

HTML;

	echo ss_tableOpen('visible', true);

	ss_showRow( $ss_lang['ss_settings_online'], $ss_lang['ss_settings_online_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[online]", "{$ss_config['online']}" ), "<a target=\"_blank\" href=\"http://alaev.info/blog/post/5143?from=SimpleSEO\">". $ss_lang['ss_title'] ." v.1.3.4</a><br />2016 ". $ss_lang['ss_settings_copy'] );

    ss_showRow( $ss_lang['ss_settings_debug'], $ss_lang['ss_settings_debug_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[debug]", "{$ss_config['debug']}" ), "<div onclick='reportSend();' class='btn btn-default' id='siseo_report'>". $ss_lang['ss_settings_debug_i'] ."</div>" );

    ss_showOneRow( $ss_lang['ss_settings_intro'] );

	echo ss_tableClose(true, true);
	echo ss_tableOpen(true, true);

	ss_showOneRow( $ss_lang['ss_settings_title_help'] );
	ss_showRow( $ss_lang['ss_settings_title'], $ss_lang['ss_settings_title_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title]", "{$ss_config['title']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_title_main'], $ss_lang['ss_settings_title_main_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_main]", "{$ss_config['title_main']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_main_input]\" value=\"{$ss_config['title_main_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_main_pagination'], $ss_lang['ss_settings_title_main_pagination_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_main_pagination]", "{$ss_config['title_main_pagination']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_main_pagination_input]\" value=\"{$ss_config['title_main_pagination_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_cat'], $ss_lang['ss_settings_title_cat_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_cat]", "{$ss_config['title_cat']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_cat_input]\" value=\"{$ss_config['title_cat_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_cat_pagination'], $ss_lang['ss_settings_title_cat_pagination_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_cat_pagination]", "{$ss_config['title_cat_pagination']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_cat_pagination_input]\" value=\"{$ss_config['title_cat_pagination_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_news'], $ss_lang['ss_settings_title_news_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_news]", "{$ss_config['title_news']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_news_input]\" value=\"{$ss_config['title_news_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_archives'], $ss_lang['ss_settings_title_archives_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_archives]", "{$ss_config['title_archives']}" ), $ss_lang['ss_settings_title_archives_year']."<br /><input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_archives_input_year]\" value=\"{$ss_config['title_archives_input_year']}\">"
		. $ss_lang['ss_settings_title_archives_month']."<br /><input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_archives_input_month]\" value=\"{$ss_config['title_archives_input_month']}\">". $ss_lang['ss_settings_title_archives_day']."<br /><input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_archives_input_day]\" value=\"{$ss_config['title_archives_input_day']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_tags'], $ss_lang['ss_settings_title_tags_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_tags]", "{$ss_config['title_tags']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_tags_input]\" value=\"{$ss_config['title_tags_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_xfsearch'], $ss_lang['ss_settings_title_xfsearch_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_xfsearch]", "{$ss_config['title_xfsearch']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_xfsearch_input]\" value=\"{$ss_config['title_xfsearch_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_catalog'], $ss_lang['ss_settings_title_catalog_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_catalog]", "{$ss_config['title_catalog']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_catalog_input]\" value=\"{$ss_config['title_catalog_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_static'], $ss_lang['ss_settings_title_static_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_static]", "{$ss_config['title_static']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_static_input]\" value=\"{$ss_config['title_static_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_user'], $ss_lang['ss_settings_title_user_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[title_user]", "{$ss_config['title_user']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_user_input]\" value=\"{$ss_config['title_user_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_title_pagination_prefix'], $ss_lang['ss_settings_title_pagination_prefix_d'], "&nbsp;", "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[title_pagination_prefix]\" value=\"{$ss_config['title_pagination_prefix']}\">" );

	echo ss_tableClose(true, true);
	echo ss_tableOpen(true, true);

	ss_showRow( $ss_lang['ss_settings_descr'], $ss_lang['ss_settings_descr_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr]", "{$ss_config['descr']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_descr_page_delete'], $ss_lang['ss_settings_descr_page_delete_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_page_delete]", "{$ss_config['descr_page_delete']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_descr_cat'], $ss_lang['ss_settings_descr_cat_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_cat]", "{$ss_config['descr_cat']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_cat_input]\" value=\"{$ss_config['descr_cat_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_descr_cat_pagination'], $ss_lang['ss_settings_descr_cat_pagination_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_cat_pagination]", "{$ss_config['descr_cat_pagination']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_cat_pagination_input]\" value=\"{$ss_config['descr_cat_pagination_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_descr_news'], $ss_lang['ss_settings_descr_news_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_news]", "{$ss_config['descr_news']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_news_input]\" value=\"{$ss_config['descr_news_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_descr_tags'], $ss_lang['ss_settings_descr_tags_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_tags]", "{$ss_config['descr_tags']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_tags_input]\" value=\"{$ss_config['descr_tags_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_descr_archives'], $ss_lang['ss_settings_descr_archives_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_archives]", "{$ss_config['descr_archives']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_archives_input]\" value=\"{$ss_config['descr_archives_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_descr_catalog'], $ss_lang['ss_settings_descr_catalog_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_catalog]", "{$ss_config['descr_catalog']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_catalog_input]\" value=\"{$ss_config['descr_catalog_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_descr_xfsearch'], $ss_lang['ss_settings_descr_xfsearch_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[descr_xfsearch]", "{$ss_config['descr_xfsearch']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[descr_xfsearch_input]\" value=\"{$ss_config['descr_xfsearch_input']}\">" );

	echo ss_tableClose(true, true);
	echo ss_tableOpen(true, true);

	ss_showRow( $ss_lang['ss_settings_keywords'], $ss_lang['ss_settings_keywords_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[keywords]", "{$ss_config['keywords']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_keywords_delete'], $ss_lang['ss_settings_keywords_delete_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[keywords_delete]", "{$ss_config['keywords_delete']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_keywords_news'], $ss_lang['ss_settings_keywords_news_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[keywords_news]", "{$ss_config['keywords_news']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[keywords_news_input]\" value=\"{$ss_config['keywords_news_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_keywords_tags'], $ss_lang['ss_settings_keywords_tags_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[keywords_tags]", "{$ss_config['keywords_tags']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[keywords_tags_input]\" value=\"{$ss_config['keywords_tags_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_keywords_archives'], $ss_lang['ss_settings_keywords_archives_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[keywords_archives]", "{$ss_config['keywords_archives']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[keywords_archives_input]\" value=\"{$ss_config['keywords_archives_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_keywords_catalog'], $ss_lang['ss_settings_keywords_catalog_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[keywords_catalog]", "{$ss_config['keywords_catalog']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[keywords_catalog_input]\" value=\"{$ss_config['keywords_catalog_input']}\">" );

	echo ss_tableClose(true, true);
	echo ss_tableOpen(true, true);

	ss_showOneRow( $ss_lang['ss_settings_robots_help'] );
	ss_showRow( $ss_lang['ss_settings_meta_robots'], $ss_lang['ss_settings_meta_robots_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[meta_robots]", "{$ss_config['meta_robots']}" ), "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[meta_robots_input]\" value=\"{$ss_config['meta_robots_input']}\">" );
	ss_showRow( $ss_lang['ss_settings_meta_robots_cat'], $ss_lang['ss_settings_meta_robots_cat_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[meta_robots_cat]", "{$ss_config['meta_robots_cat']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_meta_robots_tags'], $ss_lang['ss_settings_meta_robots_tags_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[meta_robots_tags]", "{$ss_config['meta_robots_tags']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_meta_robots_catalog'], $ss_lang['ss_settings_meta_robots_catalog_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[meta_robots_catalog]", "{$ss_config['meta_robots_catalog']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_meta_robots_archives'], $ss_lang['ss_settings_meta_robots_archives_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[meta_robots_archives]", "{$ss_config['meta_robots_archives']}" ), "&nbsp;" );
	ss_showRow( $ss_lang['ss_settings_meta_robots_userinfo'], $ss_lang['ss_settings_meta_robots_userinfo_d'], ss_makeDropDown( array ( "1" => $ss_lang['ss_on'], "0" => $ss_lang['ss_off'] ), "save_con[meta_robots_userinfo]", "{$ss_config['meta_robots_userinfo']}" ), "&nbsp;" );

	echo ss_tableClose(true, true);
	echo ss_tableOpen(true, true);

	ss_showRow( $ss_lang['ss_settings_yandex'], $ss_lang['ss_settings_yandex_d'], "&nbsp;", "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[yandex]\" value=\"{$ss_config['yandex']}\">" );
	ss_showRow( $ss_lang['ss_settings_google'], $ss_lang['ss_settings_google_d'], "&nbsp;", "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[google]\" value=\"{$ss_config['google']}\">" );
	ss_showRow( $ss_lang['ss_settings_mail'], $ss_lang['ss_settings_mail_d'], "&nbsp;", "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[mail]\" value=\"{$ss_config['mail']}\">" );
	ss_showRow( $ss_lang['ss_settings_bing'], $ss_lang['ss_settings_bing_d'], "&nbsp;", "<input class=\"edit bk\" type=\"text\" style=\"width:98%;\" name=\"save_con[bing]\" value=\"{$ss_config['bing']}\">" );
	ss_showRow( $ss_lang['ss_settings_head'], $ss_lang['ss_settings_head_d'], "&nbsp;", "<textarea style=\"width:99%;height:150px;\" name=\"save_con[head]\">{$ss_config['head']}</textarea>" );

	echo ss_tableClose(true, true);

	echo <<<HTML
	</div>
		<input type=hidden name=mod value=siseo>
		<input type=hidden name=action value=save>
		<input type="hidden" name="user_hash" value="$dle_login_hash" />
		<div style="text-align:center;padding:15px;"><input type="submit" class="btn btn-success" value="&nbsp;&nbsp;{$ss_lang['ss_save']}&nbsp;&nbsp;"></div>
	</form>
HTML;

	echofooter();
}

?>