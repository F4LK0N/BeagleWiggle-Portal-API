<?
use Core\Provider\ROUTER;

class ROUTER_Test extends FknTestCase
{
    public function testLoadFileNotFound()
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(eERROR::NOT_FOUND);
        new ROUTER([
            __DIR__ . "/testRouteInexistent.php"
        ]);
    }
    
    //public function testLoadFileInvalidContent()
    //{
    //    $this->expectException("Error");
    //    //$this->expectExceptionCode(eERROR::INTERNAL);
    //    new ROUTER([
    //        __DIR__ . "/___route-invalid.php"
    //    ]);
    //}
    
    public function testDefaultRoute()
    {
        $this->eq(
            "PUBLIC", ROUTER::MODULE()
        );
        $this->eq(
            "Index", ROUTER::CONTROLLER()
        );
        $this->eq(
            "Index", ROUTER::ACTION()
        );
        $this->assertIsArray(
            ROUTER::PARAMS()
        );
    }
    
    public function testNotFoundRoute()
    {
        ROUTER::RUN('inexistent/route/');
        
        $this->eq(
            "PUBLIC", ROUTER::MODULE()
        );
        $this->eq(
            "Index", ROUTER::CONTROLLER()
        );
        $this->eq(
            "NotFound", ROUTER::ACTION()
        );
        $this->assertIsArray(
            ROUTER::PARAMS()
        );
        
    }

}
