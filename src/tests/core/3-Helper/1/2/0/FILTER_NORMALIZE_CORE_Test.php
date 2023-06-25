<?
use Core\Helper\FILTER;

class FILTER_NORMALIZE_CORE_Test extends FknTestCase
{
    //##########################################################################
    //### SPACES ###
    //##########################################################################
    public function testSpaces()
    {
        $this->eq("A B", FILTER::NORMALIZE_SPACES("A\tB"));
        $this->eq("A  B", FILTER::NORMALIZE_SPACES("A   B"));
        $this->eq("A\nB", FILTER::NORMALIZE_SPACES("A\nB"));
        
        $this->eq("A\nB", FILTER::RUN("A\nB", 'normalize-spaces'));
    }
    //##########################################################################
    //### BREAKS ###
    //##########################################################################
    public function testBreaks()
    {
        $this->eq("A\nB", FILTER::NORMALIZE_BREAKS("A\nB"));
        $this->eq("A\nB", FILTER::NORMALIZE_BREAKS("A\rB"));
        
        $this->eq("A\nB", FILTER::NORMALIZE_BREAKS("A\r\nB"));
        
        $this->eq("A\n\nB", FILTER::NORMALIZE_BREAKS("A\r\n\r\nB"));
        
        $this->eq("A\n\nB", FILTER::RUN("A\r\n\r\nB", 'normalize-breaks'));
    }
    //##########################################################################
    public function testBreaksTrailing()
    {
        $this->eq("A\n B", FILTER::NORMALIZE_BREAKS("A \n B"));
        $this->eq("A\n B", FILTER::NORMALIZE_BREAKS("A\t \n B"));
        $this->eq("A\n B", FILTER::NORMALIZE_BREAKS("A \t\n B"));
    }
    //##########################################################################
    public function testBreaksTriple()
    {
        $this->eq("A\n\nB", FILTER::NORMALIZE_BREAKS("A\n\n\nB"));
        $this->eq("A\n\nB", FILTER::NORMALIZE_BREAKS("A\n \n \nB"));
    }
}
