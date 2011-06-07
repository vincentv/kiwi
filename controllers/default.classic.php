<?php
/**
* @package   kiwi
* @subpackage kiwi
* @author    Vincentv
* @copyright 2011 Vincentv
* @link      https://github.com/vincentv/kiwi
*/
jClasses::inc('kiwi~Wiki');

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $wiki = new Wiki(__DIR__.'/../tests/examples/lotr.git');

        $pages = $wiki->pages();

        $tpl = new jTpl();
        $tpl->assign('pages', $pages);
        $rep->body->assign('MAIN', $tpl->fetch('index'));

        return $rep;
    }

    function show(){

        $repository = __DIR__.'/../tests/examples/lotr.git';
        $page_name = $this->param('page', null, true);
        $page_name = pathinfo($page_name, PATHINFO_FILENAME);

        $this->wiki = new Wiki($repository);
        $page = $this->wiki->page($page_name);

        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $page->raw_data());

        return $rep;

    }
}

