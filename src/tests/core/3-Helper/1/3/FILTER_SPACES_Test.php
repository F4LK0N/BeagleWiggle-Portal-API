<?
use Core\Helper\FILTER;

class FILTER_SPACES_Test extends FknTestCase
{
    public function testDoubleBreaks()
    {
        $this->eq("A\nB", FILTER::DOUBLE_BREAKS("A\n\nB"));
        $this->eq("A\nB", FILTER::DOUBLE_BREAKS("A\n\n\nB"));
        
        $this->eq("A\nB", FILTER::RUN("A\n\n\nB", 'double-breaks'));
    }
    
    public function testDoubleSpaces()
    {
        $this->eq("A B", FILTER::DOUBLE_SPACES("A  B"));
        $this->eq("A B", FILTER::DOUBLE_SPACES("A   B"));
        $this->eq("A\n B", FILTER::DOUBLE_SPACES("A\n   B"));
        
        $this->eq("A\n B", FILTER::RUN("A\n   B", 'double-spaces'));
    }
    
    public function testBreaks()
    {
        $this->eq("A B", FILTER::BREAKS("A\nB"));
        $this->eq("A  B", FILTER::BREAKS("A\n\nB"));
        $this->eq("A   B", FILTER::BREAKS("A\n\n\nB"));
        
        $this->eq("A   B", FILTER::RUN("A\n\n\nB", 'breaks'));
    }
    
    public function testSpaces()
    {
        $this->eq("AB", FILTER::SPACES("A B"));
        $this->eq("AB", FILTER::SPACES("A  B"));
        $this->eq("AB", FILTER::SPACES("A   B"));
        
        $this->eq("AB", FILTER::RUN("A   B", 'spaces'));
    }
}
