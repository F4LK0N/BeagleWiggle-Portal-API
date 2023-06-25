<?
use Core\Helper\FILTER;

class FILTER_NORMALIZE_ALPHA_Test extends FknTestCase
{
    //##########################################################################
    //### ALPHA ###
    //##########################################################################
    public function testAlpha()
    {
        $expected=
            '0123456789 '.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ '.
            'abcdefghijklmnopqrstuvwxyz '.
            'AAAAAA CEEEEIIII NOOOOO OUUUUY '.
            'aaaaaa ceeeeiiiionooooo ouuuuy yY'
        ;
        $input = FILTER::NORMALIZE_ALPHA(self::INPUT_COMMOM());
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
        
        
        $input = FILTER::RUN(self::INPUT_COMMOM(), 'normalize-alpha');
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
    }
    //##########################################################################
    public function testAlphaOverCode()
    {
        $input=FILTER::NORMALIZE_ALPHA(self::INPUT_EXTRA());
        $input=trim($input);
        $this->eq("", $input);
    }
    //##########################################################################
}
