<?
use App\App;

class NewsFlowFillTest extends FknTestCase
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
    public function testAddAndPublish()
    {
        for($i=0; $i<60; $i++){
            
            $random = self::FAKER()->name()." ".self::FAKER()->lastName()." ".rand(10, 99);
            
            $_POST=[];
            $_POST['id_category']=rand(1,20);
            //$_POST['url']="news-approved";
            $_POST['title']=$random." News Title";
            $_POST['description']="".$random." News Description";
            $_POST['content']="".$random." News Content ".$random;
            //$_POST['source_name']="Source Name";
            //$_POST['source_url']="source-url-url";
            App::RUN("/ADM/News/Add/");
            $result = App::RESULT();
            $news = $result->get('news');
            $id = $news->id;
            
            App::RUN("/ADM/News/Complete/$id");
            $_POST['approved']="1";
            App::RUN("/ADM/News/RevisionOrthographic/$id");
            $_POST['approved']="1";
            App::RUN("/ADM/News/RevisionContent/$id");
            
            App::RUN("/ADM/News/Publish/$id");
            $result = App::RESULT();
            
            $this->eq('', $result->message());
            $this->eq(eSTATE::SUCCESS, $result->state());
            
            $news = $result->get('news');
            $this->eq(1, $news->published);
            $this->eq(70, $news->step);
            
            $this->assertIsInt($news->ts_published);
            $this->tr(($news->ts_published > 0));
        }
    }
    //##########################################################################
}
