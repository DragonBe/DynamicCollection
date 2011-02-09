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
}