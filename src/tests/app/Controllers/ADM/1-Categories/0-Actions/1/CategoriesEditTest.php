<?
use App\App;

class CategoriesEditTest extends FknTestCase
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
        App::RUN("/ADM/Categories/Edit/1");
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
        
        App::RUN("/ADM/Categories/Edit/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' min length must be \'3\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testTransactionNotFound()
    {
        $_POST['name']="CategoryName";
        
        App::RUN("/ADM/Categories/Edit/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(Not found\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testTransactionEdit()
    {
        $_POST['name']="CategoryName";
        App::RUN("/ADM/Categories/Add/");
        
        $_POST['name']="CategoryNameEdited";
        App::RUN("/ADM/Categories/Edit/1");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $category = $result->get('category');
        $this->eq(1, $category->id);
        $this->eq('CategoryNameEdited', $category->name);
    }
    
    public function testTransactionEditConstrain()
    {
        $_POST['name']="CategoryName2";
        App::RUN("/ADM/Categories/Add/");
        
        $_POST['name']="CategoryNameEdited";
        App::RUN("/ADM/Categories/Edit/2");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' already registered\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
}
