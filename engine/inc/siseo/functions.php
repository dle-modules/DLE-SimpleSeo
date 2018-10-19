<?PHP
/*
=====================================================
 Файл: functions.php
-----------------------------------------------------
 Назначение: Основные функции
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

/*
* Функция для вывода полей настроек (в админпанели)
*/
function ss_showRow($title = "", $description = "", $select = "", $field = "")
{
  global $config;
  
  if( version_compare($config['version_id'], '10.2', '<') )
  {
         echo "<tr>
              <td style=\"padding:7px\" class=\"option\"><b>{$title}</b><div class=\"small\">{$description}</div></td>
              <td style=\"padding:7px\" width=\"100\" align=left>{$select}</td>
              <td style=\"padding:7px\" width=\"400\" align=left>{$field}</td>
              </tr>
			  <tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>";
          $bg = ""; 
  }
  else
  {
        echo "<tr>
          <td class=\"col-xs-9 col-sm-6 col-md-7\"><h6>{$title}</h6><span class=\"note large\">{$description}</span></td>
          <td class=\"col-xs-1 col-md-1 settingstd\">{$select}</td>
		  <td class=\"col-xs-2 col-md-4 settingstd\">{$field}</td>
          </tr>";
  }
}

function ss_showOneRow($field = "")
{
  global $config;
  
  if( version_compare($config['version_id'], '10.2', '<') )
  {
         echo "<tr>
              <td colspan=\"3\" style=\"padding:7px\" width=\"100%\" align=left>{$field}</td>
              </tr>
			  <tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>";
          $bg = ""; 
  }
  else
  {
        echo "<tr>
          <td colspan=\"3\" class=\"col-xs-12 col-sm-12 col-md-12\">{$field}</td>
          </tr>";
  }
}

/*
* Функция для создания выпадающего списка
*/
function ss_makeDropDown($options, $name, $selected)
{
  $output = "<select class=\"uniform\" style=\"min-width:100px;cursor:pointer;\" name=\"$name\">\r\n";
  foreach ( $options as $value => $description ) {
          $output .= "<option value=\"$value\"";
          if( $selected == $value ) {
            $output .= " selected ";
          }
          $output .= ">$description</option>\n";
  }
  $output .= "</select>";
  return $output;
}

/*
* Функция возвращает html код открытия таблицы (дизайн панели администратора)
*/
function ss_tableOpen($box = false, $light = false)
{
  global $config;
  
    if($box == "visible")
    {
        $box = " <div class=\"box {$box}\">";
    }
    else if($box)
    {
        $box = " <div class=\"box\">";
    }
    
    if($light){
    
    return <<<HTML
    
     {$box}        
        <table width="100%" border="0" class="table table-normal">
        
HTML;

}
    if( version_compare($config['version_id'], '10.2', '<') )
    {
    
    return <<<HTML
    
     {$box}        
    <table width="100%" border="0" class="table table-normal">
      <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif" colspan=2><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
      </tr>
      <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF" colspan=2>
        <table width="100%" border="0">
HTML;
  }
  else
  {
      return <<<HTML
    
     {$box}        
    <table width="100%" border="0" class="table table-normal">
HTML;
  
  }

}

/*
* Функция возвращает html код закрытия таблицы (дизайн панели администратора)
*/
function ss_tableClose($box = false, $light = false)
{
  global $config;

      if($box)
      {
          $box = "</div>";
      }


    if($light){
    
    return <<<HTML
    
    </table>
    
     {$box}        
        
HTML;

    }
    
    if( version_compare($config['version_id'], '10.2', '<') )
    {
    
      return <<<HTML
      </table>
          </td>
            <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
          </tr>
          <tr>
            <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
            <td background="engine/skins/images/tl_ub.gif" colspan=2><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
            <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
          </tr>
        </table>
    
        {$box}
        
HTML;

    }
    else
    {
          return <<<HTML
        </table>
    
        {$box}
        
HTML;
    
    }

}


/**
* Сохранение параметров модуля
* @param $save_con array
* 		 array: ассоциативный массив параметров
* @return null;
*/				
function save_config($save_con)
{
  global $ss_config;
  
  if(!is_array($ss_config)) {
      $ss_config = array ();
  }
  
  $save_con = array_merge ( $ss_config , $save_con ) ;
  array_to_file($save_con, ENGINE_DIR . '/data/siseo_config.php', $file = 0);
}
		
/*
* Запись массива в файл
*/
function array_to_file($array, $filename = 0, $file = 0)
{
  global $config;
  
  $copy = "/**********************************************************************
 * Simple SEO - легкая seo оптимизация
 **********************************************************************
 * @author       Oleg Budrin <support@mofsy.ru>
 * @copyright    Copyright (c) 2014-2016, Alexander Alaev
 *********************************************************************/";
         
        $level = 1;
        if($file == 0)
        {
          $level = 0;
          $file = fopen($filename, "w");
            
          if(!$file)
          {
            return false;
          }
          
          if( $config['charset'] == 'utf-8')
            fwrite($file, iconv("WINDOWS-1251", "UTF-8", "<" . "?php\n".$copy."\n\n\$ss_config = "));
          else
            fwrite($file, "<" . "?php\n".$copy."\n\n\$ss_config = ");
        }
     
        $cnt = count($array);
        $i = 0;
        fwrite($file, "array(\n\n");
        foreach($array as $key => $value)
        {
          if($i++ != 0)
          {
            fwrite($file, ",\n\n");
          }
          if(is_array($array[$key]))
          {
            fwrite($file, str_repeat(' ', ($level + 1) * 1) ."'$key' => ");
            $this->array_to_file($array[$key], 0, $file);
          }
          else 
          {
            $value = addcslashes($value, "'"."\\\\");

            fwrite($file, str_repeat(' ', ($level + 1) * 2) . "'$key' => '$value'");
          }
        }
        
        fwrite($file, ")");
     
        if($level == 0)
        {
          fwrite($file, ";\n\n?".">");
          fclose($file);
          return true;
        }
}