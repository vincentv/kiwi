<?php

class Page {

    public $entry;

    public function __construct(Git\Tree\Entry $entry){
        if($entry->isBlob()){
            $this->entry = $entry;
        } else {
            throw new Exception('On attend un blob');
        }
    }

    public static function find($wiki, $name, $ref) {
        $page = null;

        $iterator = $wiki->pages($ref);

        foreach($iterator as $it) {
            if($name == pathinfo($it->name, PATHINFO_FILENAME)){
                $page = new Page($it);
                break;
            }
        }

        return $page;
    }

    public function name(){
        return $this->entry->name;
    }

    public function raw_data() {
        return $this->entry->toObject()->data;
    }


}


class PagesValid extends FilterIterator {

    protected $valid_extension = "/^(.+)\.(md|mkdn?|mdown|markdown|textile|rdoc|org|creole|re?st(\.txt)?|asciidoc|pod|(media)?wiki)$/i";
    protected $invalid_filename = "/^_/";

    public function __construct(Iterator $iterator){
        parent::__construct($iterator);
    }

    public function accept(){
        $iterator = $this->getInnerIterator()->current();

        $match = (preg_match($this->invalid_filename, $iterator->name, $mm))?false:true;

        if($match)
            $match = preg_match($this->valid_extension, $iterator->name);

        return $match;
    }
}
