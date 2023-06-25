<?
use Core\Helper\FILTER;

class FILTER_RUN_Test extends FknTestCase
{
    /**
     * @dataProvider providerInvalidArgument
     */
    public function testInvalidArgument($filters)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("FILTER error!\nInvalid sanitizers!");
        FILTER::RUN("A\n\nB", $filters);
    }
    static public function providerInvalidArgument()
    {
        return [
            [null],
            [true],
            [false],
            [0],
            [1],
            [(object)[]],
        ];
    }
    public function testInvalidFilter()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("FILTER error!\nFilter 'A' not found!");
        FILTER::RUN("AB", "A");
    }
    public function testSingle()
    {
        $this->eq("A\nB", FILTER::RUN("A\n\nB", "double-breaks"));
    }
    public function testMultiple()
    {
        $this->eq("A\nB C", FILTER::RUN("A\n\nB  C", ['double-breaks', 'double-spaces']));
    }
    public function testAllSecurity()
    {
        $this->eq("abc", FILTER::RUN("abc", ['forbidden', 'php', 'quotes']));
        $this->eq("abc", FILTER::RUN("abc", ['forbidden', 'php-encode', 'quotes-encode']));
        $this->eq("abc", FILTER::RUN("abc", ['secure']));
        $this->eq("abc", FILTER::RUN("abc", ['secure-strip']));
        $this->eq("abc", FILTER::RUN("abc", ['secure-encode']));
    }
    public function testAllNormalize()
    {
        $this->eq("abc", FILTER::RUN("abc", ['normalize-spaces']));
        $this->eq("abc", FILTER::RUN("abc", ['normalize-breaks']));
        $this->eq("123", FILTER::RUN("123", ['normalize-number']));
        $this->eq("abc", FILTER::RUN("abc", ['normalize-alpha']));
        $this->eq("abc", FILTER::RUN("abc", ['normalize-keyword']));
        $this->eq("abc", FILTER::RUN("abc", ['normalize-ascii']));
        $this->eq("abc", FILTER::RUN("abc", ['normalize-pt-br']));
    }
    public function testAllSpaces()
    {
        $this->eq("abc", FILTER::RUN("abc", ['double-breaks']));
        $this->eq("abc", FILTER::RUN("abc", ['double-spaces']));
        $this->eq("abc", FILTER::RUN("abc", ['breaks']));
        $this->eq("abc", FILTER::RUN("abc", ['spaces']));
    }
    public function testAllSanitize()
    {
        $this->eq("1",   FILTER::RUN("1",   ['bool']));
        $this->eq("123", FILTER::RUN("123", ['number']));
        $this->eq("123", FILTER::RUN("123", ['int']));
        $this->eq("abc", FILTER::RUN("abc", ['keyword']));
        $this->eq("abc", FILTER::RUN("abc", ['ascii']));
        $this->eq("abc", FILTER::RUN("abc", ['name']));
        $this->eq("abc", FILTER::RUN("abc", ['text']));
        $this->eq("abc", FILTER::RUN("abc", ['url']));
    }
    public function testAll()
    {
        $this->eq("1", FILTER::RUN("1", [
            'forbidden', 'php', 'quotes',
            'php-encode', 'quotes-encode',
            'secure', 'secure-strip', 'secure-encode',
            
            'normalize-spaces',
            'normalize-breaks',
            'normalize-number',
            'normalize-alpha',
            'normalize-keyword',
            'normalize-ascii',
            'normalize-pt-br',
            
            'double-breaks',
            'double-spaces',
            'breaks',
            'spaces',
            
            'bool',
            'number',
            'int',
            'keyword',
            'ascii',
            'name',
            'text',
            'url',
        ]));
    }
}
