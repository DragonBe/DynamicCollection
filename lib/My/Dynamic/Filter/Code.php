<?php
class My_Dynamic_Filter_Code extends FilterIterator
{
    /**
     * @var 	string The search filter
     */
    protected $_filter;
    
    /**
     * Constructor for this filter class
     * 
     * @param 	Iterator $iterator The object's Iterator
     * @param 	string $filter The search filter
     */
    public function __construct(Iterator $iterator, $filter)
    {
        parent::__construct($iterator);
        $this->_filter = $filter;
    }
    /**
     * (non-PHPdoc)
     * @see FilterIterator::accept()
     * @return boolean
     */
    public function accept()
    {
        $entry = $this->getInnerIterator()->current();
        if (0 === strcmp($entry->code, $this->_filter)) {
            return true;
        }
        return false;
    }
}