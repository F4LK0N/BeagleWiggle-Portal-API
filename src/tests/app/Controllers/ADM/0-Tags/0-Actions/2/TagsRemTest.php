<?
use App\App;

class TagsRemTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('tags');
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
        App::RUN("/ADM/Tags/Rem/0");
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
        App::RUN("/ADM/Tags/Rem/1");
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
        $_POST['name']="TagA";
        App::RUN("/ADM/Tags/Add/");
        
        App::RUN("/ADM/Tags/Rem/1");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tag = $result->get('tag');
        $this->assertIsInt(
            $tag->ts_deleted
        );
        $this->tr(
            ($tag->ts_deleted > 0)
        );
    }
    
    public function testTransactionAlreadyDeleted()
    {
        App::RUN("/ADM/Tags/Rem/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(Not found\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
}
