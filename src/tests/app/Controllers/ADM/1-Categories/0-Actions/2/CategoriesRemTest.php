<?
use App\App;

class CategoriesRemTest extends FknTestCase
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
    public function testInputInvalid()
    {
        App::RUN("/ADM/Categories/Rem/0");
        $result = App::RESULT();
        
        $this->rx('/.*(Input error\!).*/', $result->message());
        $this->rx('/.*(\'id\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'id\' min value must be \'1\'\!).*/', $result->message());
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    public function testTransactionNotFound()
    {
        App::RUN("/ADM/Categories/Rem/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(Not found\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testTransactionDelete()
    {
        $_POST['name']="CategoryA";
        App::RUN("/ADM/Categories/Add/");
        
        App::RUN("/ADM/Categories/Rem/1");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $category = $result->get('category');
        $this->assertIsInt(
            $category->ts_deleted
        );
        $this->tr(
            ($category->ts_deleted > 0)
        );
    }
    
    public function testTransactionAlreadyDeleted()
    {
        App::RUN("/ADM/Categories/Rem/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(Not found\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
}
