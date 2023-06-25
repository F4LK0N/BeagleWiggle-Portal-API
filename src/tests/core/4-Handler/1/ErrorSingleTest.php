<?
use Core\Error;

class ErrorSingleTest extends FknTestCase
{
    public function testCode()
    {
        $error = new Error();
        $this->eq(
            0, $error->code()
        );
        
        $error = new Error();
        $error->setError(1);
        $this->eq(
            400, $error->code()
        );
        
        $error = new Error(404);
        $this->eq(
            404, $error->code()
        );
    }
    
    public function testMessage()
    {
        $error = new Error();
        $this->eq(
            '', $error->message()
        );
        
        $error = new Error();
        $error->setError(1, 'ERROR');
        $this->eq(
            'ERROR', $error->message()
        );
        $this->eq(
            '', $error->details()
        );
    }
    
    public function testErrorObject()
    {
        $errorA = new Error(401, "ERROR_A");
        $errorB = new Error($errorA);
        $this->eq(
            401, $errorB->code()
        );
        $this->eq(
            'ERROR_A', $errorB->message()
        );
    }
    
    public function testException()
    {
        $error=null;
        try{
            throw new Exception("EXCEPTION", 456);
        }
        catch(Exception $exception){
            $error = new Error($exception);
        }
        $this->eq(
            456, $error->code()
        );
        $this->eq(
            'EXCEPTION', $error->message()
        );
    }
    
    public function testHasError()
    {
        $error = new Error();
        $this->eq(
            false, $error->hasError()
        );
        
        $error = new Error();
        $error->setError(1, 'ERROR');
        $this->eq(
            true, $error->hasError()
        );
    }
    
    
    public function testJsonSerialize()
    {
        $error = new Error();
        $jsonString = json_encode($error);
        $this->eq(
            '{"code":0,"message":""}', $jsonString
        );
        
        $error = new Error(404, "ERROR");
        $jsonString = json_encode($error);
        $this->eq(
            '{"code":404,"message":"ERROR"}', $jsonString
        );
    }
    
}
