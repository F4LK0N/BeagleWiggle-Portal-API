<?
use App\App;

class NewsPublishTest extends FknTestCase
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
        App::RUN("/ADM/News/Publish/0");
        $result = App::RESULT();
        
        $this->rx('/.*(\'id\').*(\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testNotFound()
    {
        App::RUN("/ADM/News/Publish/1");
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
        
        App::RUN("/ADM/News/Publish/1");
        $result = App::RESULT();
        
        $this->rx('/.*(Rule error\!).*/', $result->message());
        $this->rx('/.*(Invalid action for current step\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testPublish()
    {
        $_POST['id_category']=30;
        $_POST['url']="news-approved";
        $_POST['title']="News Title Approved";
        $_POST['description']="News Description Approved";
        $_POST['content']="News Content Approved";
        $_POST['source_name']="Source Name";
        $_POST['source_url']="source-url-url";
        App::RUN("/ADM/News/Add/");
        App::RUN("/ADM/News/Complete/2");
        $_POST['approved']="1";
        App::RUN("/ADM/News/RevisionOrthographic/2");
        $_POST['approved']="1";
        App::RUN("/ADM/News/RevisionContent/2");
        
        App::RUN("/ADM/News/Publish/2");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->eq(1, $news->published);
        $this->eq(70, $news->step);
        
        $this->assertIsInt($news->ts_published);
        $this->tr(($news->ts_published > 0));
    }
    //##########################################################################
}
