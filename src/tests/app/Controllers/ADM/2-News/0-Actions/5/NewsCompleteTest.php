<?
use App\App;

class NewsCompleteTest extends FknTestCase
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
        App::RUN("/ADM/News/Complete/0");
        $result = App::RESULT();
        
        $this->rx('/.*(\'id\').*(\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testNotFound()
    {
        App::RUN("/ADM/News/Complete/1");
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
        
        App::RUN("/ADM/News/Complete/1");
        $result = App::RESULT();
        
        $this->rx('/.*(Rule error\!).*/', $result->message());
        $this->rx('/.*(Invalid action for current step\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    public function testRuleWorkflowFields()
    {
        $_POST['id_category']=2;
        $_POST['title']="News Title Added 2";
        App::RUN("/ADM/News/Add/");
        
        App::RUN("/ADM/News/Complete/2");
        $result = App::RESULT();
        
        $this->rx('/.*(Rule error\!).*/', $result->message());
        $this->rx('/.*(\'description\').*(\!).*/', $result->message());
        $this->rx('/.*(\'cover_description\').*(\!).*/', $result->message());
        $this->rx('/.*(\'content\').*(\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    public function testRuleWorkflowAllFields()
    {
        $_POST['id_category']=2;
        $_POST['title']="News Title Added 3";
        App::RUN("/ADM/News/Add/");
        self::DB()->execute("
            UPDATE
                `news`
            SET 
                `step` = 1,
                `url`='',
                `cover_title`='',
                `cover_description`='',
                `title`='',
                `description`='',
                `content`='',
                `source_name`='',
                `source_url`='source-url'
            WHERE
                `id`= 3
            ");
        
        App::RUN("/ADM/News/Complete/3");
        $result = App::RESULT();
        
        $this->rx('/.*(Rule error\!).*/', $result->message());
        $this->rx('/.*(\'url\').*(\!).*/', $result->message());
        $this->rx('/.*(\'title\').*(\!).*/', $result->message());
        $this->rx('/.*(\'description\').*(\!).*/', $result->message());
        $this->rx('/.*(\'cover_title\').*(\!).*/', $result->message());
        $this->rx('/.*(\'cover_description\').*(\!).*/', $result->message());
        $this->rx('/.*(\'content\').*(\!).*/', $result->message());
        $this->rx('/.*(\'source_name\').*(\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testComplete()
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
        
        App::RUN("/ADM/News/Complete/4");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->eq(10, $news->step);
    }
    //##########################################################################
}
