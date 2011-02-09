<?php
class My_Dynamic_Collection_FileTest extends PHPUnit_Framework_TestCase
{
    protected $_file;
    
    protected function setUp()
    {
        $this->_file = new My_Dynamic_Collection_File();
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->_file = null;
    }
    public function testFileIsEmpty()
    {
        $this->assertSame(0, count($this->_file));
    }
    public function testEntrySeparatorCanBeModified()
    {
        $this->assertSame(My_Dynamic_Collection_File::MDC_ENTRY_SEPARATOR, $this->_file->getSeparator());
        $this->_file->setSeparator(';');
        $this->assertSame(';', $this->_file->getSeparator());
    }
    public function testEntryEolCanBeModified()
    {
        $this->assertSame(My_Dynamic_Collection_File::MDC_ENTRY_EOL, $this->_file->getEol());
        $this->_file->setEol(PHP_EOL);
        $this->assertSame(PHP_EOL, $this->_file->getEol());
    }
    public function fileProvider()
    {
        return array (
            array (TEST_PATH . '/_files/foo.txt', 'Publishing', 3),
            array (TEST_PATH . '/_files/bar.txt', 'Frameworks', 3),
        );
    }
    /**
     * @dataProvider fileProvider
     */
    public function testCheckFileReturnsTrueForCorrectFiles($file)
    {
        $this->assertTrue($this->_file->checkFile($file));
    }
    public function testCheckFileThrowsExceptionForNonExistingFiles()
    {
        $file = TEST_PATH . '/_files/nonexisting.txt';
        try {
            $this->_file->checkFile($file);
        } catch (My_Dynamic_Collection_Exception $e) {
            return;
        }
        $this->fail('Expected exception not thrown');
    }
    public function testCheckFileThrowsExceptionForNotReadableFiles()
    {
        $file = TEST_PATH . '/_files/dummy.txt';
        chmod($file, 0044);
        try {
            $this->_file->checkFile($file);
        } catch (My_Dynamic_Collection_Exception $e) {
            chmod($file, 0644);
            return;
        }
        $this->fail('Expected exception not thrown');
    }
    public function testCheckFileThrowsExceptionForWrongExtension()
    {
        $file = TEST_PATH . '/_files/wrongfile.chk';
        try {
            $this->_file->loadFile($file);
        } catch (My_Dynamic_Collection_Exception $e) {
            chmod($file, 0644);
            return;
        }
        $this->fail('Expected exception not thrown');
    }
    /**
     * @dataProvider fileProvider
     */
    public function testFileConfigurationCanBeLoaded($file, $filename)
    {
        $this->_file->loadConfiguration($file);
        $this->assertSame($filename, $this->_file->getFilename());
    }
    /**
     * @dataProvider fileProvider
     */
    public function testFileCanBeLoaded($file, $filename, $count)
    {
        $this->_file->loadFile($file);
        $this->assertSame($count, count($this->_file));
        $this->assertSame($filename, $this->_file->getFileName());
        $this->assertType('array', $this->_file->getEntries());
    }
    /**
     * @dataProvider fileProvider
     */
    public function testFileIsIterator($file, $filename, $count)
    {
        $this->_file->loadFile($file);
        $this->assertType('My_Dynamic_Collection_Entry', $this->_file->seek($count - 1)->current());
        $this->assertTrue($this->_file->valid());
        $this->assertSame(0, $this->_file->rewind()->key());
        $this->assertSame(1, $this->_file->next()->key());
        try {
            $this->_file->seek($count+1);
            $this->fail('Expected exception not thrown');
        } catch (OutOfBoundsException $e) {
            /** expected exception **/
        }
    }
    
    public function testFileCanBeConvertedIntoAnArray()
    {
        $this->_file->loadFile(TEST_PATH . '/_files/foo.txt');
        $array = $this->_file->toArray();
        $this->assertType('array', $array);
        $this->assertArrayHasKey('filename', $array);
        $this->assertArrayHasKey('count', $array);
        $this->assertSame(3, $array['count']);
        $this->assertArrayHasKey('entries', $array);
        $this->assertType('array', $array['entries']);
    }
}