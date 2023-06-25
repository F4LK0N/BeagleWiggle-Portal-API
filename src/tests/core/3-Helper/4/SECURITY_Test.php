<?
use Core\Helper\SECURITY;

class SECURITY_Test extends FknTestCase
{
    public function testHash()
    {
        $this->eq(
            '5B118EA53E17310CE1142F79DF13BC46629938A0449BC8C2C56256C54202F612',
            SECURITY::HASH('')
        );
        $this->eq(
            'F98B8EF4D044FAB5451A084255B58433324EF8EB0D970AA320A3E2853771C186',
            SECURITY::HASH('A')
        );
        $this->eq(
            'B493417E651B187116E2DC36E8271E0867CECF768C28E069350023D74E30BE0B',
            SECURITY::HASH('B')
        );
        $this->eq(
            '894596E9BCCECFFE8A66EFA11D56644C89CC666DD005334C64873EEE59D2268B',
            SECURITY::HASH('AB')
        );
        $this->eq(
            '006F82799664F20280DCEB85C264C29B5BDF5E87C8F49ED1108EF0E9CE01EBC8',
            SECURITY::HASH('ABC')
        );
        $this->eq(
            'FBF33978866331AE5B5F85FF63F80576DBC24BF1F9860BA2AC7EA515AA0165B0',
            SECURITY::HASH('ABCD')
        );
    }
    public function testToken()
    {
        $token = SECURITY::TOKEN();
        $this->eq(64, strlen($token));
    }
}
