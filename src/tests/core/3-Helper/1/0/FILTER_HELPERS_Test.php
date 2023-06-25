<?
use Core\Helper\FILTER;

class FILTER_HELPERS_Test extends FknTestCase
{
    //##########################################################################
    //### REPLACE ###
    //##########################################################################
    public function testReplaceByPass()
    {
        $this->eq('', FILTER::REPLACE('a', 'b', ''));
        $this->eq('abc', FILTER::REPLACE('', 'b', 'abc'));
        $this->eq('abc', FILTER::REPLACE('a', 'a', 'abc'));
    }
    public function testReplaceMaxIterations()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Max iterations!");
        FILTER::REPLACE('a', 'aa', 'abc');
    }
    public function testReplace()
    {
        $input="AaBb";
        $this->eq('AABb', FILTER::REPLACE('a', 'A', $input));
        $this->eq('aaBb', FILTER::REPLACE('Aa', 'aa', $input));
        $this->eq('AAbb', FILTER::REPLACE('aB', 'Ab', $input));
    }
    //##########################################################################
    //### REPLACE CI ###
    //##########################################################################
    public function testReplaceCiByPass()
    {
        $this->eq('', FILTER::REPLACE_CI('a', 'b', ''));
        $this->eq('abc', FILTER::REPLACE_CI('', 'b', 'abc'));
        $this->eq('abc', FILTER::REPLACE_CI('a', 'a', 'abc'));
    }
    public function testReplaceCiMaxIterations()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Max iterations!");
        FILTER::REPLACE_CI('A', 'aa', 'abc');
    }
    public function testReplaceCi()
    {
        $input="AaBb";
        $this->eq('__Bb', FILTER::REPLACE_CI('a', '_', $input));
        $this->eq('A__b', FILTER::REPLACE_CI('ab', '__', $input));
    }
    //##########################################################################
    //### REPLACE REGEX ###
    //##########################################################################
    public function testReplaceRegexByPass()
    {
        $this->eq('', FILTER::REPLACE_REGEX('/a/', 'b', ''));
        $this->eq('abc', FILTER::REPLACE_REGEX('', 'b', 'abc'));
    }
    public function testReplaceRegexMaxIterations()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Max iterations!");
        FILTER::REPLACE_REGEX('/a/', 'aa', 'abc');
    }
    public function testReplaceRegex()
    {
        $input="AaBb";
        $this->eq('A_Bb', FILTER::REPLACE_REGEX('/a/', '_', $input));
        $this->eq('A_B_', FILTER::REPLACE_REGEX('/(a|b)/', '_', $input));
        $this->eq('__Bb', FILTER::REPLACE_REGEX('/a/i', '_', $input));
    }
}
