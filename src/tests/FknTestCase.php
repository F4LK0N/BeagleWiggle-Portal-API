<?
defined("FKN") or http_response_code(403).die('Forbidden!');
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class FknTestCase extends PHPUnitTestCase
{
    //###################################
    //### FAKER ###
    //###################################
    static private ?Faker\Generator $FAKER_INSTANCE = null;
    
    static public function &FAKER (): Faker\Generator
    {
        if(self::$FAKER_INSTANCE===null){
            self::$FAKER_INSTANCE = Faker\Factory::create();
        }
        return self::$FAKER_INSTANCE;
    }
    //###################################
    //### DB ###
    //###################################
    static private ?Core\Provider\DB $DB_INSTANCE = null;
    
    static public function &DB (): Core\Provider\DB
    {
        if(self::$DB_INSTANCE===null){
            self::$DB_INSTANCE = Core\Provider\DB::INSTANCE();
        }
        //$temp = &self::$DB_INSTANCE;
        return self::$DB_INSTANCE;
    }
    
    static public function DB_TRUNCATE ($table): void
    {
        self::DB()->execute("TRUNCATE `$table`");
    }
    //###################################
    //### INPUTS ###
    //###################################
    static private ?string $INPUT_COMMOM = null;
    static public function INPUT_COMMOM (): string
    {
        if(self::$INPUT_COMMOM===null){
            self::$INPUT_COMMOM="";
            for($codepoint=0; $codepoint<256; $codepoint++){
                self::$INPUT_COMMOM.=mb_chr($codepoint);
            }
            self::$INPUT_COMMOM.=mb_chr(376);//Ÿ
        }
        return self::$INPUT_COMMOM;
    }
    
    static private ?string $INPUT_EXTRA = null;
    static public function INPUT_EXTRA (): string
    {
        if(self::$INPUT_EXTRA===null){
            self::$INPUT_EXTRA="";
            for($codepoint=256; $codepoint<1000; $codepoint++){
                if($codepoint===376){//Ÿ
                    continue;
                }
                self::$INPUT_EXTRA.=mb_chr($codepoint);
            }
        }
        return self::$INPUT_EXTRA;
    }
    //###################################
    //### ASSERTIONS ###
    //###################################
    public function eq ($expected, $actual): void
    {
        $this->assertEquals($expected, $actual);
    }
    public function tr ($actual): void
    {
        $this->assertTrue($actual);
    }
    public function fs ($actual): void
    {
        $this->assertFalse($actual);
    }
    public function rx ($expected, $actual): void
    {
        $this->assertMatchesRegularExpression($expected, $actual);
    }
}


