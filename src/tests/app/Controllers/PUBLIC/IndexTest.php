<?
use App\App;

class IndexTest extends FknTestCase
{
    public function testIndex()
    {
        $_POST = [];
         $_GET = [];
        App::RUN("/");
        
        $result = App::RESULT();
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
    }
}
