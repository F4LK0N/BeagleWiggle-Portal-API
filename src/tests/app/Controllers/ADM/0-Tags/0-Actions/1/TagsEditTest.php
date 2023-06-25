<?
use App\App;

class TagsEditTest extends FknTestCase
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
        App::RUN("/ADM/Tags/Edit/1");
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
        
        App::RUN("/ADM/Tags/Edit/1");
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
        $_POST['name']="TagName";
        
        App::RUN("/ADM/Tags/Edit/1");
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
        $_POST['name']="TagName";
        App::RUN("/ADM/Tags/Add/");
        
        $_POST['name']="TagNameEdited";
        App::RUN("/ADM/Tags/Edit/1");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tag = $result->get('tag');
        $this->eq(1, $tag->id);
        $this->eq('TagNameEdited', $tag->name);
    }
    
    public function testTransactionEditConstrain()
    {
        $_POST['name']="TagName2";
        App::RUN("/ADM/Tags/Add/");
        
        $_POST['name']="TagNameEdited";
        App::RUN("/ADM/Tags/Edit/2");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'name\' already registered\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
}
