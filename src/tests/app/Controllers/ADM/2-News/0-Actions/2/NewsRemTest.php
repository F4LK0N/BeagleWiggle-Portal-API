<?
use App\App;

class NewsRemTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('news');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET = [];
    }
    //##########################################################################
    public function testInputInvalid()
    {
        App::RUN("/ADM/News/Rem/0");
        $result = App::RESULT();
        
        $this->rx('/.*(\'id\').*(\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testNotFound()
    {
        App::RUN("/ADM/News/Rem/1");
        $result = App::RESULT();
        
        $this->eq('Not found!', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testRuleWorkflowStep()
    {
        $_POST['id_category']=1;
        $_POST['title']="News Title Added";
        App::RUN("/ADM/News/Add/");
        self::DB()->execute("
            UPDATE
                `news`
            SET 
                `step` = 1
            WHERE
                `id`= 1
            ");
        
        App::RUN("/ADM/News/Rem/1");
        $result = App::RESULT();
        
        $this->rx('/.*(Rule error\!).*/', $result->message());
        $this->rx('/.*(Invalid action for current step\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testRem()
    {
        $_POST['id_category']=30;
        $_POST['url']="news-url-complete";
        $_POST['cover_title']="News Cover Title Complete";
        $_POST['cover_description']="News Cover Description Complete";
        $_POST['title']="News Title Complete";
        $_POST['description']="News Description Complete";
        $_POST['content']="News Content Complete";
        $_POST['source_name']="Source Name";
        $_POST['source_url']="source-url-url";
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        App::RUN("/ADM/News/Rem/2");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->assertIsInt($news->ts_deleted);
        $this->tr(($news->ts_deleted > 0));
        $this->rx('/.*[DELETED].*/', $news->title);
        $this->rx('/.*[DELETED].*/', $news->cover_title);
        $this->rx('/.*[DELETED].*/', $news->url);
    }
    //##########################################################################
}
