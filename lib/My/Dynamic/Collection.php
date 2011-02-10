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
 * My_Dynamic_Collection
 * 
 * This collection class contains all objects dynamically generated for handling
 * CSV files, with different headers and field count.
 * 
 * @package		My
 */
class My_Dynamic_Collection implements Countable, SeekableIterator, RecursiveIterator
{
    /**
     * @var 	int The internal counter
     */
    protected $_count;
    /**
     * @var 	int The internal stack position
     */
    protected $_position;
    /**
     * @var 	array The collection of files with entries
     */
    protected $_data;
    /**
     * Constructor for this Collection
     */
    public function __construct()
    {
        $this->_count = 0;
        $this->rewind();
        $this->_data = array ();
    }
    /**
     * Adds a File object to this collection
     * 
     * @param 	My_Dynamic_Collection_File $file
     * @return	My_Dynamic_Collection
     */
    public function addData(My_Dynamic_Collection_File $file)
    {
        $this->_data[] = $file;
        $this->_count++;
        return $this;
    }
    /**
     * Retrieve an array of My_Dynamic_Collection_File objects
     * 
     * @return	array
     */
    public function getData()
    {
        return $this->_data;
    }
    /**
     * Converts this class into an array
     * 
     * @return	array
     */
    public function toArray()
    {
        $array = array (
            'data' => array (),
            'count' => $this->count(),
        );
        foreach ($this->getData() as $data) {
            $array['data'][] = $data->toArray();
        }
        return $array;
    }
    /**
     * (non-PHPdoc)
     * @see Countable::count()
     */
    public function count()
    {
        return $this->_count;
    }
    /**
     * (non-PHPdoc)
     * @see SeekableIterator::rewind()
     * @return My_Dynamic_Collection
     */
    public function rewind()
    {
        $this->_position = 0;
        return $this;
    }
    /**
     * (non-PHPdoc)
     * @see SeekableIterator::next()
     * @return My_Dynamic_Collection
     */
    public function next()
    {
        ++$this->_position;
        return $this;
    }
    /**
     * (non-PHPdoc)
     * @see SeekableIterator::valid()
     */
    public function valid()
    {
        return isset ($this->_data[$this->_position]);
    }
    /**
     * (non-PHPdoc)
     * @see SeekableIterator::key()
     */
    public function key()
    {
        return $this->_position;
    }
    /**
     * (non-PHPdoc)
     * @see SeekableIterator::current()
     */
    public function current()
    {
        return $this->_data[$this->_position];
    }
    /**
     * (non-PHPdoc)
     * @see SeekableIterator::seek()
     * @throws OutOfBoundsException
     * @return My_Dynamic_Collection
     */
    public function seek($position)
    {
        $this->_position = (int) $position;
        if (!$this->valid()) {
            throw new OutOfBoundsException('Invalid position');
        }
        return $this;
    }
    public function hasChildren()
    {
        return 0 < count($this->getData());
    }
    public function getChildren()
    {
        return $this->getData();
    }
}