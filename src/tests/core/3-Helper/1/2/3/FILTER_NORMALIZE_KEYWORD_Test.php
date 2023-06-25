<?
use Core\Helper\FILTER;

class FILTER_NORMALIZE_KEYWORD_Test extends FknTestCase
{
    //##########################################################################
    //### KEYWORD ###
    //##########################################################################
    public function testKeyword()
    {
        $expected=
            '-. '.
            '0123456789 '.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ _ '.
            'abcdefghijklmnopqrstuvwxyz '.
            'AAAAAA CEEEEIIII NOOOOO OUUUUY '.
            'aaaaaa ceeeeiiiionooooo ouuuuy yY'
        ;
        $input = FILTER::NORMALIZE_KEYWORD(self::INPUT_COMMOM());
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
        
        
        $input = FILTER::RUN(self::INPUT_COMMOM(), 'normalize-keyword');
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
    }
    //##########################################################################
    public function testKeywordOverCode()
    {
        $input=FILTER::NORMALIZE_KEYWORD(self::INPUT_EXTRA());
        $input=trim($input);
        $this->eq("", $input);
    }
    //##########################################################################
}
