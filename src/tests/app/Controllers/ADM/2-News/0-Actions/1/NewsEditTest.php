<?
use App\App;

class NewsEditTest extends FknTestCase
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
        App::RUN("/ADM/News/Edit/1");
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
        
        App::RUN("/ADM/News/Edit/1");
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
        
        App::RUN("/ADM/News/Edit/0");
        $result = App::RESULT();
        
        $this->rx('/.*(Input error\!).*/', $result->message());
        $this->rx('/.*(\'id\').*/', $result->message());
        $this->rx('/.*(\'id_category\').*/', $result->message());
        $this->rx('/.*(\'url\').*/', $result->message());
        $this->rx('/.*(\'cover_title\').*/', $result->message());
        $this->rx('/.*(\'cover_description\').*/', $result->message());
        $this->rx('/.*(\'title\').*/', $result->message());
        $this->rx('/.*(\'description\').*/', $result->message());
        $this->rx('/.*(\'content\').*/', $result->message());
        $this->rx('/.*(\'source_name\').*/', $result->message());
        $this->rx('/.*(\'source_url\').*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testNotFound()
    {
        $_POST['id_category']=1;
        $_POST['url']='news-url';
        $_POST['cover_title']='News Title';
        $_POST['title']='News Title';
        
        App::RUN("/ADM/News/Edit/1");
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
        self::DB()->execute("UPDATE `news` SET `step`=1");
        
        $_POST['id_category']=2;
        $_POST['url']="news-title-step";
        $_POST['cover_title']="News Cover Title Step";
        $_POST['title']="News Title Step";
        App::RUN("/ADM/News/Edit/1");
        $result = App::RESULT();
        
        $this->rx('/.*(Rule error\!).*/', $result->message());
        $this->rx('/.*(\'DRAFT\').*/', $result->message());
        $this->eq(eSTATE::ERROR, $result->state());
    }
    //##########################################################################
    public function testTransactionUpdateMinimal()
    {
        $_POST['id_category']=1;
        $_POST['title']="News Title Added 2";
        App::RUN("/ADM/News/Add/");
        
        $_POST=[];
        $_POST['id_category']=2;
        $_POST['title']="News Title Minimal";
        App::RUN("/ADM/News/Edit/2");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->eq(2, $news->id);
        $this->eq(2, $news->id_category);
        $this->eq(0, $news->published);
        $this->eq(0, $news->step);
        $this->eq(2, $news->version);
        $this->eq('news-title-minimal', $news->url);
        $this->eq('News Title Minimal', $news->cover_title);
        $this->eq('News Title Minimal', $news->title);
        $this->eq('', $news->cover_description);
        $this->eq('', $news->description);
        $this->eq('', $news->content);
        $this->eq('', $news->source_name);
        $this->eq('', $news->source_url);
        
        $this->assertIsInt($news->ts_created);
        $this->tr(($news->ts_created > 0));
        $this->assertIsInt($news->ts_modified);
        $this->tr(($news->ts_modified > 0));
    }
    //##########################################################################
    public function testTransactionUpdateFull()
    {
        $_POST['id_category']=1;
        $_POST['title']="News Title Added 3";
        App::RUN("/ADM/News/Add/");
        
        $_POST['id_category']=30;
        $_POST['url']="news-url-full";
        
        $_POST['cover_title']="News Cover Title Full";
        $_POST['cover_description']="News Cover Description Full";
        
        $_POST['title']="News Title Full";
        $_POST['description']="News Description Full";
        $_POST['content']="News Content Full";
        
        $_POST['source_name']="Source Name";
        $_POST['source_url']="source-url";
        App::RUN("/ADM/News/Edit/3");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $news = $result->get('news');
        $this->eq(3, $news->id);
        $this->eq(30, $news->id_category);
        $this->eq(0, $news->published);
        $this->eq(0, $news->step);
        $this->eq(2, $news->version);
        $this->eq('news-url-full', $news->url);
        $this->eq('News Cover Title Full', $news->cover_title);
        $this->eq('News Title Full', $news->title);
        $this->eq('News Cover Description Full', $news->cover_description);
        $this->eq('News Description Full', $news->description);
        $this->eq('Source Name', $news->source_name);
        $this->eq('source-url', $news->source_url);
        
        $this->assertIsInt($news->ts_created);
        $this->tr(($news->ts_created > 0));
        $this->assertIsInt($news->ts_modified);
        $this->tr(($news->ts_modified > 0));
    }
    //##########################################################################
    //public function testTransactionInsertConstrainTitle()
    //{
    //    $_POST['id_category']=1;
    //    $_POST['cover_title']="Cover Title 100";
    //    $_POST['title']="News Title";
    //    App::RUN("/ADM/News/Add/");
    //    
    //    $_POST['id_category']=1;
    //    $_POST['cover_title']="Cover Title 200";
    //    $_POST['title']="News Title";
    //    App::RUN("/ADM/News/Add/");
    //    $result = App::RESULT();
    //    
    //    $this->rx('/.*(\'title\' already registered\!).*/', $result->message());
    //    $this->eq(eSTATE::ERROR, $result->state());
    //}
    ////##########################################################################
    //public function testTransactionInsertConstrainCoverTitle()
    //{
    //    $_POST['id_category']=1;
    //    $_POST['cover_title']="Cover Title";
    //    $_POST['title']="News Title 100";
    //    $_POST['url']="news-url-1";
    //    App::RUN("/ADM/News/Add/");
    //    
    //    $_POST['id_category']=1;
    //    $_POST['cover_title']="Cover Title";
    //    $_POST['title']="News Title 200";
    //    $_POST['url']="news-url-2";
    //    App::RUN("/ADM/News/Add/");
    //    $result = App::RESULT();
    //    
    //    $this->rx('/.*(\'cover_title\' already registered\!).*/', $result->message());
    //    $this->eq(eSTATE::ERROR, $result->state());
    //}
    ////##########################################################################
    //public function testTransactionInsertConstrainUrl()
    //{
    //    $_POST['id_category']=1;
    //    $_POST['cover_title']="Cover Title 111";
    //    $_POST['title']="News Title 111";
    //    $_POST['url']="news-url-equal";
    //    App::RUN("/ADM/News/Add/");
    //    
    //    $_POST['id_category']=1;
    //    $_POST['cover_title']="Cover Title 222";
    //    $_POST['title']="News Title 222";
    //    $_POST['url']="news-url-equal";
    //    App::RUN("/ADM/News/Add/");
    //    $result = App::RESULT();
    //    
    //    $this->rx('/.*(\'url\' already registered\!).*/', $result->message());
    //    $this->eq(eSTATE::ERROR, $result->state());
    //}
    //##########################################################################
}
