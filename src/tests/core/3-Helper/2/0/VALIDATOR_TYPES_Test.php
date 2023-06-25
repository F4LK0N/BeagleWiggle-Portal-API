<?
use Core\Helper\VALIDATOR;

class VALIDATOR_TYPES_Test extends FknTestCase
{
    
    //##########################################################################
    //### MIN ###
    //##########################################################################
    public function testMinFail()
    {
        $this->fs(VALIDATOR::MIN(3, 2));
        $this->fs(VALIDATOR::MIN(3, 1));
        $this->fs(VALIDATOR::MIN(3, 0));
        $this->fs(VALIDATOR::MIN(3, -1));
        
        $this->fs(VALIDATOR::MIN(3, 2.0));
        $this->fs(VALIDATOR::MIN(3, 2.9));
        $this->fs(VALIDATOR::MIN(3, -1.9));
        
        $this->fs(VALIDATOR::MIN(3, '2'));
        $this->fs(VALIDATOR::MIN(3, '1.0'));
        $this->fs(VALIDATOR::MIN(3, '0'));
        $this->fs(VALIDATOR::MIN(3, '-1'));
        $this->fs(VALIDATOR::MIN(3, '-1.9'));
        
        $this->fs(VALIDATOR::MIN(3, null));
        $this->fs(VALIDATOR::MIN(3, true));
        $this->fs(VALIDATOR::MIN(3, false));
        $this->fs(VALIDATOR::MIN(3, []));
        $this->fs(VALIDATOR::MIN(3, (object)[]));
    }
    public function testMin()
    {
        $this->tr(VALIDATOR::MIN(3, 3));
        $this->tr(VALIDATOR::MIN(3, 4));
        $this->tr(VALIDATOR::MIN(3, 1000));
        
        $this->tr(VALIDATOR::MIN(-3, -2));
        
        $this->tr(VALIDATOR::MIN(3, 3.0));
        $this->tr(VALIDATOR::MIN(3, 3.1));
        $this->tr(VALIDATOR::MIN(3, 4.9));
        
        
        $this->tr(VALIDATOR::MIN(3, '3'));
        $this->tr(VALIDATOR::MIN(3, '4'));
        $this->tr(VALIDATOR::MIN(3, '1000'));
        
        $this->tr(VALIDATOR::MIN(-3, '-2'));
        
        $this->tr(VALIDATOR::MIN(3, '3.0'));
        $this->tr(VALIDATOR::MIN(3, '3.1'));
        $this->tr(VALIDATOR::MIN(3, '4.9'));
    }
    //##########################################################################
    //### MAX ###
    //##########################################################################
    public function testMaxFail()
    {
        $this->fs(VALIDATOR::MAX(3, 4));
        $this->fs(VALIDATOR::MAX(3, 5));
        $this->fs(VALIDATOR::MAX(3, 1000));
        
        $this->fs(VALIDATOR::MAX(-3, -2));
        $this->fs(VALIDATOR::MAX(3, 4.9));
        
        
        $this->fs(VALIDATOR::MAX(3, '4'));
        $this->fs(VALIDATOR::MAX(3, '5'));
        $this->fs(VALIDATOR::MAX(3, '1000'));
        
        $this->fs(VALIDATOR::MAX(-3, '-2'));
        
        $this->fs(VALIDATOR::MAX(3, '4.0'));
        $this->fs(VALIDATOR::MAX(3, '4.9'));
        
        $this->fs(VALIDATOR::MAX(3, null));
        $this->fs(VALIDATOR::MAX(3, true));
        $this->fs(VALIDATOR::MAX(3, false));
        $this->fs(VALIDATOR::MAX(3, []));
        $this->fs(VALIDATOR::MAX(3, (object)[]));
    }
    public function testMax()
    {
        $this->tr(VALIDATOR::MAX(3, 3));
        $this->tr(VALIDATOR::MAX(3, 2));
        $this->tr(VALIDATOR::MAX(3, 1));
        $this->tr(VALIDATOR::MAX(3, 0));
        $this->tr(VALIDATOR::MAX(3, -1));
        
        $this->tr(VALIDATOR::MAX(3, 3.9));
        $this->tr(VALIDATOR::MAX(3, 3.0));
        $this->tr(VALIDATOR::MAX(3, 2.0));
        $this->tr(VALIDATOR::MAX(3, 2.9));
        $this->tr(VALIDATOR::MAX(3, -1.9));
        
        $this->tr(VALIDATOR::MAX(3, '3.9'));
        $this->tr(VALIDATOR::MAX(3, '3.0'));
        $this->tr(VALIDATOR::MAX(3, '3'));
        $this->tr(VALIDATOR::MAX(3, '2'));
        $this->tr(VALIDATOR::MAX(3, '1.0'));
        $this->tr(VALIDATOR::MAX(3, '0'));
        $this->tr(VALIDATOR::MAX(3, '-1'));
        $this->tr(VALIDATOR::MAX(3, '-1.9'));
    }
    //##########################################################################
    //### BETWEEN ###
    //##########################################################################
    public function testBetweenFail()
    {
        $this->fs(VALIDATOR::BETWEEN(3, 4, 5));
        $this->fs(VALIDATOR::BETWEEN(3, 4, 2));
        
        $this->fs(VALIDATOR::MAX(3, null));
        $this->fs(VALIDATOR::MAX(3, true));
        $this->fs(VALIDATOR::MAX(3, false));
        $this->fs(VALIDATOR::MAX(3, []));
        $this->fs(VALIDATOR::MAX(3, (object)[]));
    }
    public function testBetween()
    {
        $this->tr(VALIDATOR::BETWEEN(3, 4, 3));
        $this->tr(VALIDATOR::BETWEEN(3, 4, 4));
    }
    //##########################################################################
    //### MIN LENGTH ###
    //##########################################################################
    public function testMinLengthFail()
    {
        $this->fs(VALIDATOR::MIN_LENGTH(3, ''));
        $this->fs(VALIDATOR::MIN_LENGTH(3, '1'));
        $this->fs(VALIDATOR::MIN_LENGTH(3, '12'));
        
        $this->fs(VALIDATOR::MIN_LENGTH(3, null));
        $this->fs(VALIDATOR::MIN_LENGTH(3, true));
        $this->fs(VALIDATOR::MIN_LENGTH(3, false));
        $this->fs(VALIDATOR::MIN_LENGTH(3, []));
        $this->fs(VALIDATOR::MIN_LENGTH(3, (object)[]));
    }
    public function testMinLength()
    {
        $this->tr(VALIDATOR::MIN_LENGTH(3, '123'));
        $this->tr(VALIDATOR::MIN_LENGTH(3, '1234'));
    }
    //##########################################################################
    //### MAX LENGTH ###
    //##########################################################################
    public function testMaxLengthFail()
    {
        $this->fs(VALIDATOR::MAX_LENGTH(3, '1234'));
        $this->fs(VALIDATOR::MAX_LENGTH(3, '12345'));
        
        $this->fs(VALIDATOR::MAX_LENGTH(3, null));
        $this->fs(VALIDATOR::MAX_LENGTH(3, true));
        $this->fs(VALIDATOR::MAX_LENGTH(3, false));
        $this->fs(VALIDATOR::MAX_LENGTH(3, []));
        $this->fs(VALIDATOR::MAX_LENGTH(3, (object)[]));
    }
    public function testMaxLength()
    {
        $this->tr(VALIDATOR::MAX_LENGTH(3, '123'));
        $this->tr(VALIDATOR::MAX_LENGTH(3, '12'));
    }
    //##########################################################################
    //### FIELD NAME ###
    //##########################################################################
    public function testFieldNameFail()
    {
        $this->fs(VALIDATOR::FIELD_NAME(0));
        $this->fs(VALIDATOR::FIELD_NAME(1));
        $this->fs(VALIDATOR::FIELD_NAME(false));
        $this->fs(VALIDATOR::FIELD_NAME(true));
        $this->fs(VALIDATOR::FIELD_NAME(null));
        $this->fs(VALIDATOR::FIELD_NAME(array()));
        $this->fs(VALIDATOR::FIELD_NAME((object)[]));
        $this->fs(VALIDATOR::FIELD_NAME('' ));
        $this->fs(VALIDATOR::FIELD_NAME(' '));
        $this->fs(VALIDATOR::FIELD_NAME('*'));
        $this->fs(VALIDATOR::FIELD_NAME('^'));
        $this->fs(VALIDATOR::FIELD_NAME('0'));
        $this->fs(VALIDATOR::FIELD_NAME('1'));
        $this->fs(VALIDATOR::FIELD_NAME('1name'));
        $this->fs(VALIDATOR::FIELD_NAME('name name'));
        $this->fs(VALIDATOR::FIELD_NAME('-name'));
        $this->fs(VALIDATOR::FIELD_NAME('_'));
        $this->fs(VALIDATOR::FIELD_NAME('__'));
        $this->fs(VALIDATOR::FIELD_NAME('_-_'));
        $this->fs(VALIDATOR::FIELD_NAME('_9'));
        $this->fs(VALIDATOR::FIELD_NAME('_9_'));
        $this->fs(VALIDATOR::FIELD_NAME('_9name'));
        $this->fs(VALIDATOR::FIELD_NAME('name-'));
        $this->fs(VALIDATOR::FIELD_NAME('name*'));
    }
    public function testFieldName()
    {
        $this->tr(VALIDATOR::FIELD_NAME('name'));
        $this->tr(VALIDATOR::FIELD_NAME('Name'));
        $this->tr(VALIDATOR::FIELD_NAME('NameName'));
        $this->tr(VALIDATOR::FIELD_NAME('_name'));
        $this->tr(VALIDATOR::FIELD_NAME('_Name'));
        $this->tr(VALIDATOR::FIELD_NAME('_name_name'));
        $this->tr(VALIDATOR::FIELD_NAME('_name_name_'));
        $this->tr(VALIDATOR::FIELD_NAME('_name-name'));
        $this->tr(VALIDATOR::FIELD_NAME('__name-name_'));
    }
    //##########################################################################
    //### DB NAME ###
    //##########################################################################
    public function testDbNameFail()
    {
        $this->fs(VALIDATOR::DB_NAME(0));
        $this->fs(VALIDATOR::DB_NAME(1));
        $this->fs(VALIDATOR::DB_NAME(false));
        $this->fs(VALIDATOR::DB_NAME(true));
        $this->fs(VALIDATOR::DB_NAME(null));
        $this->fs(VALIDATOR::DB_NAME(array()));
        $this->fs(VALIDATOR::DB_NAME((object)[]));
        $this->fs(VALIDATOR::DB_NAME('' ));
        $this->fs(VALIDATOR::DB_NAME(' '));
        $this->fs(VALIDATOR::DB_NAME('*'));
        $this->fs(VALIDATOR::DB_NAME('0'));
        $this->fs(VALIDATOR::DB_NAME('1'));
        $this->fs(VALIDATOR::DB_NAME('1name'));
        $this->fs(VALIDATOR::DB_NAME('name name'));
        $this->fs(VALIDATOR::DB_NAME('name-name'));
        $this->fs(VALIDATOR::DB_NAME('_'));
        $this->fs(VALIDATOR::DB_NAME('__'));
        $this->fs(VALIDATOR::DB_NAME('_-_'));
        $this->fs(VALIDATOR::DB_NAME('_9'));
        $this->fs(VALIDATOR::DB_NAME('_9name'));
        $this->fs(VALIDATOR::DB_NAME('name-'));
        $this->fs(VALIDATOR::DB_NAME('name_'));
        $this->fs(VALIDATOR::DB_NAME('name*'));
        $this->fs(VALIDATOR::DB_NAME('Name'));
    }
    public function testDbName()
    {
        $this->tr(VALIDATOR::DB_NAME('name'));
        $this->tr(VALIDATOR::DB_NAME('_name'));
        $this->tr(VALIDATOR::DB_NAME('_name_name'));
        $this->tr(VALIDATOR::DB_NAME('__name__name'));
        $this->tr(VALIDATOR::DB_NAME('_name9'));
        $this->tr(VALIDATOR::DB_NAME('_name9_name9'));
        $this->tr(VALIDATOR::DB_NAME('a'));
        $this->tr(VALIDATOR::DB_NAME('_a'));
    }
    //##########################################################################
    //### EMAIL ###
    //##########################################################################
    public function testEmailFail()
    {
        $this->fs(VALIDATOR::EMAIL(0));
        $this->fs(VALIDATOR::EMAIL(1));
        $this->fs(VALIDATOR::EMAIL(false));
        $this->fs(VALIDATOR::EMAIL(true));
        $this->fs(VALIDATOR::EMAIL(null));
        $this->fs(VALIDATOR::EMAIL(array()));
        $this->fs(VALIDATOR::EMAIL((object)[]));
        $this->fs(VALIDATOR::EMAIL('' ));
        $this->fs(VALIDATOR::EMAIL(' '));
        $this->fs(VALIDATOR::EMAIL('*'));
        $this->fs(VALIDATOR::EMAIL('0'));
        $this->fs(VALIDATOR::EMAIL('1'));
        $this->fs(VALIDATOR::EMAIL('mail@@mail.com'));
        $this->fs(VALIDATOR::EMAIL('mail@mail..com'));
        $this->fs(VALIDATOR::EMAIL('mail..mail@mail.com'));
        $this->fs(VALIDATOR::EMAIL('mail@mail'));
    }
    public function testEmail()
    {
        $this->tr(VALIDATOR::EMAIL('a@b.c'));
        $this->tr(VALIDATOR::EMAIL('mail@mail.com'));
        $this->tr(VALIDATOR::EMAIL('mail@mail.com.com'));
        $this->tr(VALIDATOR::EMAIL('mail.mail@mail.com'));
    }
}
