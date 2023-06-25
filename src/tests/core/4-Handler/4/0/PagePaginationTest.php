<?
use Core\Inputs;
use Core\Page;

class PagePaginationTest extends FknTestCase
{
    private ?Inputs $inputs = null;
    private ?Page   $page   = null;
    
    
    //##########################################################################
    //### SET INPUTS ###
    //##########################################################################
    /**
     * @dataProvider providerSetInputs
     */
    public function testSetInputs($page, $expectedPage, $pageSize, $expectedPageSize)
    {
        $_POST = [
            'page'     => $page,
            'pageSize' => $pageSize,
        ];
        
        $this->inputs = new Inputs();
        $this->inputs->add([
            'page'     => Page::$FIELDS['page'],
            'pageSize' => Page::$FIELDS['pageSize'],
        ]);
        $this->inputs->run();
        
        $this->page = new Page();
        $this->page->setInputs($this->inputs);
        
        
        $this->eq($expectedPage, $this->page->getPage());
        $this->eq($expectedPageSize, $this->page->getPageSize());
    }
    static public function providerSetInputs()
    {
        return [
            [1, 1, 2, 2],
            [2, 2, 3, 3],
            [3, 3, 10, 10],
            [10, 10, 90, 90],
        ];
    }
    //##########################################################################
    //### SET TOTAL ###
    //##########################################################################
    /**
     * @dataProvider providerSetTotal
     */
    public function testSetTotal($page, $pageSize, $pages, $total)
    {
        $_POST = [
            'page'     => $page['value'],
            'pageSize' => $pageSize['value'],
        ];
        
        $this->inputs = new Inputs();
        $this->inputs->add([
            'page'     => Page::$FIELDS['page'],
            'pageSize' => Page::$FIELDS['pageSize'],
        ]);
        $this->inputs->run();
        
        $this->page = new Page();
        $this->page->setInputs($this->inputs);
        $this->page->setTotal($total['value']);
        
        $this->eq($page['expected'], $this->page->getPage());
        $this->eq($pageSize['expected'], $this->page->getPageSize());
        $this->eq($pages['expected'], $this->page->getPages());
        $this->eq($total['expected'], $this->page->getTotal());
    }
    static public function providerSetTotal()
    {
        return [
            [
                ['value' => 1,  'expected' => 1],
                ['value' => 10, 'expected' => 10],
                ['value' => 1,  'expected' => 1],
                ['value' => 0,  'expected' => 0],
            ],
            [
                ['value' => 1, 'expected' => 1],
                ['value' => 2, 'expected' => 2],
                ['value' => 1, 'expected' => 1],
                ['value' => 1, 'expected' => 1],
            ],
            [
                ['value' => 9, 'expected' => 2],
                ['value' => 2, 'expected' => 2],
                ['value' => 2, 'expected' => 2],
                ['value' => 3, 'expected' => 3],
            ],
            [
                ['value' => 9,  'expected' => 3],
                ['value' => 5,  'expected' => 5],
                ['value' => 3,  'expected' => 3],
                ['value' => 15, 'expected' => 15],
            ],
            [
                ['value' => 9,  'expected' => 4],
                ['value' => 5,  'expected' => 5],
                ['value' => 4,  'expected' => 4],
                ['value' => 16, 'expected' => 16],
            ],
        ];
    }
    
    
}
