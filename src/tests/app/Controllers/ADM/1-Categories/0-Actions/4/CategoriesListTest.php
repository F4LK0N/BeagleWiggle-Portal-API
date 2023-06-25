<?
use App\App;

class CategoriesListTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('categories');
    }
    protected function setUp(): void
    {
        $_POST = [];
        $_GET  = [];
    }
    //##########################################################################
    public function testInputPageInvalid()
    {
        $_POST['page']=0;
        
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'page\' min value must be \'1\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    public function testInputPageSizeInvalid()
    {
        $_POST['pageSize']=1;
        
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'pageSize\' min value must be \'2\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
        
        
        $_POST['pageSize']=101;
        
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'pageSize\' max value must be \'100\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    public function testInputSearchInvalid()
    {
        $_POST['search']='ab';
        
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'search\' min length must be \'3\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    public function testInputOrderInvalid()
    {
        $_POST['order']='ab';
        
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'order\' min length must be \'3\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    //##########################################################################
    public function testPage()
    {
        for($i=1; $i<=5; $i++)
        {
            $_POST = [];
            $_POST['name']="Category$i";
            App::RUN("/ADM/Categories/Add/");
        }
        $_POST=[];
        
        $_POST['page']=1;
        $_POST['pageSize']=2;
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $categories = $result->get('categories');
        $this->eq(1, $categories->getPage());
        $this->eq(2, $categories->getPageSize());
        $this->eq(3, $categories->getPages());
        $this->eq(5, $categories->getTotal());
    }
    public function testPageLimit()
    {
        for($i=1; $i<=5; $i++)
        {
            $_POST = [];
            $_POST['name']="Category$i";
            App::RUN("/ADM/Categories/Add/");
        }
        $_POST=[];
        
        $_POST['page']=10;
        $_POST['pageSize']=2;
        App::RUN("/ADM/Categories/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $categories = $result->get('categories');
        $this->eq(3, $categories->getPage());
        $this->eq(2, $categories->getPageSize());
        $this->eq(3, $categories->getPages());
        $this->eq(5, $categories->getTotal());
    }
    
}
