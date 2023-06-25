<?
use App\App;

class CategoriesViewTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('categories');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET  = [];
    }
    //##########################################################################
    //##########################################################################
    //##########################################################################
    public function testTransactionNotFound()
    {
        App::RUN("/ADM/Categories/View/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(Not found\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    /**
     * @depends testTransactionNotFound
     */
    public function testTransactionFind()
    {
        $_POST['name']="CategoryA";
        App::RUN("/ADM/Categories/Add/");
        
        App::RUN("/ADM/Categories/View/1");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $category = $result->get('category');
        $this->eq(1, $category->id);
        $this->eq('CategoryA', $category->name);
        $this->assertIsInt($category->ts_created);
        $this->tr(($category->ts_created > 0));
        $this->assertIsInt($category->ts_modified);
        $this->tr(($category->ts_modified > 0));
    }
}
