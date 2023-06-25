<?
use Core\Helper\FILTER;
use Core\Helper\JSON;

class JSON_Test extends FknTestCase
{
    //##########################################################################
    //### ENCODE ###
    //##########################################################################
    public function testEncodeBool()
    {
        $this->eq('true',  JSON::ENCODE(true));
        $this->eq('false', JSON::ENCODE(false));
    }
    public function testEncodeInt()
    {
        $this->eq('0',    JSON::ENCODE(0));
        $this->eq('1',    JSON::ENCODE(1));
        $this->eq('1000', JSON::ENCODE(1000));
        $this->eq('-1',   JSON::ENCODE(-1));
    }
    public function testEncodeString()
    {
        $this->eq('"value"', JSON::ENCODE('value'));
        $this->eq('"1"',     JSON::ENCODE('1'));
    }
    public function testEncodeText()
    {
        $input = self::INPUT_COMMOM();
        $input = FILTER::TEXT($input);
        $input = FILTER::SPACES($input);
        $this->eq(
            '"!\u0026#34;#$%\u0026\u0026#39;()*+,-.\/0123456789:;\u003C=\u003E?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\\\]^_\u0026#39;abcdefghijklmnopqrstuvwxyz{|}~!\u00a2\u00a3\u00a4\u00a5|\u00a7\u00a8\u00a9\u00aa\u00ab\u00ac-\u00ae-\u00b0\u00b1\u00b2\u00b3\u0026#39;\u00b5\u00b6\u00b7\u00b8\u00b9\u00ba\u00bb\u00bc\u00bd\u00be?\u00c0\u00c1\u00c2\u00c3AAAE\u00c7\u00c8\u00c9\u00caE\u00cc\u00cd\u00ceIDN\u00d2\u00d3\u00d4\u00d5OxO\u00d9\u00da\u00dbUY\u00de\u00df\u00e0\u00e1\u00e2\u00e3aaae\u00e7\u00e8\u00e9\u00eae\u00ec\u00ed\u00eeion\u00f2\u00f3\u00f4\u00f5o\u00f7o\u00f9\u00fa\u00fbuy\u00feyY"',
            JSON::ENCODE($input))
        ;
    }
    public function testEncodeArray()
    {
        $object = [
            'A',
            'B',
        ];
        $this->eq('["A","B"]', JSON::ENCODE($object));
    }
    public function testEncodeObject()
    {
        $object = new stdClass();
        $object->key = "value";
        $this->eq('{"key":"value"}', JSON::ENCODE($object));
        
        $object = [];
        $object['key'] = "value";
        $this->eq('{"key":"value"}', JSON::ENCODE($object));
    }
    public function testEncodeAllTypes()
    {
        $object = new stdClass();
        $object->keyBool = true;
        $object->keyInt = 1;
        $object->keyString = "value";
        $object->keyArray = ["A","B"];
        $object->keyObject = (object)["key"=>"value"];
        
        $this->eq(
            '{'.
                '"keyBool":true,'.
                '"keyInt":1,'.
                '"keyString":"value",'.
                '"keyArray":["A","B"],'.
                '"keyObject":{"key":"value"}'.
            '}',
            JSON::ENCODE($object)
        );
    }
    public function testEncodeErrorDepth()
    {
        $object=
        [[[[//4
            [[[[//8
                [[[[//12
                    [[[[//16
                        ['A']
                    ]]]]
                ]]]]
            ]]]]
        ]]]];
        
        $this->expectException('Exception');
        $this->expectExceptionMessage("JSON Encode error!\nMaximum stack depth exceeded!");
        JSON::ENCODE($object);
    }
    //##########################################################################
    //### DECODE ###
    //##########################################################################
    public function testDecodeBool()
    {
        $this->eq(true,  JSON::DECODE('true'));
        $this->eq(false, JSON::DECODE('false'));
    }
    public function testDecodeInt()
    {
        $this->eq(0,    JSON::DECODE('0'));
        $this->eq(1,    JSON::DECODE('1'));
        $this->eq(1000, JSON::DECODE('1000'));
        $this->eq(-1,   JSON::DECODE('-1'));
    }
    public function testDecodeString()
    {
        $this->eq('value', JSON::DECODE('"value"'));
        $this->eq('1',     JSON::DECODE('"1"'));
    }
    public function testDecodeText()
    {
        $expected = self::INPUT_COMMOM();
        $expected = FILTER::TEXT($expected);
        $expected = FILTER::SPACES($expected);
        $this->eq(
            $expected,
            JSON::DECODE('"!\u0026#34;#$%\u0026\u0026#39;()*+,-.\/0123456789:;\u003C=\u003E?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\\\]^_\u0026#39;abcdefghijklmnopqrstuvwxyz{|}~!\u00a2\u00a3\u00a4\u00a5|\u00a7\u00a8\u00a9\u00aa\u00ab\u00ac-\u00ae-\u00b0\u00b1\u00b2\u00b3\u0026#39;\u00b5\u00b6\u00b7\u00b8\u00b9\u00ba\u00bb\u00bc\u00bd\u00be?\u00c0\u00c1\u00c2\u00c3AAAE\u00c7\u00c8\u00c9\u00caE\u00cc\u00cd\u00ceIDN\u00d2\u00d3\u00d4\u00d5OxO\u00d9\u00da\u00dbUY\u00de\u00df\u00e0\u00e1\u00e2\u00e3aaae\u00e7\u00e8\u00e9\u00eae\u00ec\u00ed\u00eeion\u00f2\u00f3\u00f4\u00f5o\u00f7o\u00f9\u00fa\u00fbuy\u00feyY"')
        );
    }
    public function testDecodeArray()
    {
        $input = '["A","B"]';
        $array = JSON::DECODE($input);
        
        $this->tr(is_array($array));
        $this->eq(2, count($array));
        $this->eq('A', $array[0]);
        $this->eq('B', $array[1]);
    }
    public function testDecodeObject()
    {
        $input = '{"key":"value"}';
        $object = JSON::DECODE($input);
        
        $this->tr(is_object($object));
        $this->tr(isset($object->key));
        $this->eq('value', $object->key);
    }
    public function testDecodeAllTypes()
    {
        $input = 
        '{'.
            '"keyBool":true,'.
            '"keyInt":1,'.
            '"keyString":"value",'.
            '"keyArray":["A","B"],'.
            '"keyObject":{"key":"value"}'.
        '}';
        $object = JSON::DECODE($input);
        
        $this->tr(is_object($object));
        
        $this->tr(isset($object->keyBool));
        $this->eq(true, $object->keyBool);
        
        $this->tr(isset($object->keyInt));
        $this->eq(1, $object->keyInt);
        
        $this->tr(isset($object->keyString));
        $this->eq("value", $object->keyString);
        
        $this->tr(isset($object->keyArray));
        $this->tr(is_array($object->keyArray));
        $this->eq(2, count($object->keyArray));
        $this->eq('A', $object->keyArray[0]);
        $this->eq('B', $object->keyArray[1]);
        
        $this->tr(is_object($object->keyObject));
        $this->tr(isset($object->keyObject->key));
        $this->eq('value', $object->keyObject->key);
    }
    public function testDecodeError()
    {
        $input = 
        '[[[['.//4
            '[[[['.//8
                '[[[['.//12
                    '[[[['.//16
                        '["A"]'.
                    ']]]]'.
                ']]]]'.
            ']]]]'.
        ']]]]';
        
        $this->expectException('Exception');
        $this->expectExceptionMessage("JSON Decode error!\nMaximum stack depth exceeded!");
        $object = JSON::DECODE($input);
    }
}
