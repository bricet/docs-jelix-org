<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @copyright   2009-2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerModuleInfos{
	public $name;
	public $access;
	public $dbProfile;
	public $isInstalled;
	public $version;
	public $parameters=array();
	public $skipInstaller=false;
	function __construct($name,$config){
		$this->name=$name;
		$this->access=$config[$name.'.access'];
		$this->dbProfile=$config[$name.'.dbprofile'];
		$this->isInstalled=$config[$name.'.installed'];
		$this->version=$config[$name.'.version'];
		if(isset($config[$name.'.installparam'])){
			$params=explode(';',$config[$name.'.installparam']);
			foreach($params as $param){
				$kp=explode("=",$param);
				if(count($kp)> 1)
					$this->parameters[$kp[0]]=$kp[1];
				else
					$this->parameters[$kp[0]]=true;
			}
		}
		if(isset($config[$name.'.skipinstaller'])&&$config[$name.'.skipinstaller']=='skip'){
			$this->skipInstaller=true;
		}
	}
	function serializeParameters(){
		$p='';
		foreach($this->parameters as $name=>$v){
			if($v===true||$v=='')
				$p.=';'.$name;
			else
				$p.=';'.$name.'='.$v;
		}
		if($p=='')
			return '';
		return substr($p,1);
	}
}
