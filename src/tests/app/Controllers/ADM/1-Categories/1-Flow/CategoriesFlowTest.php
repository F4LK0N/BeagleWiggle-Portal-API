<?
use App\App;

class CategoriesFlowTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('categories');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET = [];
    }
    //##########################################################################
    public function testAdd()
    {
        for($i=1; $i<=20; $i++)
        {
            $_POST = [];
            $_POST['name']=self::FAKER()->name() ." ".rand(10,99);
        
            App::RUN("/ADM/Categories/Add/");
            $result = App::RESULT();
            
            $this->eq(eSTATE::SUCCESS, $result->state());
            
            $category = $result->get('category');
            $this->eq($i, $category->id);
        }
    }
    //##########################################################################
    /**
     * @depends testAdd
     * @dataProvider providerEditIds 
     * */
    public function testEdit($id)
    {
        $_POST = [];
        $_POST['name']="Category ($id) Edited ";
        App::RUN("/ADM/Categories/Edit/$id");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $category = $result->get('category');
        $this->eq($id, $category->id);
        $this->eq("Category ($id) Edited", $category->name);
    }
    static public function providerEditIds()
    {
        return [
            [5],
            [10],
            [12],
        ];
    }
    //##########################################################################
    /**
     * @depends testEdit
     */
    public function testDelete()
    {
        App::RUN("/ADM/Categories/Rem/4");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $category = $result->get('category');
        $this->assertIsInt($category->ts_deleted);
        $this->tr(($category->ts_deleted > 0));
    }
    //##########################################################################
    /**
     * @depends testDelete
     */
    public function testView()
    {
        App::RUN("/ADM/Categories/View/5");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $category = $result->get('category');
        $this->eq(5, $category->id);
        $this->eq('Category (5) Edited', $category->name);
        $this->assertIsInt($category->ts_created);
        $this->tr(($category->ts_created > 0));
        $this->assertIsInt($category->ts_modified);
        $this->tr(($category->ts_modified > 0));
    }
    //##########################################################################
    /**
     * @depends testView
     */
    public function testList()
    {
        $_POST['page']=1;
        $_POST['pageSize']=5;
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $categories = $result->get('categories');
        $this->eq(1, $categories->getPage());
        $this->eq(5, $categories->getPageSize());
        $this->eq(4, $categories->getPages());
        $this->eq(19, $categories->getTotal());
    }
    
}
