<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @copyright  2005-2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_ltx2pdf_jurl($tpl,$selector,$params=array())
{
	$url=jUrl::get($selector,$params,0);
	echo str_replace(array('#','$','%','^','&','_','{','}','~'),array('\\#','\\$','\\%','\\^','\\&','\\_','\\{','\\}','\\~'),str_replace('\\','\\textbackslash',$url));
}
