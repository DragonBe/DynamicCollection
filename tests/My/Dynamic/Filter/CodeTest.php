<?php
class My_Dynamic_Filter_CodeTest extends PHPUnit_Framework_TestCase
{
    protected $_obj;
    
    protected function setUp()
    {
        $this->_obj = new ArrayObject(array (
            array ('code' => '123', 'result' => 'num'),
            array ('code' => 'ABC', 'result' => 'alpha'),
        ), ArrayObject::ARRAY_AS_PROPS);
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->_obj = null;
    }
    public function testFilterCanFindExistingCode()
    {
        $iterator = new My_Dynamic_Filter_Code(
            $this->_obj->getIterator(), '123');
        $iterator->rewind();
        $this->assertSame(1, count($iterator));
        $result = $iterator->current();
        $this->assertSame('num', $result['result']);
    }
    public function testFilterReturnsEmptyWithNonExistingCode()
    {
        $iterator = new My_Dynamic_Filter_Code(
            $this->_obj->getIterator(), '000');
        $iterator->rewind();
        $this->assertNull($iterator->current());
    }
}