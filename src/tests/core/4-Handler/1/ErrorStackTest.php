<?
use Core\Error;

class ErrorStackTest extends FknTestCase
{
    public function testCode()
    {
        $error = new Error();
        $this->eq(
            0, $error->code()
        );
        
        $error->setError(1);
        $this->eq(
            400, $error->code()
        );
        
        $error->setError(404);
        $this->eq(
            404, $error->code()
        );
        
        $error->setError(999);
        $this->eq(
            999, $error->code()
        );
    }
    
    public function testMessage()
    {
        $error = new Error();
        $this->eq(
            '', $error->message()
        );
        
        $error->setError(401, 'ERROR401');
        $this->eq(
            'ERROR401', $error->message()
        );
        
        $error->setError(401, 'ERROR404');
        $this->eq(
            'ERROR404', $error->message()
        );
        
        $error->setError(401, 'ERROR1001');
        $this->eq(
            'ERROR1001', $error->message()
        );
    }
    
    public function testFullMessage()
    {
        $error = new Error();
        $this->eq(
            '', $error->fullMessage()
        );
        
        $error->setError(401, 'E401');
        $this->eq(
            'E401', $error->fullMessage()
        );
        
        $error->setError(405, 'E405');
        $this->eq(
            "E405\nE401", $error->fullMessage()
        );
        
        $error->setError(405, 'E407');
        $this->eq(
            "E407\nE405\nE401", $error->fullMessage()
        );
    }
    
    public function testDetails()
    {
        $error = new Error();
        $error->setError(401, 'E401');
        $error->setError(404, 'E404');
        $error->setError(407, 'E407');
        $this->eq(
            "E404\nE401", $error->details()
        );
    }
    
    public function testStack()
    {
        $error = new Error();
        $error->setError(1, 'E1');
        $error->setError(1, 'E4');
        $error->setError(1, 'E7');
        
        $stack=$error->stack(); 
        $this->assertIsArray(
            $stack
        );
        $this->assertCount(3, $stack);
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
        $error->setError(401, 'E401');
        $error->setError(404, 'E404');
        $jsonString = json_encode($error);
        $this->eq(
            '{"code":404,"message":"E404","stack":[{"code":404,"message":"E404"},{"code":401,"message":"E401"}]}', $jsonString
        );
        
        $error->setError(407, 'E407');
        $jsonString = json_encode($error);
        $this->eq(
            '{"code":407,"message":"E407","stack":[{"code":407,"message":"E407"},{"code":404,"message":"E404"},{"code":401,"message":"E401"}]}', $jsonString
        );
    }
    
    public function testErrorObjectStack()
    {
        $errorBefore = new Error(401, "BEFORE");
        $error = new Error(405, "E405", $errorBefore);
        
        $this->eq(
            405, $error->code()
        );
        $this->eq(
            "E405", $error->message()
        );
        $jsonString = json_encode($error);
        $this->eq(
            '{"code":405,"message":"E405","stack":[{"code":405,"message":"E405"},{"code":401,"message":"BEFORE"}]}', $jsonString
        );
        
        $error->setError(408, 'E408');
        $jsonString = json_encode($error);
        $this->eq(
            '{"code":408,"message":"E408","stack":[{"code":408,"message":"E408"},{"code":405,"message":"E405"},{"code":401,"message":"BEFORE"}]}', $jsonString
        );
        
        $errorFinal = new Error(409, "E409", $error);
        $jsonString = json_encode($errorFinal);
        $this->eq(
            '{"code":409,"message":"E409","stack":[{"code":409,"message":"E409"},{"code":408,"message":"E408"},{"code":405,"message":"E405"},{"code":401,"message":"BEFORE"}]}', $jsonString
        );
    }
    
}
