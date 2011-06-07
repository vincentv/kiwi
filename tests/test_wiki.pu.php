<?php

require_once __DIR__ . '/../classes/Wiki.class.php';
require_once __DIR__ . '/../classes/Page.class.php';

class test_wikiUnitTest extends PHPUnit_Framework_TestCase {


    public function setUp () {
        if (file_exists(__DIR__.'/examples/new_repos.git'))
            jFile::removeDir(__DIR__.'/examples/new_repos.git');


        $this->wiki = new Wiki(__DIR__.'/examples/lotr.git');
    }

    public function tearDown(){
    }

    function testRepoPath () {
        $wiki = new Wiki(__DIR__.'/examples/lotr.git');
        $this->assertEquals(__DIR__.'/examples/lotr.git', $wiki->path);
    }

    function testCreateRepo (){
        $wiki = Wiki::create(__DIR__.'/examples/new_repos.git');
        $this->assertEquals(__DIR__.'/examples/new_repos.git', $wiki->path);
        $this->assertFileExists(__DIR__.'/examples/new_repos.git');
    }

    function testPages() {
        $pages = $this->wiki->pages();

        $names = array();
        foreach($pages as $page) {
            $names[] = $page->name;
        }

        $this->assertEquals(
            array("Bilbo-Baggins.md",
            "Eye-Of-Sauron.md",
            "Home.textile",
            "My-Precious.md"),
            $names);

    }

    function testSize(){
        $this->assertEquals(4, $this->wiki->size());
    }

    function testData(){

        $this->assertNull($this->wiki->page('qwerty'));

        $page = $this->wiki->page('My-Precious');
        $this->assertNotNull($page);
        $this->assertEquals("One ring to rule them all!\n", $page->raw_data());
    }

    function testRefToId(){
        $this->wiki->log();
        $this->assertEquals('a8ad3c09dd842a3517085bfadd37718856dee813', $this->wiki->ref_to_id());
    }

}
