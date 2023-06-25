<?
use Core\Helper\FILTER;

class FILTER_SECURITY_Test extends FknTestCase
{
    //##########################################################################
    //### FORBIDDEN ###
    //##########################################################################
    public function testForbidden()
    {
        for($mb_ord=0;$mb_ord<32;$mb_ord++){
            if($mb_ord===9 || $mb_ord===10 || $mb_ord===13){
                continue;
            }
            $this->eq(' ', FILTER::FORBIDDEN(mb_chr($mb_ord)));
        }
        
        
        $input="";
        for($mb_ord=0;$mb_ord<32;$mb_ord++){
            if($mb_ord===9 || $mb_ord===10 || $mb_ord===13){
                continue;
            }
            $input.=mb_chr($mb_ord);
        }
        $this->eq('                             ', FILTER::FORBIDDEN($input));
    }
    //##########################################################################
    /**
     * @dataProvider providerAsciiChars
     * @dataProvider providerAccents
     * @dataProvider providerSymbols
     * */
    public function testAllowed($value)
    {
        $this->eq($value, FILTER::FORBIDDEN($value));
    }
    static public function providerAsciiChars()
    {
        return[
            ["\t\n\r 0123456789 abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ"],
        ];
    }
    static public function providerAccents()
    {
        return[
            ["àáâãäå èéêë ìíîï òóôõöø ùúûü ýÿçñ ÀÁÂÃÄÅ ÈÉÊË ÌÍÎÏ ÒÓÔÕÖØ ÙÚÛÜ ÝŸÇÑ"],
        ];
    }
    static public function providerSymbols()
    {
        return[
            ["æ Æ ™ © ® ¢ £ ¥ €"],
        ];
    }
    //##########################################################################
    //### PHP ###
    //##########################################################################
    public function testPhp()
    {
        $this->eq("AB", FILTER::PHP("A<?phpB"));
        $this->eq("AB", FILTER::PHP("A<?PhpB"));
        $this->eq("AB", FILTER::PHP("A<?B"));
        $this->eq("AB", FILTER::PHP("A?>B"));
        $this->eq("AB", FILTER::PHP("A<?php?><??>B"));
        $this->eq("AB", FILTER::PHP("A<?php?<?><??>B"));
        $this->eq("A\nB", FILTER::PHP("A\n<?php?<?><??>B"));
        
        $this->eq("A\nB", FILTER::RUN("A\n<?php?<?><??>B", 'php'));
    }
    //##########################################################################
    public function testPhpEncode()
    {
        $this->eq("&#60;&#63;php", FILTER::PHP("<?php", true));
        $this->eq("&#60;&#63;", FILTER::PHP("<?", true));
        $this->eq("&#63;&#62;", FILTER::PHP("?>", true));
        $this->eq("A\n&#63;&#62;", FILTER::PHP("A\n?>", true));
        
        $this->eq("A\n&#63;&#62;", FILTER::RUN("A\n?>", 'php-encode'));
    }
    //##########################################################################
    //### QUOTES ###
    //##########################################################################
    public function testQuotes()
    {
        $this->eq(' ', FILTER::QUOTES("'", false));
        $this->eq(' ', FILTER::QUOTES('"', false));
        $this->eq(' ', FILTER::QUOTES('`', false));
        $this->eq(' ', FILTER::QUOTES('´', false));
        $this->eq('A\n B', FILTER::QUOTES('A\n´B', false));
        
        $this->eq('A\n B', FILTER::RUN('A\n´B', 'quotes'));
    }
    //##########################################################################
    public function testQuotesEncode()
    {
        $this->eq('&#34;', FILTER::QUOTES('"'));
        $this->eq('&#39;', FILTER::QUOTES("'"));
        $this->eq('&#39;', FILTER::QUOTES('`'));
        $this->eq('A\n&#39;B', FILTER::QUOTES('A\n´B'));
        
        $this->eq('A\n&#39;B', FILTER::RUN('A\n´B', 'quotes-encode'));
    }
    //##########################################################################
    //### SECURE ###
    //##########################################################################
    public function testSecure()
    {
        $this->eq(' ', FILTER::SECURE(chr(5)));
        
        $this->eq("AB", FILTER::SECURE("A<?phpB", false));
        $this->eq("&#60;&#63;", FILTER::SECURE("<?", true));
        
        $this->eq(' ', FILTER::SECURE("'", false, false));
        $this->eq('&#34;', FILTER::SECURE('"', false, true));
        
        $this->eq(' AB&#34;',           FILTER::SECURE(chr(5)."A<?B".'"', false, true));
        $this->eq(' AB ',               FILTER::SECURE(chr(5)."A<?B".'"', false, false));
        $this->eq(' A&#60;&#63;B&#34;', FILTER::SECURE(chr(5)."A<?B".'"', true, true));
        $this->eq(' A&#60;&#63;B ',     FILTER::SECURE(chr(5)."A<?B".'"', true, false));
        
        $this->eq(' AB&#34;',           FILTER::RUN(chr(5)."A<?B".'"', 'secure'));
        $this->eq(' AB ',               FILTER::RUN(chr(5)."A<?B".'"', 'secure-strip'));
        $this->eq(' A&#60;&#63;B&#34;', FILTER::RUN(chr(5)."A<?B".'"', 'secure-encode'));
    }
}
