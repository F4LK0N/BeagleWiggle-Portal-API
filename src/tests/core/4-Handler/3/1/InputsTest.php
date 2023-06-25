<?
use Core\Inputs as InputsBase;

class Inputs extends InputsBase
{
    public function getData(): array
    {
        return $this->data;
    }
    
    public function getConfig(): array
    {
        return $this->fields;
    }
}

class InputsTest extends FknTestCase
{
    protected function setUp(): void
    {
        $_POST = [];
        $_GET  = [];
    }
    //##########################################################################
    //### ERROR ###
    //##########################################################################
    public function testRunWithPreviousError()
    {
        $inputs = new Inputs();
        
        try{
            $inputs->add([
                "" => [],
            ]);
        }
        catch(Exception $exception){
            
        }
        
        $this->expectException('Exception');
        $this->expectExceptionMessage('Invalid field name!');
        $inputs->run();
    }
    //##########################################################################
    //### RETRIEVE ###
    //##########################################################################
    public function testRetrieveGet()
    {
        $_GET['field']='A';
        
        $inputs = new Inputs();
        $inputs->add([
            'field' => [],
        ]);
        $inputs->run();
        
        $this->eq('A', $inputs->get('field'));
    }
    public function testRetrievePost()
    {
        $_POST['field']='B';
        
        $inputs = new Inputs();
        $inputs->add([
            'field' => [],
        ]);
        $inputs->run();
        
        $this->eq('B', $inputs->get('field'));
    }
    public function testRetrieveGetPost()
    {
        $_GET['field']='A';
        $_POST['field']='B';
        
        $inputs = new Inputs();
        $inputs->add([
            'field' => [],
        ]);
        $inputs->run();
        
        $this->eq('B', $inputs->get('field'));
    }
    //##########################################################################
    //### DEFAULT ###
    //##########################################################################
    public function testDefault()
    {
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type' => 'int',
                'default' => 1,
            ],
            'checked' => [
                'type' => 'bool',
                'default' => true,
            ],
            'name' => [
                'type' => 'string',
                'default' => 'Name',
            ]
        ]);
        $inputs->run();
        $this->fs($inputs->hasError());
        $this->eq(1, $inputs->get('id'));
        $this->eq(true, $inputs->get('checked'));
        $this->eq('Name', $inputs->get('name'));
    }
    public function testDefaultOverwrite()
    {
        $_POST['id']=5;
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type' => 'int',
                'default' => 1,
            ],
        ]);
        $inputs->run();
        $this->fs($inputs->hasError());
        $this->eq(5, $inputs->get('id'));
    }
    //##########################################################################
    //### REQUIRED ###
    //##########################################################################
    public function testRequiredError()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("'id' is required!");
        
        $inputs = new Inputs();
        $inputs->add([
            'id' => []
        ]);
        $inputs->run();
    }
    public function testRunRequiredErrorMultiFields()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("'name' is required!\n'id' is required!");
        
        $inputs = new Inputs();
        $inputs->add([
            'id' => [],
            'name' => []
        ]);
        $inputs->run();
    }
    public function testRequiredDefault()
    {
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'default' => 1,
            ]
        ]);
        $inputs->run();
        
        $this->fs($inputs->hasError());
        $this->eq(1, $inputs->get('id'));
    }
    //##########################################################################
    //### TYPE ###
    //##########################################################################
    public function testType()
    {
        $_POST=[
            'id' => '5',
            'name' => 'Name',
            'registered' => '1',
        ];
        
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type' => 'int',
            ],
            'name' => [
                'type' => 'string',
            ],
            'registered' => [
                'type' => 'bool',
            ],
        ]);
        $inputs->run();
        $this->fs($inputs->hasError());
        $this->eq('5', $inputs->get('id'));
        $this->eq('Name', $inputs->get('name'));
        $this->eq('1', $inputs->get('registered'));
    }
    //##########################################################################
    //### FILTERS ###
    //##########################################################################
    public function testFilters()
    {
        $_POST['name']="Name  Name ";
        
        $inputs = new Inputs();
        $inputs->add([
            'name' => [
                'type' => 'string',
                'filters' => ['name']
            ],
            'surname' => [
                'type' => 'string',
                'required'=>false,
                'filters' => ['name'],
            ],
        ]);
        $inputs->run();
        $this->fs($inputs->hasError());
        $this->eq('Name Name', $inputs->get('name'));
    }
    public function testFiltersInvalid()
    {
        $_POST['name']="Name  Name ";
        
        $inputs = new Inputs();
        $inputs->add([
            'name' => [
                'type' => 'string',
                'filters' => ['invalid-filter']
            ]
        ]);
        
        $this->expectException('Exception');
        $this->expectExceptionMessage("FILTER error!\nFilter 'invalid-filter' not found!");
        $inputs->run();
    }
    public function testFiltersRequiredError()
    {
        $_POST['name']=",,,";
        
        $inputs = new Inputs();
        $inputs->add([
            'name' => [
                'type' => 'string',
                'filters' => ['keyword']
            ],
        ]);
        
        $this->expectException('Exception');
        $this->expectExceptionMessage("'name' is required!");
        $inputs->run();
    }
    //##########################################################################
    //### VALIDATIONS ###
    //##########################################################################
    public function testValidationsRuleFail()
    {
        $_POST['name']="Name  Name ";
        
        $inputs = new Inputs();
        $inputs->add([
            'name' => [
                'type' => 'string',
                'validations' => [
                    'max-length'=>4
                ]
            ]
        ]);
        
        $this->expectException('Exception');
        $this->expectExceptionMessage(
            "'name' invalid!\n".
            "'name' max length must be '4'!"
        );
        $inputs->run();
    }
    public function testValidationsMultiRuleFail()
    {
        $_POST['name']="Name  Name ";
        
        $inputs = new Inputs();
        $inputs->add([
            'name' => [
                'type' => 'string',
                'validations' => [
                    'db-name',
                    'max-length'=>4
                ]
            ]
        ]);
        
        $this->expectException('Exception');
        $this->expectExceptionMessage(
            "'name' invalid!\n".
            "'name' must comply 'db-name' rule!\n".
            "'name' max length must be '4'!"
        );
        $inputs->run();
    }
    //##########################################################################
    //### GET ###
    //##########################################################################
    public function testGetUndefined()
    {
        $_POST['id']=5;
        
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type' => 'int',
            ],
        ]);
        $inputs->run();
        
        $this->expectException('Exception');
        $this->expectExceptionMessage("'field-x' input not found!");
        $inputs->get('field-x');
    }
    public function testGetBeforeRun()
    {
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type' => 'int',
            ],
        ]);
        
        $this->expectException('Exception');
        $this->expectExceptionMessage("'id' input not found!");
        $inputs->get('id');
    }
    //##########################################################################
    //### SET ###
    //##########################################################################
    public function testSetAfterRun()
    {
        $_POST['id']=5;
        
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type'=>'int',
                'required'=>true,
            ],
        ]);
        
        $inputs->run();
        $this->eq(5, $inputs->get('id'));
        
        $inputs->set('id', 6);
        $this->eq(6, $inputs->get('id'));
    }
    public function testSetBeforeRun()
    {
        $_POST['id']=5;
        
        $inputs = new Inputs();
        $inputs->add([
            'id' => [
                'type'=>'int',
                'required'=>true,
            ],
        ]);
        
        $inputs->set('id', 6);
        $this->eq(6, $inputs->get('id'));
        
        $inputs->run();
        $this->eq(5, $inputs->get('id'));
    }
}
