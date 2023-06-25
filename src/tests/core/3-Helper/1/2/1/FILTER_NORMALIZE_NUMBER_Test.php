<?
use Core\Helper\FILTER;

class FILTER_NORMALIZE_NUMBER_Test extends FknTestCase
{
    //##########################################################################
    //### NUMBER ###
    //##########################################################################
    public function testNumber()
    {
        $this->eq('-.0123456789', FILTER::NORMALIZE_NUMBER(self::INPUT_COMMOM()));
        $this->eq('', FILTER::NORMALIZE_NUMBER(self::INPUT_EXTRA()));
        
        $this->eq('-.0123456789', FILTER::RUN(self::INPUT_COMMOM(), 'normalize-number'));
    }
}
