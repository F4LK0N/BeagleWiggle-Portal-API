<?
use Core\Helper\FILTER;

class FILTER_SANITIZE_COMPLEX_DATA_ID_LIST_Test extends FknTestCase
{
    //##########################################################################
    /** @dataProvider providerException */
    public function testException($input)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("FILTER error!\nSyntax error!");
        FILTER::ID_LIST($input);
    }
    static public function providerException()
    {
        return [
            ['['],
            ['{'],
        ];
    }
    //##########################################################################
    public function testEmpty()
    {
        $result = FILTER::ID_LIST(null);
        $this->tr(is_array($result));
        $this->eq(0, count($result));
        
        $result = FILTER::ID_LIST([]);
        $this->tr(is_array($result));
        $this->eq(0, count($result));
        
        $result = FILTER::ID_LIST('');
        $this->tr(is_array($result));
        $this->eq(0, count($result));
        
        $result = FILTER::ID_LIST('[]');
        $this->tr(is_array($result));
        $this->eq(0, count($result));
        
        $result = FILTER::ID_LIST('{}');
        $this->tr(is_array($result));
        $this->eq(0, count($result));
    }
    //##########################################################################
    public function testArray()
    {
        $result = FILTER::ID_LIST([
            1,
            2,
        ]);
        $this->tr(is_array($result));
        $this->eq(2, count($result));
        $this->eq(1, $result[1]);
        $this->eq(2, $result[2]);
        
        $result = FILTER::ID_LIST([
            '',
            ' ',
            -1,
            0,
            1,
            1,
            1,
            +2,
        ]);
        $this->tr(is_array($result));
        $this->eq(2, count($result));
        $this->eq(1, $result[1]);
        $this->eq(2, $result[2]);
    }
    //##########################################################################
    public function testString()
    {
        $result = FILTER::ID_LIST('[1,2]');
        $this->tr(is_array($result));
        $this->eq(2, count($result));
        $this->eq(1, $result[1]);
        $this->eq(2, $result[2]);
        
        $result = FILTER::ID_LIST('[""," ",-1,0,1,1,1,2]');
        $this->tr(is_array($result));
        $this->eq(2, count($result));
        $this->eq(1, $result[1]);
        $this->eq(2, $result[2]);
    }
    //##########################################################################
}
