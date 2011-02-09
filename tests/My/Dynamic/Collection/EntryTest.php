<?php
class My_Dynamic_Collection_EntryTest extends PHPUnit_Framework_TestCase
{
    protected $_entry;
    
    protected function setUp()
    {
        $this->_entry = new My_Dynamic_Collection_Entry();
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->_entry = null;
    }
    
    public function testPropertiesCanBeInstantiated()
    {
        $this->_entry->setProperties(array ('a', 'b', 'c'));
        $this->assertNull($this->_entry->getA());
        $this->assertNull($this->_entry->getB());
        $this->assertNull($this->_entry->getC());
    }
    public function propertyProvider()
    {
        return array (
            array ('a', 'test1'),
            array ('b', 'test2'),
            array ('c', 'test3'),
        );
    }
    /**
     * @dataProvider propertyProvider
     */
    public function testPropertiesCanBePopulated($property, $value)
    {
        $this->_entry->addProperty($property);
        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);
        $this->_entry->$setter($value);
        $this->assertSame($value, $this->_entry->$getter());
        $this->assertSame(1, count($this->_entry));       
    }
    public function testValuesCannotBeRetrievedForNonExistingProperties()
    {
        try {
            $this->_entry->getFoo();
        } catch (My_Dynamic_Collection_Exception $e) {
            return;
        }
        $this->fail('Expected exception was not thrown');
    }
    public function testValuesCannotBeSetForNonExistingProperties()
    {
        try {
            $this->_entry->setFoo('Bar');
        } catch (My_Dynamic_Collection_Exception $e) {
            return;
        }
        $this->fail('Expected exception was not thrown');
    }
    public function testPropertyListCanBeRetrieved()
    {
        $properties = array ('a', 'b', 'c');
        $this->_entry->setProperties($properties);
        $this->assertSame($properties, $this->_entry->getProperties());
    }
    public function testPropertyValuesCanBeSetDirectly()
    {
        $properties = array ('a', 'b', 'c');
        $this->_entry->setProperties($properties);
        $this->_entry->a = 'test1';
        $this->_entry->b = 'test2';
        $this->_entry->c = 'test3';
    }
    public function testPropertyValuesCanBeRetrievedDirectly()
    {
        $array = array ('a' => 'test1', 'b' => 'test2', 'c' => 'test3');
        $this->_entry->setProperties(array_keys($array));
        foreach ($array as $property => $value) {
            $this->_entry->$property = $value;
        }
        foreach ($array as $property => $value) {
            $this->assertSame($value, $this->_entry->$property);
        }
    }
}