<?
use Core\Helper\FILTER;

class FILTER_NORMALIZE_PT_BR_Test extends FknTestCase
{
    //##########################################################################
    //### PT BR ###
    //##########################################################################
    public function testPtBr()
    {
        $expected=
            '!"#$%&\'()*+,-./0123456789:;<=>?@'.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`'.
            'abcdefghijklmnopqrstuvwxyz{|}~ '.
            '!¢£¤¥|§¨©ª«¬-®-°±²³´µ¶·¸¹º»¼½¾?'.
            'ÀÁÂÃAAAEÇÈÉÊEÌÍÎIDNÒÓÔÕOxOÙÚÛUYÞß'.
            'àáâãaaaeçèéêeìíîionòóôõo÷oùúûuyþyY'
        ;
        $input = FILTER::NORMALIZE_PT_BR(self::INPUT_COMMOM());
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
        
        
        $input = FILTER::RUN(self::INPUT_COMMOM(), 'normalize-pt-br');
        $input = FILTER::DOUBLE_SPACES($input);
        $input=trim($input);
        $this->eq($expected, $input);
    }
    //##########################################################################
    public function testPtBrBreaks()
    {
        $this->eq("A\n\nB", FILTER::NORMALIZE_PT_BR("A\n\nB"));
    }
    //##########################################################################
    public function testPtBrOverCode()
    {
        $input=FILTER::NORMALIZE_PT_BR(self::INPUT_EXTRA());
        $input=trim($input);
        $this->eq("", $input);
    }
    //##########################################################################
}
