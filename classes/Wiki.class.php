<?php

require_once __DIR__ . '/Page.class.php';

class Wiki {

    public $path;
    public $repository;

    public function  __construct($path){
        $this->path = $path;
        $this->repository = new Git\Repository($path);
    }

    public static function create($path){
        Git\Repository::init($path, true);

        return new Wiki($path);
    }

    public function branches(){
        return  $this->repository->getReferences();
    }

    public function ref_to_id ($ref = null){
        if (is_null($ref)){
            try{
                $ref = $this->repository->getIndex();
            } catch(Exception $e){
                $ref = 'refs/heads/master';
                $ref = $this->repository->lookupRef($ref);
            }
        } elseif (!($ref instanceof Git\Reference)) {
            $ref = $this->repository->lookupRef($ref);
        }
        return $ref->getId();
    }

    public function pages($ref = null) {
        $commit = $this->repository->getCommit($this->ref_to_id($ref));

        $tree = $commit->getTree();

        $iterator = new ArrayIterator($this->tree_to_array($tree->getIterator()));
        return new PagesValid($iterator);
    }

    public function size($ref = null) {
        return iterator_count($this->pages($ref));
    }

    public function page($name, $ref = null){
        return Page::find($this, $name, $ref);
    }

    protected function tree_to_array(Git\TreeIterator $Iterator){
        $pages = array();

        foreach($Iterator as $it) {

            if($it->isTree()){
                $obj = $it->toObject();
                $pages = array_merge($pages, $this->tree_to_array($obj->getIterator()));
            } else {
                $pages[] = $it;
            }
        }

        usort($pages, function($a, $b){
            return strcasecmp ( $a->name, $b->name );
        });

        return $pages;
    }
}
