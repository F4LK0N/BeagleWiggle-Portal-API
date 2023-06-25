<?
use Core\Helper\STORAGE;

class STORAGE_Test extends FknTestCase
{
    public function testBytes()
    {
        $this->eq('___0MB ___0KB ___0B', STORAGE::FORMAT(0));
        $this->eq('___0MB ___0KB ___1B', STORAGE::FORMAT(1));
        $this->eq('___0MB ___0KB _999B', STORAGE::FORMAT(999));
        $this->eq('___0MB ___0KB 1023B', STORAGE::FORMAT(1023));
    }
    public function testKiloBytes()
    {
        $this->eq('___0MB ___1KB ___0B', STORAGE::FORMAT(1024*1));
        $this->eq('___0MB _999KB ___0B', STORAGE::FORMAT(1024*999));
        $this->eq('___0MB 1023KB ___0B', STORAGE::FORMAT(1024*1023));
    }
    public function testMegaBytes()
    {
        $this->eq('___1MB ___0KB ___0B', STORAGE::FORMAT(1024*1024*1));
        $this->eq('_999MB ___0KB ___0B', STORAGE::FORMAT(1024*1024*999));
        $this->eq('1023MB ___0KB ___0B', STORAGE::FORMAT(1024*1024*1023));
    }
}
