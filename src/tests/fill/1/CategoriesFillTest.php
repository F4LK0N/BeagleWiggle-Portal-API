<?
use App\App;

class CategoriesFillTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('categories');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET = [];
    }
    //##########################################################################
    public function testAdd()
    {
        for($i=1; $i<=40; $i++)
        {
            $_POST=[];
            $_POST['name']=self::FAKER()->name() ." ".rand(10,99);
        
            App::RUN("/ADM/Categories/Add/");
            $result = App::RESULT();
            
            $this->eq(eSTATE::SUCCESS, $result->state());
            
            $category = $result->get('category');
            $this->eq($i, $category->id);
        }
    }
    //##########################################################################
}
