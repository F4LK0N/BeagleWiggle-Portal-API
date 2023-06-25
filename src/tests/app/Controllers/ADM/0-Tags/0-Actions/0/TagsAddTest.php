<?
use App\App;

class TagsAddTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('tags');
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
        App::RUN("/ADM/Tags/Add/");
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
        
        App::RUN("/ADM/Tags/Add/");
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
        $_POST['name']="TagName";
        
        App::RUN("/ADM/Tags/Add/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tag = $result->get('tag');
        $this->eq(1, $tag->id);
        $this->eq('TagName', $tag->name);
    }
    
    public function testTransactionInsertConstrain()
    {
        $_POST['name']="TagNameUnique";
        
        App::RUN("/ADM/Tags/Add/");
        App::RUN("/ADM/Tags/Add/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' already registered\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
}
