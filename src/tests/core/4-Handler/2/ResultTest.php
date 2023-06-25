<?
use Core\Result;

class ResultTest extends FknTestCase
{
    public function testStatus()
    {
        $result = new Result();
        $this->eq(
            eSTATE::SUCCESS, $result->state()
        );
        
        $result->setError(401, 'A');
        $this->eq(
            eSTATE::ERROR, $result->state()
        );
    }
    
    public function testDataKey()
    {
        $result = new Result();
        $result->set("KeyA", "ValueA");
        $this->eq(
            "ValueA", $result->get("KeyA")
        );
        
        $result = new Result();
        $result->set("KA", "VA");
        $result->set("KB", "VB");
        $this->eq(
            "VA", $result->get("KA")
        );
        $this->eq(
            "VB", $result->get("KB")
        );
    }
    
    public function testDataObject()
    {
        $result = new Result();
        $result->set((object)[
            "KA" => "VA",
            "KB" => "VB",
        ]);
        $this->eq(
            "VA", $result->get("KA")
        );
        $this->eq(
            "VB", $result->get("KB")
        );
    }
    
    public function testDataArray()
    {
        $result = new Result();
        $result->set([
            "KA" => "VA",
            "KB" => "VB",
        ]);
        $this->eq(
            "VA", $result->get("KA")
        );
        $this->eq(
            "VB", $result->get("KB")
        );
    }
    
    public function testJsonSerialize()
    {
        $result = new Result();
        $this->eq(
            '{"state":1,"error":{"code":0,"message":""},"data":{}}',
            json_encode($result)
        );
    }
    
}
