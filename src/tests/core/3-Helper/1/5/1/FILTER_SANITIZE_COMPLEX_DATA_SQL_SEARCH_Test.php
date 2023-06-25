<?
use Core\Helper\FILTER;

class FILTER_SANITIZE_COMPLEX_DATA_SQL_SEARCH_Test extends FknTestCase
{
    //##########################################################################
    public function testEmpty()
    {
        $result = FILTER::SQL_SEARCH('');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH(' ');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH('  ');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH('|');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH(' |');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH('| ');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH(' | ');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH(' ` ');
        $this->eq('', $result);
        
        $result = FILTER::SQL_SEARCH('| |');
        $this->eq('', $result);
    }
    //##########################################################################
    public function testTerms()
    {
        $result = FILTER::SQL_SEARCH('A');
        $this->eq('A', $result);
        
        $result = FILTER::SQL_SEARCH('Aa');
        $this->eq('Aa', $result);
        
        $result = FILTER::SQL_SEARCH('a');
        $this->eq('a', $result);
        
        $result = FILTER::SQL_SEARCH(' a');
        $this->eq('a', $result);
        
        $result = FILTER::SQL_SEARCH('a ');
        $this->eq('a', $result);
        
        $result = FILTER::SQL_SEARCH(' a ');
        $this->eq('a', $result);
        
        $result = FILTER::SQL_SEARCH('  a ');
        $this->eq('a', $result);
        
        $result = FILTER::SQL_SEARCH('a B');
        $this->eq('a B', $result);
        
        $result = FILTER::SQL_SEARCH('  a   b  ');
        $this->eq('a b', $result);
        
        $result = FILTER::SQL_SEARCH("  Test1, Test2, D'Água");
        $this->eq('Test1 Test2 DAgua', $result);
    }
    //##########################################################################
}
