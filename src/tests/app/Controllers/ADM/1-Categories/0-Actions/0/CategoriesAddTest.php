<?
use App\App;

class CategoriesAddTest extends FknTestCase
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
    //##########################################################################
    //##########################################################################
    public function testInputsMissing()
    {
        App::RUN("/ADM/Categories/Add/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' is required\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testInputsInvalid()
    {
        $_POST['name']="*";
        
        App::RUN("/ADM/Categories/Add/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' min length must be \'3\').*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testTransactionInsert()
    {
        $_POST['name']="CategoryName";
        
        App::RUN("/ADM/Categories/Add/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $category = $result->get('category');
        $this->eq(1, $category->id);
        $this->eq('CategoryName', $category->name);
    }
    
    public function testTransactionInsertConstrain()
    {
        $_POST['name']="CategoryNameUnique";
        
        App::RUN("/ADM/Categories/Add/");
        App::RUN("/ADM/Categories/Add/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' already registered\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
}
