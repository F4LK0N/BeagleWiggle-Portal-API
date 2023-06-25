<?
use App\App;

class NewsViewTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('news');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET  = [];
    }
    //##########################################################################
    public function testInputInvalid()
    {
        App::RUN("/ADM/News/View/0");
        $result = App::RESULT();
        
        $this->rx('/.*(\'id\').*(\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    //##########################################################################
    //##########################################################################
    public function testTransactionNotFound()
    {
        App::RUN("/ADM/News/View/1");
        $result = App::RESULT();
        
        $this->rx('/.*(Not found\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    
    public function testTransactionFind()
    {
        $_POST['id_category']=1;
        $_POST['url']="news-url";
        $_POST['title']="News Title";
        $_POST['description']="News Description";
        $_POST['content']="News Content";
        $_POST['source_name']="Source Name";
        $_POST['source_url']="source-url";
        App::RUN("/ADM/News/Add/");
        
        
        App::RUN("/ADM/News/View/1");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        
        $this->eq(1, $news->id);
        $this->eq(0, $news->published);
        $this->eq(0, $news->step);
        $this->eq(1, $news->version);
        $this->eq('news-url', $news->url);
        $this->eq('News Title', $news->cover_title);
        $this->eq('News Title', $news->title);
        $this->eq('News Description', $news->cover_description);
        $this->eq('News Description', $news->description);
        $this->eq('News Content', $news->content);
        $this->eq('Source Name', $news->source_name);
        $this->eq('source-url', $news->source_url);
        
        $this->assertIsInt($news->ts_created);
        $this->tr(($news->ts_created > 0));
        $this->assertIsInt($news->ts_modified);
        $this->tr(($news->ts_modified > 0));
    }
}
