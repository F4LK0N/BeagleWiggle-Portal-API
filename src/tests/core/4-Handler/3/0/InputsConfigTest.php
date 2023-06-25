<?
use Core\Inputs as InputsBase;

class InputsConfig extends InputsBase
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

class InputsConfigTest extends FknTestCase
{
    protected function setUp(): void
    {
        $_POST = [];
        $_GET  = [];
    }
    //##########################################################################
    //### EMPTY ###
    //##########################################################################
    public function testEmpty()
    {
        $inputs = new InputsConfig();
        $inputs->add([]);
        
        $this->assertFalse($inputs->hasError());
        $this->assertCount(
            0, $inputs->getConfig()
        );
    }
    //##########################################################################
    //### NAME ###
    //##########################################################################
    /** @dataProvider providerNameInvalid */
    public function testNameInvalid($name)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Invalid field name!');
        
        $inputs = new InputsConfig();
        $inputs->add([
            "$name" => [],
        ]);
    }
    static public function providerNameInvalid()
    {
        return [
            ['' ],
            [' '],
            ['0'],
            ['*'],
            ['^'],
        ];
    }
    //##########################################################################
    /** @dataProvider providerName */
    public function testName($name)
    {
        $inputs = new InputsConfig();
        $inputs->add([
            "$name" => [],
        ]);
        $this->assertFalse($inputs->hasError());
    }
    static public function providerName()
    {
        return [
            ['field'],
            ['_field'],
            ['_field1'],
            ['_field_field'],
        ];
    }
    //##########################################################################
    //### CONFIG ###
    //##########################################################################
    /** @dataProvider providerConfigInvalid */
    public function testConfigInvalid($config)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid 'field' config!");
        
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => $config,
        ]);
    }
    static public function providerConfigInvalid()
    {
        return [
            [''],
            [0],
            [1],
            [null],
            [true],
            [false],
            [(object)[]],
        ];
    }
    //##########################################################################
    //### TYPE ###
    //##########################################################################
    /** @dataProvider providerTypeInvalid */
    public function testTypeInvalid($type)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid 'field' config!\nInvalid 'type' value!");
        
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                "type" => $type
            ],
        ]);
    }
    static public function providerTypeInvalid()
    {
        return [
            [0],
            [1],
            [true],
            [false],
            [(object)[]],
            [[]],
            [''],
            ['invalidType'],
        ];
    }
    //##########################################################################
    /** @dataProvider providerType */
    public function testType($type)
    {
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                'type' => $type,
            ],
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['field'];
        $this->eq($type, $config['type']);
    }
    static public function providerType()
    {
        return [
            ['bool'],
            ['int'],
            ['string'],
        ];
    }
    //##########################################################################
    //### REQUIRED ###
    //##########################################################################
    /** @dataProvider providerRequiredInvalid */
    public function testRequiredInvalid($required)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid 'field' config!\nInvalid 'required' value!");
        
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                "required" => $required
            ],
        ]);
    }
    static public function providerRequiredInvalid()
    {
        return [
            [(object)[]],
            [[]],
        ];
    }
    //##########################################################################
    /** @dataProvider providerRequired */
    public function testAddRequired($expected, $required)
    {
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                'required' => $required,
            ],
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['field'];
        $this->eq($expected, $config['required']);
    }
    static public function providerRequired()
    {
        return [
            [false, '0'],
            [true,  '1'],
            [false,  0],
            [true,   1],
            [false, false],
            [true,  true],
            [true,  'T'],
            [false, 'F'],
            [true,  'TRUE'],
            [false, 'FALSE'],
            
            [false, ''],
            [true,  ' '],
        ];
    }
    //##########################################################################
    //### DEFAULT ###
    //##########################################################################
    /** @dataProvider providerDefaultInvalid */
    public function testDefaultInvalid($default)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid 'field' config!\nInvalid 'default' value!");
        
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                "default" => $default
            ],
        ]);
    }
    static public function providerDefaultInvalid()
    {
        return [
            [(object)[]],
            [[]],
        ];
    }
    //##########################################################################
    /** @dataProvider providerDefault */
    public function testDefault($expected, $default)
    {
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                'default' => $default,
            ],
        ]);
        $this->assertFalse($inputs->hasError());
    }
    static public function providerDefault()
    {
        return [
            ['',    ''],
            ['0',   '0'],
            ['1',   '1'],
            ['a',   'a'],
            ['A',   'A'],
            ['A a', 'A a'],
            ['Default Default', 'Default Default'],
            ['0',    0],
            ['1',    1],
            ['10',  10],
            ['-1',  -1],
            ['1.5', 1.5],
            ['0',   false],
            ['1',   true],
        ];
    }
    //##########################################################################
    //### FILTERS ###
    //##########################################################################
    /** @dataProvider providerFiltersInvalid */
    public function testFiltersInvalid($filters)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid 'field' config!\nInvalid 'filters' value!");
        
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                "filters" => $filters
            ],
        ]);
    }
    static public function providerFiltersInvalid()
    {
        return [
            [0],
            [1],
            [true],
            [false],
            [(object)[]],
            [''],
            ['invalidFilter'],
            [[0]],
            [[true]],
        ];
    }
    //##########################################################################
    /** @dataProvider providerFilters */
    public function testAddFilters($filters)
    {
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                'filters' => $filters,
            ],
        ]);
        $this->assertFalse($inputs->hasError());
    }
    static public function providerFilters()
    {
        return [
            [['spaces']],
            [['php', 'double-spaces']],
        ];
    }
    //##########################################################################
    //### VALIDATIONS ###
    //##########################################################################
    /** @dataProvider providerValidationsInvalid */
    public function testValidationsInvalid($validations)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid 'field' config!\nInvalid 'validations' value!");
        
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                "validations" => $validations
            ],
        ]);
    }
    static public function providerValidationsInvalid()
    {
        return [
            [0],
            [1],
            [true],
            [false],
            [(object)[]],
            [''],
            ['invalidValidations'],
            [[0]],
            [[true]],
        ];
    }
    //##########################################################################
    /** @dataProvider providerValidations */
    public function testValidations($validations)
    {
        $inputs = new InputsConfig();
        $inputs->add([
            "field" => [
                'validations' => $validations,
            ],
        ]);
        $this->assertFalse($inputs->hasError());
    }
    static public function providerValidations()
    {
        return [
            [['email']],
            [['password']],
            [['min'=>3]],
            [['password', 'max'=>6]],
        ];
    }
    //##########################################################################
    //### ADD ###
    //##########################################################################
    public function testAdd()
    {
        $inputs = new InputsConfig();
        $inputs->add([
            'field' => [
                'type'    => 'int',
                'required'=> true,
                'default' => 3,
                'filters' => [
                    'ascii',
                    'double-spaces'],
                'validations' => [
                    'min:3' ],
            ]
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['field'];
        $this->eq('int', $config['type']);
        $this->eq(true, $config['required']);
        $this->eq(3, $config['default']);
        $this->eq('ascii', $config['filters'][0]);
        $this->eq('double-spaces', $config['filters'][1]);
        $this->eq('min:3', $config['validations'][0]);
        
        
        
        $inputs = new InputsConfig();
        $inputs->add([
            'Field' => [
                'type'    => 'string',
                'required'=> false,
                'default' => 'Aa',
            ]
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['Field'];
        $this->eq('string', $config['type']);
        $this->eq(false, $config['required']);
        $this->eq('Aa', $config['default']);
        
        
        
        $inputs = new InputsConfig();
        $inputs->add([
            '_Field-Field' => [
                'type'    => 'bool',
                'default' => true,
            ]
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['_Field-Field'];
        $this->eq('bool', $config['type']);
        $this->eq(true, $config['default']);
    }
    //##########################################################################
    public function testAddOverwrite()
    {
        $inputs = new InputsConfig();
        
        
        $inputs->add([
            'field' => [
                'type'    => 'int',
                'required'=> true,
                'default' => 3,
                'filters' => [
                    'double-spaces'
                ],
                'validations' => [
                    'min'=>'3'
                ],
            ]
        ]);
        $inputs->add([
            'field' => [
                'type'    => 'string',
                'required'=> false,
                'default' => 'Aa',
                'filters' => [
                    'spaces'],
                'validations' => [
                    'min:4' ],
            ]
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['field'];
        $this->eq('string', $config['type']);
        $this->eq(false, $config['required']);
        $this->eq('Aa', $config['default']);
        $this->eq('spaces', $config['filters'][0]);
        $this->eq('min:4', $config['validations'][0]);
        
        
        $inputs->add([
            'field' => [
                'type'    => 'bool',
            ]
        ]);
        $this->assertFalse($inputs->hasError());
        
        $config = $inputs->getConfig()['field'];
        $this->eq('bool', $config['type']);
        $this->eq(true, $config['required']);
        $this->eq('', $config['default']);
        $this->assertCount(0, $config['filters']);
        $this->assertCount(0, $config['validations']);
    }
    
}
