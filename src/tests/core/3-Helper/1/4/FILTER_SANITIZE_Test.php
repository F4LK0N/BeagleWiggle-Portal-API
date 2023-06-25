<?
use Core\Helper\FILTER;

class FILTER_SANITIZE_Test extends FknTestCase
{
    //##########################################################################
    //### BOOL ###
    //##########################################################################
    public function testBool()
    {
        $this->eq('', FILTER::BOOL('  '));
        $this->eq('', FILTER::BOOL('A'));
        $this->eq('', FILTER::BOOL('+'));
        $this->eq('', FILTER::BOOL('2'));
        $this->eq('', FILTER::BOOL('10'));
        $this->eq('', FILTER::BOOL('-1'));
        $this->eq('', FILTER::BOOL(-1));
        $this->eq('', FILTER::BOOL(2));
        $this->eq('', FILTER::BOOL(10));
        
        $this->eq('', FILTER::BOOL(self::INPUT_COMMOM()));
        $this->eq('', FILTER::BOOL(self::INPUT_EXTRA()));
        
        $this->eq('1', FILTER::BOOL(' '));
        $this->eq('1', FILTER::BOOL('1'));
        $this->eq('1', FILTER::BOOL('T'));
        $this->eq('1', FILTER::BOOL('t'));
        $this->eq('1', FILTER::BOOL('TRUE'));
        $this->eq('1', FILTER::BOOL('true'));
        $this->eq('1', FILTER::BOOL('True'));
        $this->eq('1', FILTER::BOOL(1));
        $this->eq('1', FILTER::BOOL(true));
        $this->eq('1', FILTER::BOOL(intval(true)));
        $this->eq('1', FILTER::BOOL(strval(true)));
        
        $this->eq('0', FILTER::BOOL(''));
        $this->eq('0', FILTER::BOOL('0'));
        $this->eq('0', FILTER::BOOL('F'));
        $this->eq('0', FILTER::BOOL('f'));
        $this->eq('0', FILTER::BOOL('FALSE'));
        $this->eq('0', FILTER::BOOL('false'));
        $this->eq('0', FILTER::BOOL('False'));
        $this->eq('0', FILTER::BOOL(0));
        $this->eq('0', FILTER::BOOL(false));
        $this->eq('0', FILTER::BOOL(intval(false)));
        $this->eq('0', FILTER::BOOL(strval(false)));
        
        $this->eq('',  FILTER::RUN('  ', 'bool'));
        $this->eq('1', FILTER::RUN('1',  'bool'));
        $this->eq('0', FILTER::RUN('0',  'bool'));
    }
    //##########################################################################
    //### NUMBER ###
    //##########################################################################
    public function testNumber()
    {
        $this->eq('', FILTER::NUMBER('A'));
        $this->eq('', FILTER::NUMBER(' '));
        $this->eq('', FILTER::NUMBER('+ 1'));
        $this->eq('', FILTER::NUMBER('- 1'));
        $this->eq('', FILTER::NUMBER('1 1'));
        $this->eq('', FILTER::NUMBER('1. 1'));
        $this->eq('', FILTER::NUMBER('1..1'));
        $this->eq('', FILTER::NUMBER('1.1.'));
        $this->eq('', FILTER::NUMBER('.1.1'));
        $this->eq('', FILTER::NUMBER('. 1'));
        $this->eq('', FILTER::NUMBER(',1'));
        $this->eq('', FILTER::NUMBER('1,'));
        
        $this->eq('0', FILTER::NUMBER('0'));
        $this->eq('1', FILTER::NUMBER('1'));
        $this->eq('0', FILTER::NUMBER(' 0 '));
        $this->eq('1', FILTER::NUMBER(' 1 '));
        
        $this->eq('1', FILTER::NUMBER('+1'));
        $this->eq('-1', FILTER::NUMBER('-1'));
        
        $this->eq('123456', FILTER::NUMBER('123,456'));
        $this->eq('123456789', FILTER::NUMBER('123,456,789'));
        
        $this->eq('1.25', FILTER::NUMBER('1.25'));
        $this->eq('1.25', FILTER::NUMBER('+1.25'));
        $this->eq('-1.25', FILTER::NUMBER('-1.25'));
        
        $this->eq('0.1', FILTER::NUMBER('.1'));
        
        $this->eq('123456.321', FILTER::NUMBER('123,456.321'));
        $this->eq('123456789.321', FILTER::NUMBER('123,456,789.321'));
        
        $this->eq('1.25', FILTER::RUN('1.25', 'number'));
    }
    //##########################################################################
    //### INT ###
    //##########################################################################
    public function testInt()
    {
        $this->eq('', FILTER::INT('A'));
        $this->eq('', FILTER::INT(' '));
        $this->eq('', FILTER::INT('+ 1'));
        $this->eq('', FILTER::INT('- 1'));
        $this->eq('', FILTER::INT('1 1'));
        $this->eq('', FILTER::INT('1. 1'));
        $this->eq('', FILTER::INT('1..1'));
        $this->eq('', FILTER::INT('1.1.'));
        $this->eq('', FILTER::INT('.1.1'));
        $this->eq('', FILTER::INT('. 1'));
        $this->eq('', FILTER::INT(',1'));
        $this->eq('', FILTER::INT('1,'));
        
        $this->eq('0', FILTER::INT('0'));
        $this->eq('1', FILTER::INT('1'));
        $this->eq('0', FILTER::INT(' 0 '));
        $this->eq('1', FILTER::INT(' 1 '));
        
        $this->eq('1', FILTER::INT('+1'));
        $this->eq('-1', FILTER::INT('-1'));
        
        $this->eq('123456', FILTER::INT('123,456'));
        $this->eq('123456789', FILTER::INT('123,456,789'));
        
        $this->eq('1', FILTER::INT('1.25'));
        $this->eq('1', FILTER::INT('+1.25'));
        $this->eq('-1', FILTER::INT('-1.25'));
        
        $this->eq('0', FILTER::INT('.1'));
        
        $this->eq('123456', FILTER::INT('123,456.321'));
        $this->eq('123456789', FILTER::INT('123,456,789.321'));
        
        $this->eq('1', FILTER::RUN('1.25', 'int'));
    }
    //##########################################################################
    //### KEYWORD ###
    //##########################################################################
    public function testKeyword()
    {
        $expected=
            '-.'.
            '0123456789'.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ_'.
            'abcdefghijklmnopqrstuvwxyz'.
            'AAAAAACEEEEIIIINOOOOOOUUUUY'.
            'aaaaaaceeeeiiiionoooooouuuuyyY'
        ;
        $this->eq($expected, FILTER::KEYWORD(self::INPUT_COMMOM()));
        
        $this->eq('', FILTER::KEYWORD(self::INPUT_EXTRA()));
        
        $this->eq($expected, FILTER::RUN(self::INPUT_COMMOM(), 'keyword'));
    }
    //##########################################################################
    //### ASCII ###
    //##########################################################################
    public function testAscii()
    {
        $expected=
            '! #$%& ()*+,-./'.
            '0123456789:;<=>?@'.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_ '.
            'abcdefghijklmnopqrstuvwxyz{|}~ '.
            'AAAAAA CEEEEIIII NOOOOO OUUUUY '.
            'aaaaaa ceeeeiiiionooooo ouuuuy yY'
        ;
        $this->eq($expected, FILTER::ASCII(self::INPUT_COMMOM()));
        
        $this->eq('', FILTER::ASCII(self::INPUT_EXTRA()));
        
        $this->eq($expected, FILTER::RUN(self::INPUT_COMMOM(), 'ascii'));
    }
    //##########################################################################
    //### NAME ###
    //##########################################################################
    public function testName()
    {
        $expected=
            '!&#34;#$%&&#39;()*+,-./0123456789:;<=>?@'.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_&#39;'.
            'abcdefghijklmnopqrstuvwxyz{|}~ '.
            '!¢£¤¥|§¨©ª«¬-®-°±²³&#39;µ¶·¸¹º»¼½¾?'.
            'ÀÁÂÃAAAEÇÈÉÊEÌÍÎIDNÒÓÔÕOxOÙÚÛUYÞß'.
            'àáâãaaaeçèéêeìíîionòóôõo÷oùúûuyþyY'
        ;
        $this->eq($expected, FILTER::NAME(self::INPUT_COMMOM()));
        
        $this->eq('', FILTER::NAME(self::INPUT_EXTRA()));
        
        $this->eq("A B", FILTER::NAME("A\nB"));
        
        $this->eq($expected, FILTER::RUN(self::INPUT_COMMOM(), 'name'));
    }
    //##########################################################################
    //### TEXT ###
    //##########################################################################
    public function testText()
    {
        $expected=
            '!&#34;#$%&&#39;()*+,-./0123456789:;<=>?@'.
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_&#39;'.
            'abcdefghijklmnopqrstuvwxyz{|}~  '.
            '!¢£¤¥|§¨©ª«¬-®-°±²³&#39;µ¶·¸¹º»¼½¾?'.
            'ÀÁÂÃAAAEÇÈÉÊEÌÍÎIDNÒÓÔÕOxOÙÚÛUYÞß'.
            'àáâãaaaeçèéêeìíîionòóôõo÷oùúûuyþyY'
        ;
        $this->eq($expected, FILTER::TEXT(self::INPUT_COMMOM()));
        
        $this->eq('', FILTER::TEXT(self::INPUT_EXTRA()));
        
        $this->eq("A\n\nB", FILTER::TEXT("A\n\n\nB"));
        $this->eq("A\n\nB", FILTER::TEXT("A\n\n \nB"));
        
        $this->eq($expected, FILTER::RUN(self::INPUT_COMMOM(), 'text'));
    }
    //##########################################################################
    public function testUrl()
    {
        $expected=
            '0123456789-'.
            'abcdefghijklmnopqrstuvwxyz-'.
            'abcdefghijklmnopqrstuvwxyz-'.
            'aaaaaa-ceeeeiiii-nooooo-ouuuuy-'.
            'aaaaaa-ceeeeiiiionooooo-ouuuuy-yy'
        ;
        $this->eq($expected, FILTER::URL(self::INPUT_COMMOM()));
        
        $this->eq('', FILTER::URL(self::INPUT_EXTRA()));
        
        $this->eq('url-url', FILTER::URL('- UrL-_-url-- - '));
        
        $this->eq($expected, FILTER::RUN(self::INPUT_COMMOM(), 'url'));
    }
    //##########################################################################
}
