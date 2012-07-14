<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db_driver
* @author     Loic Mathaud
* @contributor Laurent Jouanneau
* @copyright  2006 Loic Mathaud, 2007-2012 Laurent Jouanneau
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once(dirname(__FILE__).'/sqlite3.dbresultset.php');
class sqlite3DbConnection extends jDbConnection{
	function __construct($profile){
		if(!class_exists('SQLite3')){
			throw new jException('jelix~db.error.nofunction','sqlite3');
		}
		parent::__construct($profile);
	}
	public function beginTransaction(){
		$this->_doExec('BEGIN');
	}
	public function commit(){
		$this->_doExec('COMMIT');
	}
	public function rollback(){
		$this->_doExec('ROLLBACK');
	}
	public function prepare($query){
		throw new jException('jelix~db.error.feature.unsupported',array('sqlite','prepare'));
	}
	public function errorInfo(){
		return array($this->_connection->lastErrorCode(),$this->_connection->lastErrorMsg());
	}
	public function errorCode(){
		return $this->_connection->lastErrorCode();
	}
	protected function _connect(){
		$db=$this->profile['database'];
		if(preg_match('/^(app|lib|var)\:/',$db))
			$path=str_replace(array('app:','lib:','var:'),array(jApp::appPath(),LIB_PATH,jApp::varPath()),$db);
		else
			$path=jApp::varPath('db/sqlite3/'.$db);
		return new SQLite3($path);
	}
	protected function _disconnect(){
		return $this->_connection->close();
	}
	protected function _doQuery($query){
		if($qI=$this->_connection->query($query)){
			return new sqlite3DbResultSet($qI);
		}else{
			throw new jException('jelix~db.error.query.bad',$this->_connection->lastErrorMsg().' ('.$query.')');
		}
	}
	protected function _doExec($query){
		if($qI=$this->_connection->exec($query)){
			return $this->_connection->changes();
		}else{
			throw new jException('jelix~db.error.query.bad',$this->_connection->lastErrorMsg().' ('.$query.')');
		}
	}
	protected function _doLimitQuery($queryString,$offset,$number){
		$queryString.=' LIMIT '.$offset.','.$number;
		$this->lastQuery=$queryString;
		$result=$this->_doQuery($queryString);
		return $result;
	}
	public function lastInsertId($fromSequence=''){
		return $this->_connection->lastInsertRowID();
	}
	protected function _autoCommitNotify($state){
		$this->query('SET AUTOCOMMIT='.$state ? '1' : '0');
	}
	protected function _quote($text,$binary){
		return $this->_connection->escapeString($text);
	}
	public function getAttribute($id){
		switch($id){
			case self::ATTR_CLIENT_VERSION:
			case self::ATTR_SERVER_VERSION:
				$v=SQLite3::version();
				return $v['versionString'];
		}
		return "";
	}
	public function setAttribute($id,$value){
	}
}
