<?
use App\App;

class TagsViewTest extends FknTestCase
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
    public function testTransactionNotFound()
    {
        App::RUN("/ADM/Tags/View/1");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(Not found\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testTransactionFind()
    {
        $_POST['name']="TagA";
        App::RUN("/ADM/Tags/Add/");
        
        App::RUN("/ADM/Tags/View/1");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tag = $result->get('tag');
        $this->eq(1, $tag->id);
        $this->eq('TagA', $tag->name);
        $this->assertIsInt($tag->ts_created);
        $this->tr(($tag->ts_created > 0));
        $this->assertIsInt($tag->ts_modified);
        $this->tr(($tag->ts_modified > 0));
    }
}
