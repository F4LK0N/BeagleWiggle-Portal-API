<?
use App\App;

class TagsFillTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('tags');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET = [];
    }
    //##########################################################################
    public function testAdd()
    {
        for($i=1; $i<=65; $i++)
        {
            $_POST=[];
            $_POST['name']=self::FAKER()->name() ." ".rand(10,99);
        
            App::RUN("/ADM/Tags/Add/");
            $result = App::RESULT();
            
            $this->eq(eSTATE::SUCCESS, $result->state());
            
            $tag = $result->get('tag');
            $this->eq($i, $tag->id);
        }
    }
    //##########################################################################
}
