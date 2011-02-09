<?php
class My_Dynamic_Collection_File implements Countable, SeekableIterator
{
    const MDC_ENTRY_SEPARATOR = ',';
    const MDC_ENTRY_EOL = "\r\n";
    /**
     * @var 	int Internal counter
     */
    protected $_count;
    /**
     * @var 	int Position in the iterator
     */
    protected $_position;
    /**
     * @var 	array Collection of My_Dynamic_Collection_Entry types
     */
    protected $_entries;
    /**
     * @var 	string The name of the current file
     */
    protected $_filename;
    /**
     * @var 	string The separator of values (default ',')
     */
    protected $_separator;
    /**
     * @var 	string The line ending of each entry (default '\r\n')
     */
    protected $_eol;
    /**
     * Constructor of this My_Dynamic_Collection_File
     */
    public function __construct()
    {
        $this->_count = 0;
        $this->_entries = array ();
        $this->setSeparator(self::MDC_ENTRY_SEPARATOR);
        $this->setEol(self::MDC_ENTRY_EOL);
    }
    /**
     * Adds an entry to this collection
     * 
     * @param 	My_Dynamic_Collection_Entry $entry
     * @return	My_Dynamic_Collection_File
     */
    public function addEntry(My_Dynamic_Collection_Entry $entry)
    {
        $this->_entries[] = $entry;
        $this->_count++;
        return $this;
    }
    /**
     * Returns an array containing objects of type My_Dynamic_Collection_Entry
     * 
     * @return	array
     */
    public function getEntries()
    {
        return $this->_entries;
    }
    /**
     * Sets the filename for this File
     * 
     * @param 	string $filename
     * @return	My_Dynamic_Collection_File
     */
    public function setFilename($filename)
    {
        $this->_filename = (string) $filename;
        return $this;
    }
    /**
     * Retrieve the filename of this File
     * 
     * @return	string
     */
    public function getFilename()
    {
        return $this->_filename;
    }
    /**
     * Sets the separator between data entry fields
     * 
     * @param 	string $separator
     * @return	My_Dynamic_Collection_File
     */
    public function setSeparator($separator)
    {
        $this->_separator = (string) $separator;
        return $this;
    }
    /**
     * Retrieve the used separator between data
     * 
     * @return	string
     */
    public function getSeparator()
    {
        return $this->_separator;
    }
    /**
     * Sets the line ending delimiter
     * 
     * @param 	string $eol
     * @return	My_Dynamic_Collection_File
     */
    public function setEol($eol)
    {
        $this->_eol = (string) $eol;
        return $this;
    }
    /**
     * Retrieve the used line ending delimiter
     * 
     * @return	string
     */
    public function getEol()
    {
        return $this->_eol;
    }
    /**
     * Loads the data entries of a given file
     * 
     * @param 	string $file The full path of a file
     * @return	My_Dynamic_Collection_File
     * @throws	My_Dynamic_Collection_Exception
     */
    public function loadFile($file)
    {
        $this->checkFile($file);
        $filePath = dirname($file);
        $fileName = basename($file);
        $fileType = substr($fileName, -3);
        if ('txt' !== $fileType) {
            throw new My_Dynamic_Collection_Exception('Invalid file type ' . $fileType);
        }
    }
    /**
     * Loads the configuration for a given file
     * 
     * @param 	string $file The full path of a file
     * @return	My_Dynamic_Collection_File
     */
    public function loadConfiguration($file)
    {
        $filePath = dirname($file);
        $fileName = baseName($file);
        $baseFile = substr($fileName, 0, -4);
        $file = sprintf('%s/%s.xml', $filePath, $baseFile);
        $this->checkFile($file);
        $xml = simplexml_load_file($file);
        $this->setFilename($xml['name']);
    }
    /**
     * Checks the condition of a given file. It returns TRUE when the file
     * can be accessed by the application or FALSE when there's a problem.
     * 
     * @param 	string $file The location of a given file
     * @return  boolean
     * @throws  My_Dynamic_Collection_Exception
     */
    public function checkFile($file)
    {
        $goodFile = true;
        if (!file_exists($file)) {
            $goodFile = false;
            throw new My_Dynamic_Collection_Exception('File is not found');
        }
        if (!is_readable($file)) {
            $goodFile = false;
            throw new My_Dynamic_Collection_Exception('Cannot open file');
        }
        return $goodFile;
    }
    /**
     * (non-PHPdoc)
     * @see     Countable::count()
     * @return  int
     */
    public function count()
    {
        return $this->_count;
    }
    /**
     * (non-PHPdoc)
     * @see		SeekableIterator::rewind()
     * @return	My_Dynamic_Collection_File
     */
    public function rewind()
    {
        $this->_position = 0;
        return $this;
    }
    /**
     * (non-PHPdoc)
     * @see		SeekableIterator::next()
     * @return	My_Dynamic_Collection_File
     */
    public function next()
    {
        ++$this->_position;
        return $this;
    }
    /**
     * (non-PHPdoc)
     * @see		SeekableIterator::valid()
     * @return	boolean
     */
    public function valid()
    {
        return isset ($this->_entries[$this->_position]);
    }
    /**
     * (non-PHPdoc)
     * @see		SeekableIterator::key()
     * @return	int
     */
    public function key()
    {
        return $this->_position;
    }
    /**
     * (non-PHPdoc)
     * @see		SeekableIterator::current()
     * @return	My_Dynamic_Collection_Entry
     */
    public function current()
    {
        return $this->_entries[$this->_position];
    }
    /**
     * (non-PHPdoc)
     * @see		SeekableIterator::seek()
     * @param	int $position
     * @return	My_Dynamic_Collection_File
     */
    public function seek($position)
    {
        $this->_position = (int) $position;
        if (!$this->valid()) {
            throw new OutOfBoundsException('Invalid seek position provided');
        }
        return $this;
    }
}