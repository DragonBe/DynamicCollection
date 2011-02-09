<?php
class My_Dynamic_CollectionTest extends PHPUnit_Framework_TestCase
{
    protected $_collection;
    
    protected function setUp()
    {
        $this->_collection = new My_Dynamic_Collection();
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->_collection = null;
    }
    
    public function testCollectionIsEmpty()
    {
        $this->assertSame(0, count($this->_collection));
        $this->assertSame(array (), $this->_collection->getData());
        try {
            $this->_collection->seek(1);
            $this->fail('OutOfBoundsException was not thrown');
        } catch (OutOfBoundsException $e) {
            /* this is ok */
        }
    }
    
    public function fileProvider()
    {
        return array (
            array (TEST_PATH . '/_files/foo.txt'),
            array (TEST_PATH . '/_files/bar.txt'),
        );
    }
    
    public function populateCollection()
    {
        $files = $this->fileProvider();
        foreach ($files as $file) {
            $this->_collection->addData(
                new My_Dynamic_Collection_File($file[0]));
        }
    }
    
    public function testFilesCanBeAdded()
    {
        $this->populateCollection();
        $this->assertSame(2, count($this->_collection));
    }
    
    public function testCollectionConvertsToArray()
    {
        $this->populateCollection();
        $this->assertType('array', $this->_collection->toArray());
    }
    
    public function testCollectionIsIterator()
    {
        $this->populateCollection();
        $this->assertSame(1, $this->_collection->seek(1)->key());
        $this->assertSame(0, $this->_collection->rewind()->key());
        $this->assertSame(1, $this->_collection->next()->key());
        $this->assertTrue($this->_collection->hasChildren());
    }
}