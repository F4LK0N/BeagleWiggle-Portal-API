<?
use Core\Helper\VALIDATOR;

class VALIDATOR_RUN_Test extends FknTestCase
{
    /** @dataProvider providerRunInvalidValidations */
    public function testRunInvalidValidation($validations)
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage("Invalid validation!");
        
        VALIDATOR::RUN('ab', $validations);
    }
    static public function providerRunInvalidValidations()
    {
        return [
            [['invalidValidation']],
            [['invalidValidation'=>'invalidValidation']],
            [[1]],
            [[0=>1]],
            [['max']],
            [['max'=>true]],
            [['max'=>'a']],
            [['min']],
            [['min'=>true]],
            [['min'=>'a']],
            [['between']],
            [['between'=>true]],
            [['between'=>1]],
            [['between'=>""]],
            [['between'=>"1"]],
            [['between'=>"a:a"]],
            [['max-length']],
            [['max-length'=>true]],
            [['max-length'=>'a']],
            [['min-length']],
            [['min-length'=>true]],
            [['min-length'=>'a']],
        ];
    }
    //##########################################################################
    public function testRunFail()
    {
        $this->fs(VALIDATOR::RUN('-name', ['field-name']));
        $this->eq("'field' invalid!\n'field' must comply 'field-name' rule!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN('-name', ['db-name']));
        $this->eq("'field' invalid!\n'field' must comply 'db-name' rule!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN('ab', ['min-length'=>'3']));
        $this->eq("'field' invalid!\n'field' min length must be '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN('abcd', ['max-length'=>'3']));
        $this->eq("'field' invalid!\n'field' max length must be '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN(2, ['min'=>'3']));
        $this->eq("'field' invalid!\n'field' min value must be '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN(2, ['min'=>3]));
        $this->eq("'field' invalid!\n'field' min value must be '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN(4, ['max'=>'3']));
        $this->eq("'field' invalid!\n'field' max value must be '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN(4, ['max'=>3]));
        $this->eq("'field' invalid!\n'field' max value must be '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN(1, ['between'=>'2:3']));
        $this->eq("'field' invalid!\n'field' value must be between '2' and '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(VALIDATOR::RUN(4, ['between'=>'2:3']));
        $this->eq("'field' invalid!\n'field' value must be between '2' and '3'!", VALIDATOR::ERRORS('field'));
        
        $this->fs(
            VALIDATOR::RUN(
                'name name', 
                [
                    'field-name',
                    'min-length'=>'2',
                    'max-length'=>'3',
                ])
        );
        $this->eq(
            "'field' invalid!\n".
            "'field' must comply 'field-name' rule!\n".
            "'field' max length must be '3'!",
            VALIDATOR::ERRORS('field')
        );
    }
    //##########################################################################
    public function testRun()
    {
        $this->tr(
            VALIDATOR::RUN('name', ['db-name'])
        );
        $this->tr(
            VALIDATOR::RUN('_name', ['db-name'])
        );
        $this->tr(
            VALIDATOR::RUN('abc', ['min-length'=>'3'])
        );
        $this->tr(
            VALIDATOR::RUN('abc', ['min-length'=>3])
        );
        $this->tr(
            VALIDATOR::RUN('abc', ['max-length'=>'3'])
        );
        $this->tr(
            VALIDATOR::RUN('abc', ['max-length'=>4])
        );
        
        $this->tr(
            VALIDATOR::RUN(
                'Name', 
                [
                    'field-name',
                    'min-length'=>'2',
                    'max-length'=>'6',
                ])
        );
    }
    //##########################################################################
    public function testErrors()
    {
        VALIDATOR::RUN('abc', ['min-length'=>'3']);
        $this->eq("", VALIDATOR::ERRORS('field'));
        $this->eq("", VALIDATOR::ERRORS());
        
        
        $this->fs(VALIDATOR::RUN('-name', ['field-name']));
        $this->eq("'field' invalid!\n'field' must comply 'field-name' rule!", VALIDATOR::ERRORS('field'));
        $this->eq("Invalid!\nMust comply 'field-name' rule!", VALIDATOR::ERRORS());
        
        
        $this->fs(
            VALIDATOR::RUN(
                'name name', 
                [
                    'field-name',
                    'min-length'=>'2',
                    'max-length'=>'3',
                ])
        );
        $this->eq(
            "'field' invalid!\n".
            "'field' must comply 'field-name' rule!\n".
            "'field' max length must be '3'!",
            VALIDATOR::ERRORS('field')
        );
        $this->eq(
            "Invalid!\n".
            "Must comply 'field-name' rule!\n".
            "Max length must be '3'!",
            VALIDATOR::ERRORS()
        );
    }
    //##########################################################################
}
