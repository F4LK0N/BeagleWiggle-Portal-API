<?
use App\App;

class TagsListTest extends FknTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::DB_TRUNCATE('tags');
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
        
        App::RUN("/ADM/Tags/");
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
        
        App::RUN("/ADM/Tags/");
        $result = App::RESULT();
        
        $this->rx(
            '/.*(\'pageSize\' min value must be \'2\'\!).*/', $result->message()
        );
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
        
        
        $_POST['pageSize']=101;
        
        App::RUN("/ADM/Tags/");
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
        
        App::RUN("/ADM/Tags/");
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
        
        App::RUN("/ADM/Tags/");
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
            $_POST=[];
            $_POST['name']="Tag$i";
            App::RUN("/ADM/Tags/Add/");
        }
        $_POST=[];
        
        $_POST['page']=1;
        $_POST['pageSize']=2;
        App::RUN("/ADM/Tags/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tags = $result->get('tags');
        $this->eq(1, $tags->getPage());
        $this->eq(2, $tags->getPageSize());
        $this->eq(3, $tags->getPages());
        $this->eq(5, $tags->getTotal());
    }
    public function testPageLimit()
    {
        for($i=1; $i<=5; $i++)
        {
            $_POST=[];
            $_POST['name']="Tag$i";
            App::RUN("/ADM/Tags/Add/");
        }
        $_POST=[];
        
        $_POST['page']=10;
        $_POST['pageSize']=2;
        App::RUN("/ADM/Tags/");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tags = $result->get('tags');
        $this->eq(3, $tags->getPage());
        $this->eq(2, $tags->getPageSize());
        $this->eq(3, $tags->getPages());
        $this->eq(5, $tags->getTotal());
    }
    
}
