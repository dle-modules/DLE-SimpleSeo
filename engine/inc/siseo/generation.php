<?PHP
/*
=====================================================
 Файл: generation.php
-----------------------------------------------------
 Назначение: Генерация значений метатегов
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

@include ENGINE_DIR . '/data/siseo_config.php';

if($ss_config['online'] == 1)
{
    /**
     * Debug tool
     */
    $siseo_debug = false;
    
    if(array_key_exists('debug', $ss_config) && $ss_config['debug'] === '1' && $member_id['user_group'] == 1 )
    {
        include ENGINE_DIR . '/inc/siseo/logger.php';
        $siseo_logger = new Logger(ROOT_DIR . '/uploads/files/siseo.txt', 100);
        $siseo_debug = true;
        $siseo_logger->addDebug('DLE: ' . $config['version_id']);
        $siseo_logger->addDebug('PHP: ' . phpversion());
    }

	$cstart = 0;

	if (isset( $_GET['cstart']))
	{
		$cstart = (int)$_GET['cstart'];

        if($siseo_debug)
        {
            $siseo_logger->addDebug('Page found:' . $cstart);
        }
	}
	
	if($cstart > 1)
	{
		$ss_config['title_pagination_prefix'] = str_ireplace("%page%", $cstart, $ss_config['title_pagination_prefix']);
	}
	else
	{
		$ss_config['title_pagination_prefix'] = str_ireplace("%page%", '', $ss_config['title_pagination_prefix']);
	}
	
	/**
	 * Title - генерация title
	 */
	if($ss_config['title'] == 1)
	{
        if($siseo_debug)
        {
            $siseo_logger->addDebug('Title generation is enable');
        }

		if($ss_config['title_main'] == 1 && $dle_module === 'main' && $cstart <= 1)
		{
            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title main input: ' . $ss_config['title_main_input']);
            }
            
			$ss_config['title_main_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_main_input']);
			$ss_config['title_main_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_main_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_main_input']) . '</title>', $metatags);

            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title main output: ' . $ss_config['title_main_input']);
            }
        }
		if($ss_config['title_main_pagination'] == 1 && $dle_module === 'main' && $cstart > 1)
		{
            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title main pagination input: ' . $ss_config['title_main_pagination_input']);
            }
            
			$ss_config['title_main_pagination_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_main_pagination_input']);
			$ss_config['title_main_pagination_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_main_pagination_input']);
			$ss_config['title_main_pagination_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['title_main_pagination_input']);
			$metatags = preg_replace( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_main_pagination_input']) . '</title>', $metatags);

            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title main pagination output: ' . $ss_config['title_main_pagination_input']);
            }
        }

		if($ss_config['title_cat'] == 1 && $do === 'cat' && $category != '' && $subaction == '' && $cstart <= 1)
		{
            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title cat input: ' . $ss_config['title_cat_input']);
            }

			$category_id_one = (int)$category_id;

			if(empty($cat_info[$category_id_one]['metatitle']))
            {
                $cat_info[$category_id_one]['metatitle'] = $cat_info[$category_id_one]['name'];
            }
			if(empty($cat_info[$cat_info[$category_id_one]['parentid']]['metatitle']))
            {
                $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'] = $cat_info[$cat_info[$category_id_one]['parentid']]['name'];
            }

            if($siseo_debug)
            {
                $siseo_logger->addDebug('Category data', $cat_info[$category_id_one]);
            }

			$ss_config['title_cat_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_cat_input']);
			$ss_config['title_cat_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_cat_input']);
			$ss_config['title_cat_input'] = str_ireplace("%cat_name%", $cat_info[$category_id_one]['name'], $ss_config['title_cat_input']);
			$ss_config['title_cat_input'] = str_ireplace("%cat_title%", $cat_info[$category_id_one]['metatitle'], $ss_config['title_cat_input']);
			$ss_config['title_cat_input'] = str_ireplace("%par_cat_name%", $cat_info[$cat_info[$category_id_one]['parentid']]['name'], $ss_config['title_cat_input']);
			$ss_config['title_cat_input'] = str_ireplace("%par_cat_title%", $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'], $ss_config['title_cat_input']);
			$metatags = preg_replace( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_cat_input']) . '</title>', $metatags);

            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title cat output: ' . $ss_config['title_cat_input']);
            }
        }
		if($ss_config['title_cat_pagination'] == 1 && $do === "cat" && $category != '' && $subaction == '' && $cstart > 1)
		{
            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title cat pagination input: ' . $ss_config['title_cat_pagination_input']);
            }

			$category_id_one = intval($category_id);

			if(empty($cat_info[$category_id_one]['metatitle']))
            {
                $cat_info[$category_id_one]['metatitle'] = $cat_info[$category_id_one]['name'];
            }
			if(empty($cat_info[$cat_info[$category_id_one]['parentid']]['metatitle']))
            {
                $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'] = $cat_info[$cat_info[$category_id_one]['parentid']]['name'];
            }

			$ss_config['title_cat_pagination_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_cat_pagination_input']);
			$ss_config['title_cat_pagination_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_cat_pagination_input']);
			$ss_config['title_cat_pagination_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['title_cat_pagination_input']);
			$ss_config['title_cat_pagination_input'] = str_ireplace("%cat_name%", $cat_info[$category_id_one]['name'], $ss_config['title_cat_pagination_input']);
			$ss_config['title_cat_pagination_input'] = str_ireplace("%cat_title%", $cat_info[$category_id_one]['metatitle'], $ss_config['title_cat_pagination_input']);
			$ss_config['title_cat_pagination_input'] = str_ireplace("%par_cat_name%", $cat_info[$cat_info[$category_id_one]['parentid']]['name'], $ss_config['title_cat_pagination_input']);
			$ss_config['title_cat_pagination_input'] = str_ireplace("%par_cat_title%", $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'], $ss_config['title_cat_pagination_input']);
			$metatags = preg_replace( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_cat_pagination_input']) . '</title>', $metatags);

            if($siseo_debug)
            {
                $siseo_logger->addDebug('Title cat pagination output: ' . $ss_config['title_cat_pagination_input']);
            }
        }
		if($ss_config['title_news'] == 1 && $dle_module == "showfull")
		{
			$category_id_one = intval($category_id);

			if(empty($cat_info[$category_id_one]['metatitle']))
            {
                $cat_info[$category_id_one]['metatitle'] = $cat_info[$category_id_one]['name'];
            }
			
			$siseo_title = dle_cache('siseo_title', $newsid, false);
			$siseo_xfields = dle_cache('siseo_xfields', $newsid, false);
			
			if(!$siseo_title || !$siseo_xfields)
			{
				$siseo_sql = "SELECT xfields, metatitle FROM " . PREFIX . "_post WHERE id = '{$newsid}'";
				$siseo_row = $db->super_query($siseo_sql);
				
				$siseo_title = $siseo_row['metatitle'];
				$siseo_xfields = $siseo_row['xfields'];
				
				create_cache('siseo_title', $siseo_title, $newsid, false);
				create_cache('siseo_xfields', $siseo_xfields, $newsid, false);
			}
			
			$xfieldsdata = explode( "||", $siseo_xfields );
			foreach ( $xfieldsdata as $xfielddata )
			{
				list ( $xfielddataname, $xfielddatavalue ) = explode( "|", $xfielddata );
				$xfielddataname = str_replace( "&#124;", "|", $xfielddataname );
				$xfielddataname = str_replace( "__NEWL__", "\r\n", $xfielddataname );
				$xfielddatavalue = str_replace( "&#124;", "|", $xfielddatavalue );
				$xfielddatavalue = str_replace( "__NEWL__", "\r\n", $xfielddatavalue );
				
				/**
				 * Если дополнительное поле пустое
				 */
				if(empty($xfielddatavalue))
				{
					$ss_config['title_news_input'] = preg_replace( "'\\[xfgiven_{$xfielddataname}\\](.*?)\\[/xfgiven_{$xfielddataname}\\]'is", "", $ss_config['title_news_input']);
					$ss_config['title_news_input'] = str_replace( "[xfnotgiven_{$xfielddataname}]", "", $ss_config['title_news_input'] );
					$ss_config['title_news_input'] = str_replace( "[/xfnotgiven_{$xfielddataname}]", "", $ss_config['title_news_input'] );
				}
				else 
				{
					$ss_config['title_news_input'] = preg_replace( "'\\[xfnotgiven_{$xfielddataname}\\](.*?)\\[/xfnotgiven_{$xfielddataname}\\]'is", "", $ss_config['title_news_input']);
					$ss_config['title_news_input'] = str_replace( "[xfgiven_{$xfielddataname}]", "", $ss_config['title_news_input'] );
					$ss_config['title_news_input'] = str_replace( "[/xfgiven_{$xfielddataname}]", "", $ss_config['title_news_input'] );
				}
				
				$ss_config['title_news_input'] = str_ireplace("%xfields_".$xfielddataname."%", $xfielddatavalue, $ss_config['title_news_input']);
			}

			$ss_config['title_news_input'] = preg_replace( "'\\[xfgiven_(.*?)\\](.*?)\\[/xfgiven_(.*?)\\]'is", '', $ss_config['title_news_input']);
			$ss_config['title_news_input'] = preg_replace( "'\\[xfnotgiven_(.*?)\\](.*?)\\[/xfnotgiven_(.*?)\\]'is", '', $ss_config['title_news_input']);

			if(empty($siseo_title))
			{
				$siseo_title = $titl_e;
			}

			$ss_config['title_news_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%cat_name%", $cat_info[$category_id_one]['name'], $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%cat_title%", $cat_info[$category_id_one]['metatitle'], $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%news_name%", $titl_e, $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%news_title%", $siseo_title, $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%news_id%", $newsid, $ss_config['title_news_input']);
			$ss_config['title_news_input'] = str_ireplace("%news_date%", langdate( $config['timestamp_active'], $news_date ), $ss_config['title_news_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_news_input']) . '</title>', $metatags); 
		}
		if($ss_config['title_archives'] == 1 && ($year != '' && $month == '' && $day == ''))
		{
			$ss_config['title_archives_input_year'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_archives_input_year']);
			$ss_config['title_archives_input_year'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_archives_input_year']);
			$ss_config['title_archives_input_year'] = str_ireplace("%arch_date%", $year, $ss_config['title_archives_input_year']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_archives_input_year']) . '</title>', $metatags); 
		}
		if($ss_config['title_archives'] == 1 && ($year != '' && $month != '' && $day == ''))
		{
			$ss_config['title_archives_input_month'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_archives_input_month']);
			$ss_config['title_archives_input_month'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_archives_input_month']);
			$ss_config['title_archives_input_month'] = str_ireplace("%arch_date%", ' ' . $r[$month - 1] . ' ' . $year . ' ', $ss_config['title_archives_input_month']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_archives_input_month']) . '</title>', $metatags); 
		}
		if($ss_config['title_archives'] == 1 && ($year != '' && $month != '' && $day != ''))
		{
			$ss_config['title_archives_input_day'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_archives_input_day']);
			$ss_config['title_archives_input_day'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_archives_input_day']);
			$ss_config['title_archives_input_day'] = str_ireplace("%arch_date%", ' ' . $day . '.' . $month . '.' . $year, $ss_config['title_archives_input_day']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_archives_input_day']) . '</title>', $metatags); 
		}
		if($ss_config['title_tags'] == 1 && $do == "tags")
		{
			$ss_config['title_tags_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_tags_input']);
			$ss_config['title_tags_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_tags_input']);
			$ss_config['title_tags_input'] = str_ireplace("%tag_name%", $tag, $ss_config['title_tags_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_tags_input']) . '</title>', $metatags); 
		}
		if($ss_config['title_catalog'] == 1 && $catalog != "")
		{
			$ss_config['title_catalog_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_catalog_input']);
			$ss_config['title_catalog_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_catalog_input']);
			$ss_config['title_catalog_input'] = str_ireplace("%symb_name%", $catalog, $ss_config['title_catalog_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_catalog_input']) . '</title>', $metatags); 
		}
		if($ss_config['title_static'] == 1 && $do == "static")
		{
			if(empty($static_result['metatitle']))
            {
                $static_result['metatitle'] = $static_descr;
            }
			
			$ss_config['title_static_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_static_input']);
			$ss_config['title_static_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_static_input']);
			$ss_config['title_static_input'] = str_ireplace("%static_name%", $static_descr, $ss_config['title_static_input']);
			$ss_config['title_static_input'] = str_ireplace("%static_title%", $static_result['metatitle'], $ss_config['title_static_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_static_input']) . '</title>', $metatags); 
		}
		if($ss_config['title_user'] == 1 && $subaction == 'userinfo')
		{
			$ss_config['title_user_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_user_input']);
			$ss_config['title_user_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_user_input']);
			$ss_config['title_user_input'] = str_ireplace("%username%", $user, $ss_config['title_user_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_user_input']) . '</title>', $metatags); 
		}
		if($ss_config['title_xfsearch'] == 1 && $do == "xfsearch")
		{
			$xf = urldecode ( $_GET['xf'] );

			if ( $config['charset'] == "windows-1251" AND $config['charset'] != detect_encoding($xf) )
            {
				if( function_exists( 'mb_convert_encoding' ) )
                {
					$xf = mb_convert_encoding( $xf, "windows-1251", "UTF-8" );
				}
                elseif( function_exists( 'iconv' ) )
                {
					$xf = iconv( "UTF-8", "windows-1251//IGNORE", $xf );
				}
			}

			$xf = @$db->safesql ( htmlspecialchars ( strip_tags ( stripslashes ( trim ( $xf ) ) ), ENT_QUOTES, $config['charset'] ) );

			$siseo_xfields = dle_cache('siseo_xfields', $xf, false);
			
			if(!$siseo_xfields)
			{
				$siseo_sql = "SELECT xfields FROM " . PREFIX . "_post WHERE xfields LIKE '%{$xf}%'";
				$siseo_row = $db->super_query($siseo_sql);
				
				$siseo_xfields = $siseo_row['xfields'];
				
				create_cache('siseo_xfields', $siseo_xfields, $xf, false);
			}
			
			$xfieldsdata = explode( "||", $siseo_xfields );
			foreach ( $xfieldsdata as $xfielddata )
			{
				list ( $xfielddataname, $xfielddatavalue ) = explode( "|", $xfielddata );
				$xfielddataname = str_replace( "&#124;", "|", $xfielddataname );
				$xfielddataname = str_replace( "__NEWL__", "\r\n", $xfielddataname );
				$xfielddatavalue = str_replace( "&#124;", "|", $xfielddatavalue );
				$xfielddatavalue = str_replace( "__NEWL__", "\r\n", $xfielddatavalue );
				
				/**
				 * Если дополнительное поле пустое
				 */
				if(empty($xfielddatavalue))
				{
					$ss_config['title_xfsearch_input'] = preg_replace( "'\\[xfgiven_{$xfielddataname}\\](.*?)\\[/xfgiven_{$xfielddataname}\\]'is", "", $ss_config['title_xfsearch_input']);
					$ss_config['title_xfsearch_input'] = str_replace( "[xfnotgiven_{$xfielddataname}]", "", $ss_config['title_xfsearch_input'] );
					$ss_config['title_xfsearch_input'] = str_replace( "[/xfnotgiven_{$xfielddataname}]", "", $ss_config['title_xfsearch_input'] );
				}
				else 
				{
					$ss_config['title_xfsearch_input'] = preg_replace( "'\\[xfnotgiven_{$xfielddataname}\\](.*?)\\[/xfnotgiven_{$xfielddataname}\\]'is", "", $ss_config['title_xfsearch_input']);
					$ss_config['title_xfsearch_input'] = str_replace( "[xfgiven_{$xfielddataname}]", "", $ss_config['title_xfsearch_input'] );
					$ss_config['title_xfsearch_input'] = str_replace( "[/xfgiven_{$xfielddataname}]", "", $ss_config['title_xfsearch_input'] );
				}
				
				$ss_config['title_xfsearch_input'] = str_ireplace("%xfields_".$xfielddataname."%", $xfielddatavalue, $ss_config['title_xfsearch_input']);
			}

			$ss_config['title_xfsearch_input'] = preg_replace( "'\\[xfgiven_(.*?)\\](.*?)\\[/xfgiven_(.*?)\\]'is", '', $ss_config['title_xfsearch_input']);
			$ss_config['title_xfsearch_input'] = preg_replace( "'\\[xfnotgiven_(.*?)\\](.*?)\\[/xfnotgiven_(.*?)\\]'is", '', $ss_config['title_xfsearch_input']);

			$ss_config['title_xfsearch_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['title_xfsearch_input']);
			$ss_config['title_xfsearch_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['title_xfsearch_input']);
			$ss_config['title_xfsearch_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['title_xfsearch_input']);
			$metatags = preg_replace ( "/<title>(.*)<\/title>/i", '<title>' . strip_tags($ss_config['title_xfsearch_input']) . '</title>', $metatags); 
		}
	}
	
	/**
	 * Description - генерация описания
	 */
	if($ss_config['descr'] == 1)
	{
		if($ss_config['descr_page_delete'] == 1 && $cstart > 1)
		{
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '', $metatags);
		}
		if($ss_config['descr_news'] == 1 && $dle_module == "showfull")
		{
			$ss_config['descr_news_input'] = str_ireplace("%news_name%", $titl_e, $ss_config['descr_news_input']);
			$ss_config['descr_news_input'] = str_ireplace("%news_date%", langdate( $config['timestamp_active'], $news_date ), $ss_config['descr_news_input']);

			$siseo_xfields = dle_cache('siseo_xfields', $newsid, false);

			if(!$siseo_xfields)
			{
				$siseo_sql = "SELECT xfields FROM " . PREFIX . "_post WHERE id = '{$newsid}'";
				$siseo_row = $db->super_query($siseo_sql);

				$siseo_xfields = $siseo_row['xfields'];

				create_cache('siseo_xfields', $siseo_xfields, $newsid, false);
			}

			$xfieldsdata = explode( "||", $siseo_xfields );
			foreach ( $xfieldsdata as $xfielddata )
			{
				list ( $xfielddataname, $xfielddatavalue ) = explode( "|", $xfielddata );
				$xfielddataname = str_replace( "&#124;", "|", $xfielddataname );
				$xfielddataname = str_replace( "__NEWL__", "\r\n", $xfielddataname );
				$xfielddatavalue = str_replace( "&#124;", "|", $xfielddatavalue );
				$xfielddatavalue = str_replace( "__NEWL__", "\r\n", $xfielddatavalue );

				/*
				 * Если дополнительное поле пустое
				 */
				if(empty($xfielddatavalue))
				{
					$ss_config['descr_news_input'] = preg_replace( "'\\[xfgiven_{$xfielddataname}\\](.*?)\\[/xfgiven_{$xfielddataname}\\]'is", "", $ss_config['descr_news_input']);
					$ss_config['descr_news_input'] = str_replace( "[xfnotgiven_{$xfielddataname}]", "", $ss_config['descr_news_input'] );
					$ss_config['descr_news_input'] = str_replace( "[/xfnotgiven_{$xfielddataname}]", "", $ss_config['descr_news_input'] );
				}
				else
				{
					$ss_config['descr_news_input'] = preg_replace( "'\\[xfnotgiven_{$xfielddataname}\\](.*?)\\[/xfnotgiven_{$xfielddataname}\\]'is", "", $ss_config['descr_news_input']);
					$ss_config['descr_news_input'] = str_replace( "[xfgiven_{$xfielddataname}]", "", $ss_config['descr_news_input'] );
					$ss_config['descr_news_input'] = str_replace( "[/xfgiven_{$xfielddataname}]", "", $ss_config['descr_news_input'] );
				}

				$ss_config['descr_news_input'] = str_ireplace("%xfields_".$xfielddataname."%", $xfielddatavalue, $ss_config['descr_news_input']);
			}

			$ss_config['descr_news_input'] = preg_replace( "'\\[xfgiven_(.*?)\\](.*?)\\[/xfgiven_(.*?)\\]'is", '', $ss_config['descr_news_input']);
			$ss_config['descr_news_input'] = preg_replace( "'\\[xfnotgiven_(.*?)\\](.*?)\\[/xfnotgiven_(.*?)\\]'is", '', $ss_config['descr_news_input']);


			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_news_input']) . '" />', $metatags);

		}
		if($ss_config['descr_tags'] == 1 && $do == "tags")
		{
			$ss_config['descr_tags_input'] = str_ireplace("%tag_name%", $tag, $ss_config['descr_tags_input']);
			$ss_config['descr_tags_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['descr_tags_input']);
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_tags_input']) . '" />', $metatags); 
		}
		if($ss_config['descr_archives'] == 1 && ($year != '' || $month != '' || $day != ''))
		{
			if ($year != '' and $month == '' and $day == '') $ss_date = ' ' . $year . ' ';
			if ($year != '' and $month != '' and $day == '') $ss_date = ' ' . $r[$month - 1] . ' ' . $year . ' ';
			if ($year != '' and $month != '' and $day != '' and $subaction == '') $ss_date = ' ' . $day . '.' . $month . '.' . $year;
			$ss_config['descr_archives_input'] = str_ireplace("%arch_date%", $ss_date, $ss_config['descr_archives_input']);
			$ss_config['descr_archives_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['descr_archives_input']);
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_archives_input']) . '" />', $metatags); 
		}
		if($ss_config['descr_catalog'] == 1 && $catalog != "")
		{
			$ss_config['descr_catalog_input'] = str_ireplace("%symb_name%", $catalog, $ss_config['descr_catalog_input']);
			$ss_config['descr_catalog_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['descr_catalog_input']);
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_catalog_input']) . '" />', $metatags); 
		}
		if($ss_config['descr_cat'] == 1 && $do == "cat" && $category != '' && $subaction == '' && $cstart <= 1)
		{
			$category_id_one = intval($category_id);

			if(empty($cat_info[$category_id_one]['metatitle']))
            {
                $cat_info[$category_id_one]['metatitle'] = $cat_info[$category_id_one]['name'];
            }
			if(empty($cat_info[$cat_info[$category_id_one]['parentid']]['metatitle']))
            {
                $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'] = $cat_info[$cat_info[$category_id_one]['parentid']]['name'];
            }
			
			$ss_config['descr_cat_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['descr_cat_input']);
			$ss_config['descr_cat_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['descr_cat_input']);
			$ss_config['descr_cat_input'] = str_ireplace("%cat_name%", $cat_info[$category_id_one]['name'], $ss_config['descr_cat_input']);
			$ss_config['descr_cat_input'] = str_ireplace("%cat_title%", $cat_info[$category_id_one]['metatitle'], $ss_config['descr_cat_input']);
			$ss_config['descr_cat_input'] = str_ireplace("%par_cat_name%", $cat_info[$cat_info[$category_id_one]['parentid']]['name'], $ss_config['descr_cat_input']);
			$ss_config['descr_cat_input'] = str_ireplace("%par_cat_title%", $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'], $ss_config['descr_cat_input']);
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_cat_input']) . '" />', $metatags); 
		}
		if($ss_config['descr_cat_pagination'] == 1 && $do == "cat" && $category != '' && $subaction == '' && $cstart > 1)
		{
			$category_id_one = intval($category_id);

			if(empty($cat_info[$category_id_one]['metatitle']))
            {
                $cat_info[$category_id_one]['metatitle'] = $cat_info[$category_id_one]['name'];
            }
			if(empty($cat_info[$cat_info[$category_id_one]['parentid']]['metatitle']))
            {
                $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'] = $cat_info[$cat_info[$category_id_one]['parentid']]['name'];
            }
			
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['descr_cat_pagination_input']);
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['descr_cat_pagination_input']);
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['descr_cat_pagination_input']);
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%cat_name%", $cat_info[$category_id_one]['name'], $ss_config['descr_cat_pagination_input']);
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%cat_title%", $cat_info[$category_id_one]['metatitle'], $ss_config['descr_cat_pagination_input']);
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%par_cat_name%", $cat_info[$cat_info[$category_id_one]['parentid']]['name'], $ss_config['descr_cat_pagination_input']);
			$ss_config['descr_cat_pagination_input'] = str_ireplace("%par_cat_title%", $cat_info[$cat_info[$category_id_one]['parentid']]['metatitle'], $ss_config['descr_cat_pagination_input']);
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_cat_pagination_input']) . '" />', $metatags); 
		}
		if($ss_config['descr_xfsearch'] == 1 && $do == "xfsearch")
		{
			$xf = urldecode ( $_GET['xf'] );

			if ( $config['charset'] == "windows-1251" AND $config['charset'] != detect_encoding($xf) ) {

				if( function_exists( 'mb_convert_encoding' ) ) {
			
					$xf = mb_convert_encoding( $xf, "windows-1251", "UTF-8" );
			
				} elseif( function_exists( 'iconv' ) ) {
				
					$xf = iconv( "UTF-8", "windows-1251//IGNORE", $xf );
				
				}

			}

			$xf = @$db->safesql ( htmlspecialchars ( strip_tags ( stripslashes ( trim ( $xf ) ) ), ENT_QUOTES, $config['charset'] ) );

			$siseo_xfields = dle_cache('siseo_xfields', $xf, false);
			
			if(!$siseo_xfields)
			{
				$siseo_sql = "SELECT xfields FROM " . PREFIX . "_post WHERE xfields LIKE '%{$xf}%'";
				$siseo_row = $db->super_query($siseo_sql);
				
				$siseo_xfields = $siseo_row['xfields'];
				
				create_cache('siseo_xfields', $siseo_xfields, $xf, false);
			}
			
			$xfieldsdata = explode( "||", $siseo_xfields );
			foreach ( $xfieldsdata as $xfielddata )
			{
				list ( $xfielddataname, $xfielddatavalue ) = explode( "|", $xfielddata );
				$xfielddataname = str_replace( "&#124;", "|", $xfielddataname );
				$xfielddataname = str_replace( "__NEWL__", "\r\n", $xfielddataname );
				$xfielddatavalue = str_replace( "&#124;", "|", $xfielddatavalue );
				$xfielddatavalue = str_replace( "__NEWL__", "\r\n", $xfielddatavalue );
				
				/*
				 * Если дополнительное поле пустое
				 */
				if(empty($xfielddatavalue))
				{
					$ss_config['descr_xfsearch_input'] = preg_replace( "'\\[xfgiven_{$xfielddataname}\\](.*?)\\[/xfgiven_{$xfielddataname}\\]'is", "", $ss_config['descr_xfsearch_input']);
					$ss_config['descr_xfsearch_input'] = str_replace( "[xfnotgiven_{$xfielddataname}]", "", $ss_config['descr_xfsearch_input'] );
					$ss_config['descr_xfsearch_input'] = str_replace( "[/xfnotgiven_{$xfielddataname}]", "", $ss_config['descr_xfsearch_input'] );
				}
				else
				{
					$ss_config['descr_xfsearch_input'] = preg_replace( "'\\[xfnotgiven_{$xfielddataname}\\](.*?)\\[/xfnotgiven_{$xfielddataname}\\]'is", "", $ss_config['descr_xfsearch_input']);
					$ss_config['descr_xfsearch_input'] = str_replace( "[xfgiven_{$xfielddataname}]", "", $ss_config['descr_xfsearch_input'] );
					$ss_config['descr_xfsearch_input'] = str_replace( "[/xfgiven_{$xfielddataname}]", "", $ss_config['descr_xfsearch_input'] );
				}
				
				$ss_config['descr_xfsearch_input'] = str_ireplace("%xfields_".$xfielddataname."%", $xfielddatavalue, $ss_config['descr_xfsearch_input']);
			}

			$ss_config['descr_xfsearch_input'] = preg_replace( "'\\[xfgiven_(.*?)\\](.*?)\\[/xfgiven_(.*?)\\]'is", '', $ss_config['descr_xfsearch_input']);
			$ss_config['descr_xfsearch_input'] = preg_replace( "'\\[xfnotgiven_(.*?)\\](.*?)\\[/xfnotgiven_(.*?)\\]'is", '', $ss_config['descr_xfsearch_input']);

			$ss_config['descr_xfsearch_input'] = str_ireplace("%site_title%", $config['home_title'], $ss_config['descr_xfsearch_input']);
			$ss_config['descr_xfsearch_input'] = str_ireplace("%site_name%", $config['short_title'], $ss_config['descr_xfsearch_input']);
			$ss_config['descr_xfsearch_input'] = str_ireplace("%page%", $ss_config['title_pagination_prefix'], $ss_config['descr_xfsearch_input']);
			$metatags = preg_replace ( "/<meta name=\"description\" content=\"(.*)\" \/>/i", '<meta name="description" content="' . strip_tags($ss_config['descr_xfsearch_input']) . '" />', $metatags); 
		}

	}
	
	/**
	 * Keywords - генерация ключевых слов
	 */
	if($ss_config['keywords'] == 1 && $ss_config['keywords_delete'] == 0)
	{
		if($ss_config['keywords_news'] == 1 && $dle_module == "showfull")
		{
			$ss_config['keywords_news_input'] = str_ireplace("%news_name%", $titl_e, $ss_config['keywords_news_input']);
			$metatags = preg_replace ( "/<meta name=\"keywords\" content=\"(.*)\" \/>/i", '<meta name="keywords" content="' . strip_tags($ss_config['keywords_news_input']) . '" />', $metatags); 
		}
		if($ss_config['keywords_tags'] == 1 && $do == "tags")
		{
			$ss_config['keywords_tags_input'] = str_ireplace("%tag_name%", $tag, $ss_config['keywords_tags_input']);
			$metatags = preg_replace ( "/<meta name=\"keywords\" content=\"(.*)\" \/>/i", '<meta name="keywords" content="' . strip_tags($ss_config['keywords_tags_input']) . '" />', $metatags); 
		}
		if($ss_config['keywords_archives'] == 1 && ($year != '' || $month != '' || $day != ''))
		{
			if ($year != '' and $month == '' and $day == '') $ss_date = ' ' . $year . ' ';
			if ($year != '' and $month != '' and $day == '') $ss_date = ' ' . $r[$month - 1] . ' ' . $year . ' ';
			if ($year != '' and $month != '' and $day != '' and $subaction == '') $ss_date = ' ' . $day . '.' . $month . '.' . $year;
			$ss_config['keywords_archives_input'] = str_ireplace("%arch_date%", $ss_date, $ss_config['keywords_archives_input']);
			$metatags = preg_replace ( "/<meta name=\"keywords\" content=\"(.*)\" \/>/i", '<meta name="keywords" content="' . strip_tags($ss_config['keywords_archives_input']) . '" />', $metatags); 
		}
		if($ss_config['keywords_catalog'] == 1 && $catalog != "")
		{
			$ss_config['keywords_catalog_input'] = str_ireplace("%symb_name%", $catalog, $ss_config['keywords_catalog_input']);
			$metatags = preg_replace ( "/<meta name=\"keywords\" content=\"(.*)\" \/>/i", '<meta name="keywords" content="' . strip_tags($ss_config['keywords_catalog_input']) . '" />', $metatags); 
		}
	}
	
	if($ss_config['keywords_delete'] == 1)
	{
		$metatags = preg_replace ( "/<meta name=\"keywords\" content=\"(.*)\" \/>/i", '', $metatags); 
	}
	
	/**
	 * Meta Robots - прописывает тег в head <meta name="robots" content="%макрос%" />
	 */
	if($ss_config['meta_robots'] == 1)
	{
        if($siseo_debug)
        {
            $siseo_logger->addDebug('Meta robots is enable');
        }

		$ss_config['meta_robots_input'] = '<meta name="robots" content="' . $ss_config['meta_robots_input'] . '" />';
		if($ss_config['meta_robots_userinfo'] == 1 && $subaction == 'userinfo')
		{
			$metatags .= "\n" . $ss_config['meta_robots_input'];
		}
		if($ss_config['meta_robots_cat'] == 1 && $do == 'cat' && $category != '' && $subaction == '')
		{
			$metatags .= "\n" . $ss_config['meta_robots_input'];
		}
		if($ss_config['meta_robots_tags'] == 1 && $do == 'tags')
		{
			$metatags .= "\n" . $ss_config['meta_robots_input'];
		}
		if($ss_config['meta_robots_catalog'] == 1 && $catalog != '')
		{
			$metatags .= "\n" . $ss_config['meta_robots_input'];
		}
		if($ss_config['meta_robots_archives'] == 1 && ($year != '' || $month != '' || $day != ''))
		{
			$metatags .= "\n" . $ss_config['meta_robots_input'];
		}

        if($siseo_debug)
        {
            $siseo_logger->addDebug('Meta robots final data', $metatags);
        }
	}
	
	/**
	 * Панели вебмастеров и произвольные теги
	 */
	if(!empty($ss_config['yandex'])){
		$metatags .= "\n" . $ss_config['yandex'];
	}
	if(!empty($ss_config['google'])){
		$metatags .= "\n" . $ss_config['google'];
	}
	if(!empty($ss_config['mail'])){
		$metatags .= "\n" . $ss_config['mail'];
	}
	if(!empty($ss_config['bing'])){
		$metatags .= "\n" . $ss_config['bing'];
	}
	if(!empty($ss_config['head'])){
		$metatags .= "\n" . $ss_config['head'];
	}
}
