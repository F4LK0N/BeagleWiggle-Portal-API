<?
use Core\Helper\FILTER;

class FILTER_NORMALIZE_ASCII_Test extends FknTestCase
{
    //##########################################################################
    //### ASCII ###
    //##########################################################################
    public function testAscii()
    {
        $expected=
            '!"#$%&\'()*+,-./'.
            '0123456789:;<=>?@'.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`'.
            'abcdefghijklmnopqrstuvwxyz{|}~ '.
            'AAAAAA CEEEEIIII NOOOOO OUUUUY '.
            'aaaaaa ceeeeiiiionooooo ouuuuy yY'
        ;
        $input = FILTER::NORMALIZE_ASCII(self::INPUT_COMMOM());
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
        
        
        $input = FILTER::RUN(self::INPUT_COMMOM(), 'normalize-ascii');
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
    }
    //##########################################################################
    public function testAsciiOverCode()
    {
        $input=FILTER::NORMALIZE_ASCII(self::INPUT_EXTRA());
        $input=trim($input);
        $this->eq("", $input);
    }
}
