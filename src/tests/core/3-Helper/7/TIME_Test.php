<?
use Core\Helper\TIME;

class TIME_Test extends FknTestCase
{
    public function testSeconds()
    {
        $now = TIME::SECONDS();
        $this->tr(is_int($now));
    }
    public function testMicroSeconds()
    {
        $now = TIME::MICROSECONDS();
        $this->tr(is_float($now));
    }
    public function testFormatSeconds()
    {
        $this->eq('__0s', TIME::FORMAT(0));
        $this->eq('__1s', TIME::FORMAT(1));
        $this->eq('_60s', TIME::FORMAT(60));
        $this->eq('120s', TIME::FORMAT(120));
    }
    public function testFormatMicroSeconds()
    {
        $this->eq('__0s __0ms', TIME::FORMAT(0.0));
        $this->eq('__0s 100ms', TIME::FORMAT(0.1));
        $this->eq('__0s __1ms', TIME::FORMAT(0.001));
        $this->eq('__0s __0ms', TIME::FORMAT(0.0001));
    }
}
