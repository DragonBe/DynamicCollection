<?php
/**
 * Example dynamic collection
 * 
 * @license		Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 * @link		http://creativecommons.org/licenses/by-sa/3.0/
 * @version		$Id:$
 *
 */
/**
 * My_Dynamic_Collection_Entry
 * 
 * This class contains a single row as an entry found within a file.
 * 
 * @author 		dragonbe
 * @package		My
 *
 */
class My_Dynamic_Collection_Entry implements Countable
{
    /**
     * @var 	array Collection of dynamic properties
     */
    protected $_properties;
    /**
     * @var 	int Internal Counter
     */
    protected $_count;
    /**
     * Constructor class for this Entry
     */
    public function __construct()
    {
        $this->_properties = array ();
        $this->_count = 0;
    }
    /**
     * Check to see if a property already exists
     * 
     * @param 	string $property Label for a property
     * @return	boolean
     */
    public function hasProperty($property)
    {
        return array_key_exists($property, $this->_properties);
    }
    /**
     * Adds a property to this Entry class
     * 
     * @param 	string $property
     * @return	My_Dynamic_Collection_Entry
     */
    public function addProperty($property)
    {
        $property = strtolower((string) $property);
        if (!$this->hasProperty($property)) {
            $this->_properties[$property] = null;
            $this->_count++;
        }
        return $this;
    }
    /**
     * Sets an array of properties as property
     * 
     * @param 	array $properties An array of properties
     * @return	My_Dynamic_Collection_Entry
     */
    public function setProperties($properties)
    {
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
        return $this;
    }
    /**
     * Retrieve a list of class properties
     * 
     * @return	array
     */
    public function getProperties()
    {
        return array_keys($this->_properties);
    }
    /**
     * Magic setter to set a value to an existing property
     * 
     * @param 	string $name The label of the property
     * @param 	mixed $value The value for that property
     */
    public function __set($name, $value)
    {
        if ($this->hasProperty($name)) {
            $this->_properties[$name] = $value;
        }
    }
    /**
     * Magic getter to retrieve a value form an existing property
     * 
     * @param 	string $name The label of the property
     * @return	mixed
     */
    public function __get($name)
    {
        if ($this->hasProperty($name)) {
            return $this->_properties[$name];
        }
    }
    /**
     * Magic caller to access properties with get or set methods. It either
     * returns a value for a getMethod or void for a setMethod
     * 
     * @param string $method The set/get method
     * @param array $args
     * @return null|mixed
     * @example setTest('Test1') assigns 'Test1' to property 'test'
     * @example getTest() returns a the value for property 'test' 
     */
    public function __call($method, $args)
    {
        // let's first creat our setters
        if ('set' === substr($method, 0, 3)) {
            $property = substr(strtolower($method), 3);
            if (!$this->hasProperty($property)) {
                throw new My_Dynamic_Collection_Exception('Invalid method called');
            }
            $this->$property = $args[0];
        }
        if ('get' === substr($method, 0, 3)) {
            $property = substr(strtolower($method), 3);
            if (!$this->hasProperty($property)) {
                throw new My_Dynamic_Collection_Exception('Invalid method called');
            }
            return $this->$property;
        }
    }
    /**
     * (non-PHPdoc)
     * @see Countable::count()
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }
    /**
     * Reprisent this object as an array
     * 
     * @return	array
     */
    public function toArray()
    {
        $array = array ();
        foreach ($this->getProperties() as $property) {
            $array[$property] = $this->$property;
        }
        return $array;
    }
}