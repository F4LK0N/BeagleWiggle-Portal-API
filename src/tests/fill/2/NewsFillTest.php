<?
use App\App;
use Core\Helper\FILTER;

class NewsFillTest extends FknTestCase
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
        for($i=0; $i<200; $i++){
            
            $_POST=[];
            $_POST['id_category']="".rand(1,40);
            $_POST['title']=self::FAKER()->text(rand(40, 80))." ".rand(1000, 9999);
            $_POST['description']=self::FAKER()->text(rand(40, 235));
            $_POST['content']= self::FAKER()->paragraphs(rand(12, 32), true);
            
            if(rand(1,2)===1){
                $_POST['source_name']=self::FAKER()->name();
                if(rand(1,2)===1){
                    $_POST['source_url']=self::FAKER()->name().self::FAKER()->text(rand(10, 40));
                    $_POST['source_url'] = FILTER::URL($_POST['source_url']);
                }
            }
            
            App::RUN("/ADM/News/Add/");
            $result = App::RESULT();
            $this->eq('', $result->message());
            
            $id = $result->get('news')->id;
            
            App::RUN("/ADM/News/Complete/$id");
            $_POST['approved']="1";
            App::RUN("/ADM/News/RevisionOrthographic/$id");
            $_POST['approved']="1";
            App::RUN("/ADM/News/RevisionContent/$id");
            
            App::RUN("/ADM/News/Publish/$id");
            $news = App::RESULT()->get('news');
            
            $this->eq(1, $news->published);
            $this->assertIsInt($news->ts_published);
            $this->tr(($news->ts_published > 0));
        }
    }
    //##########################################################################
}
