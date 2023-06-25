<?
use App\App;

class TagsFlowTest extends FknTestCase
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
        for($i=1; $i<=20; $i++)
        {
            $_POST=[];
            $_POST['name']="Tag ($i) ".self::FAKER()->name();
        
            App::RUN("/ADM/Tags/Add/");
            $result = App::RESULT();
            
            $this->eq(eSTATE::SUCCESS, $result->state());
            
            $tag = $result->get('tag');
            $this->eq($i, $tag->id);
        }
    }
    //##########################################################################
    /**
     * @depends testAdd
     * @dataProvider providerEditIds 
     * */
    public function testEdit($id)
    {
        $_POST=[];
        $_POST['name']="Tag ($id) Edited ";
        App::RUN("/ADM/Tags/Edit/$id");
        $result = App::RESULT();
        
        $this->eq('', $result->message());
        $this->eq(eSTATE::SUCCESS, $result->state());
        
        $tag = $result->get('tag');
        $this->eq($id, $tag->id);
        $this->eq("Tag ($id) Edited", $tag->name);
    }
    static public function providerEditIds()
    {
        return [
            [5],
            [10],
            [12],
        ];
    }
    //##########################################################################
    /**
     * @depends testEdit
     */
    public function testDelete()
    {
        App::RUN("/ADM/Tags/Rem/4");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tag = $result->get('tag');
        $this->assertIsInt($tag->ts_deleted);
        $this->tr(($tag->ts_deleted > 0));
    }
    //##########################################################################
    /**
     * @depends testDelete
     */
    public function testView()
    {
        App::RUN("/ADM/Tags/View/5");
        $result = App::RESULT();
        
        $this->eq(
            '', $result->message()
        );
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $tag = $result->get('tag');
        $this->eq(5, $tag->id);
        $this->eq('Tag (5) Edited', $tag->name);
        $this->assertIsInt($tag->ts_created);
        $this->tr(($tag->ts_created > 0));
        $this->assertIsInt($tag->ts_modified);
        $this->tr(($tag->ts_modified > 0));
    }
    //##########################################################################
    /**
     * @depends testView
     */
    public function testList()
    {
        $_POST=[];
        $_POST['page']=1;
        $_POST['pageSize']=5;
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
        $this->eq(5, $tags->getPageSize());
        $this->eq(4, $tags->getPages());
        $this->eq(19, $tags->getTotal());
    }
    
}
