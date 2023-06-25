<?
use Core\Helper\MATH;

class MATH_Test extends FknTestCase
{
    public function testMax()
    {
        $this->eq(3, MATH::MAX(2, 3));
        $this->eq(3, MATH::MAX(3, 2));
        
        $this->eq(3.0, MATH::MAX(2.9, 3.0));
        $this->eq(3.0, MATH::MAX(3.0, 2.9));
    }
    
    public function testMin()
    {
        $this->eq(2, MATH::MIN(2, 3));
        $this->eq(2, MATH::MIN(3, 2));
        
        $this->eq(3.0, MATH::MIN(3.1, 3.0));
        $this->eq(3.0, MATH::MIN(3.0, 3.1));
    }

    public function testBetween()
    {
        $this->eq(2, MATH::BETWEEN(1, 2, 4));
        $this->eq(2, MATH::BETWEEN(2, 2, 4));
        $this->eq(3, MATH::BETWEEN(3, 2, 4));
        $this->eq(4, MATH::BETWEEN(4, 2, 4));
        $this->eq(4, MATH::BETWEEN(5, 2, 4));
        
        $this->eq(2, MATH::BETWEEN(1, 4, 2));
        $this->eq(2, MATH::BETWEEN(2, 4, 2));
        $this->eq(3, MATH::BETWEEN(3, 4, 2));
        $this->eq(4, MATH::BETWEEN(4, 4, 2));
        $this->eq(4, MATH::BETWEEN(5, 4, 2));
    }
    
    public function testRangeAdjust()
    {
        $value1=10;
        $value2=20;
        
        MATH::RANGE_ADJUST($value1, $value2);
        $this->eq(10, $value1);
        $this->eq(20, $value2);
        
        MATH::RANGE_ADJUST($value2, $value1);
        $this->eq(10, $value2);
        $this->eq(20, $value1);
    }
}
