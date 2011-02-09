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
            array (TEST_PATH . '/_files/foo.txt', 'Publishing'),
            array (TEST_PATH . '/_files/bar.txt', 'Frameworks'),
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
    public function testFileCanBeLoaded($file)
    {
        $this->_file->loadFile($file);
        $this->assertSame(2, count($this->_file));
        $this->assertSame(array ('Publishing', 'Frameworks'), $this->_file->getFileNames());
    }
    
}