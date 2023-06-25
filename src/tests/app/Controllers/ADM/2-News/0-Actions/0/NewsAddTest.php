<?
use App\App;

class NewsAddTest extends FknTestCase
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
    public function testInputsMissing()
    {
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->rx('/.*(Input error\!).*/', $result->message());
        $this->rx('/.*(\'id_category\' is required\!).*/', $result->message());
        $this->rx('/.*(\'title\' is required\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testInputsMissingSourceName()
    {
        $_POST['id_category']=1;
        $_POST['title']='Title title title';
        $_POST['source_url']='Title title title';
        
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->rx('/.*(Input error\!).*/', $result->message());
        $this->rx('/.*(\'source_name\' is required\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testInputInvalid()
    {
        $_POST['id_category']=0;
        $_POST['url']='u';
        $_POST['cover_title']='T';
        $_POST['cover_description']='D';
        $_POST['title']='T';
        $_POST['description']='D';
        $_POST['content']='C';
        $_POST['source_name']='N';
        $_POST['source_url']='S';
        
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->rx('/.*(Input error\!).*/', $result->message());
        $this->rx('/.*(\'id_category\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'url\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'cover_title\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'cover_description\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'title\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'description\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'content\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'source_name\' invalid\!).*/', $result->message());
        $this->rx('/.*(\'source_url\' invalid\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testTransactionInsertMinimal()
    {
        $_POST['id_category']=1;
        $_POST['title']="News Title Minimal";
        
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->eq(1, $news->id);
        $this->eq(0, $news->published);
        $this->eq(0, $news->step);
        $this->eq(1, $news->version);
        $this->eq('news-title-minimal', $news->url);
        $this->eq('News Title Minimal', $news->cover_title);
        $this->eq('News Title Minimal', $news->title);
        $this->eq('', $news->cover_description);
        $this->eq('', $news->description);
        $this->eq('', $news->content);
        
        $this->assertIsInt($news->ts_created);
        $this->tr(($news->ts_created > 0));
        $this->assertIsInt($news->ts_modified);
        $this->tr(($news->ts_modified > 0));
    }
    //##########################################################################
    public function testTransactionInsertFull()
    {
        $_POST['id_category']=1;
        $_POST['url']="news-title-complete-url";
        $_POST['cover_title']="Cover Title Complete";
        $_POST['cover_description']="Cover Description Complete";
        $_POST['title']="News Title Complete";
        $_POST['description']="News Description Complete";
        $_POST['content']="News Content Complete";
        $_POST['source_name']="Source Name";
        $_POST['source_url']="source-url";
        
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->eq(2, $news->id);
        $this->eq(0, $news->published);
        $this->eq(0, $news->step);
        $this->eq(1, $news->version);
        $this->eq('news-title-complete-url', $news->url);
        $this->eq('Cover Title Complete', $news->cover_title);
        $this->eq('Cover Description Complete', $news->cover_description);
        $this->eq('News Title Complete', $news->title);
        $this->eq('News Description Complete', $news->description);
        $this->eq('News Content Complete', $news->content);
        $this->eq('Source Name', $news->source_name);
        $this->eq('source-url', $news->source_url);
        
        $this->assertIsInt($news->ts_created);
        $this->tr(($news->ts_created > 0));
        $this->assertIsInt($news->ts_modified);
        $this->tr(($news->ts_modified > 0));
    }
    //##########################################################################
    public function testTransactionInsertConstrainTitle()
    {
        $_POST['id_category']=1;
        $_POST['cover_title']="Cover Title 100";
        $_POST['title']="News Title";
        App::RUN("/ADM/News/Add/");
        
        $_POST['id_category']=1;
        $_POST['cover_title']="Cover Title 200";
        $_POST['title']="News Title";
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->rx('/.*(\'title\' already registered\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testTransactionInsertConstrainCoverTitle()
    {
        $_POST['id_category']=1;
        $_POST['cover_title']="Cover Title";
        $_POST['title']="News Title 100";
        $_POST['url']="news-url-1";
        App::RUN("/ADM/News/Add/");
        
        $_POST['id_category']=1;
        $_POST['cover_title']="Cover Title";
        $_POST['title']="News Title 200";
        $_POST['url']="news-url-2";
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->rx('/.*(\'cover_title\' already registered\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testTransactionInsertConstrainUrl()
    {
        $_POST['id_category']=1;
        $_POST['cover_title']="Cover Title 111";
        $_POST['title']="News Title 111";
        $_POST['url']="news-url-equal";
        App::RUN("/ADM/News/Add/");
        
        $_POST['id_category']=1;
        $_POST['cover_title']="Cover Title 222";
        $_POST['title']="News Title 222";
        $_POST['url']="news-url-equal";
        App::RUN("/ADM/News/Add/");
        $result = App::RESULT();
        
        $this->rx('/.*(\'url\' already registered\!).*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
}
