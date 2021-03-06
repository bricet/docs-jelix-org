<?php
/**
* @package   gitiwiki
* @subpackage gitiwiki
* @author    Laurent Jouanneau
* @copyright 2012 laurent Jouanneau
* @link      http://jelix.org
* @license    GNU PUBLIC LICENCE
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $conf = jIniFile::read(jApp::configPath('profiles.ini.php'));
        $list = array();
        foreach($conf as $prop=> $val) {
            if (is_array($val) && preg_match('/^gtwrepo\:(.*)$/', $prop, $m)) {
                if (isset($val['title']))
                    $list[$m[1]] = $val['title'];
                else
                    $list[$m[1]] = $m[1];
            }
        }

        $tpl = new jTpl();
        $tpl->assign ('repolist', $list);
        $rep->body->assign('MAIN', $tpl->fetch('repolist'));
        return $rep;
    }
}
