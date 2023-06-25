<?
use Core\Provider\CONFIG;

class CONFIG_Test extends FknTestCase
{
    
    public function testLoadFileNotFound()
    {
        
        $this->expectException("Exception");
        $this->expectExceptionCode(eERROR::NOT_FOUND);
        new CONFIG([
            __DIR__ . "/testConfigInexistent.php"
        ]);
        
    }

    //public function testLoadFileInvalidContent()
    //{
    //    
    //    $this->expectException("Error");
    //    //$this->expectExceptionCode(eERROR::INTERNAL);
    //    new CONFIG([
    //        __DIR__ . "/___config-invalid.php"
    //    ]);
    //    
    //}
    
}
