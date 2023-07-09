<?
use Core\Provider\DISPATCHER;

class DISPATCHER_Test extends FknTestCase
{
    public function testVerifyNotFoundController()
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(eERROR::NOT_FOUND);
        DISPATCHER::RUN("XXX", "Index");
    }
    
    public function testVerifyNotFoundAction()
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(eERROR::NOT_FOUND);
        DISPATCHER::RUN("Index", "XXX");
    }
    
    //public function testVerifyDefaultModules()
    //{
    //    try{
    //        $this->assertNull(DISPATCHER::RUN("ADM", "Index", "Index"));
    //    } catch (Exception $e){
    //        $this->fail("Can't load 'ADM' module!".$e->getMessage());
    //    }
    //    
    //    try{
    //        $this->assertNull(DISPATCHER::RUN("AUTH", "Index", "Index"));
    //    } catch (Exception $e){
    //        $this->fail("Can't load 'AUTH' module!".$e->getMessage());
    //    }
    //    
    //    try{
    //        $this->assertNull(DISPATCHER::RUN("PUBLIC", "Index", "Index"));
    //    } catch (Exception $e){
    //        $this->fail("Can't load 'PUBLIC' module!".$e->getMessage());
    //    }
    //}

    
    
    //Index Module
    
    //Commom Module
    
    //index
    
    //arguments
    
    
    //TODO: Override APP_PATH constant e set a path to test controllers
    //and actions. To test que search and, event calls
    
}
