<?
namespace Core\Helper;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;

class FILTER
{
    //##################################################################################################################
    //### HELPERS ###
    //##################################################################################################################
    // Help other methods to perform commom tasks, but is also open to external use.
    // The main advantage of this replaces are to perform circular substitutions, only returning the value when all the
    //  search values has been replaced in the input string.
    // But it also has a max limit of iterations that can be done in one call.
    //##################################################################################################################
    static private int $MAX_ITERATIONS = 10;
    
    static public function REPLACE (string $search, string $replace, string $input): string
    {
        if($input==="" || $search==="" || $search===$replace){
            return $input;
        }
        $iterations=0;
        while(false!==strpos($input, $search)){
            if(($iterations++)>self::$MAX_ITERATIONS){
                throw new Exception("Max iterations!");
            }
            $input = str_replace($search, $replace, $input);
        }
        return $input;
    }
    static public function REPLACE_CI (string $search, string $replace, string $input): string
    {
        if($input==="" || $search==="" || $search===$replace){
            return $input;
        }
        $iterations=0;
        while(false!==stripos($input, $search)){
            if(($iterations++)>self::$MAX_ITERATIONS){
                throw new Exception("Max iterations!");
            }
            $input = str_ireplace($search, $replace, $input);
        }
        return $input;
    }
    static public function REPLACE_REGEX (string $search, string $replace, mixed $input): string
    {
        if($input==="" || $search===""){
            return $input;
        }
        $iterations=0;
        while(1===preg_match($search, $input)){
            if(($iterations++)>self::$MAX_ITERATIONS){
                throw new Exception("Max iterations!");
            }
            $input = preg_replace($search, $replace, $input);
        }
        return $input;
    }
    //##################################################################################################################
    //### SECURITY ###
    //##################################################################################################################
    static public function FORBIDDEN (string $input): string
    {
        //Strip all forbidden control charecters.
        //That are characters with code point smaller than 32;
        //Except 9, 10 and 13. Tab, line feed and carriage return, respectively.
        //
        // |Dec |Hex |Char |Name             |
        // |  9 | 09 |  \t | Horizontal Tab  |
        // | 10 | 0A |  \r | Line Feed       |
        // | 13 | 0D |  \n | Carriage Return |
        //
        $input = FILTER::REPLACE_REGEX('/[\x{00}-\x{08}]/u', ' ', $input);
        $input = FILTER::REPLACE_REGEX('/[\x{0B}-\x{0C}]/u', ' ', $input);
        $input = FILTER::REPLACE_REGEX('/[\x{0E}-\x{1F}]/u', ' ', $input);
        $input = FILTER::REPLACE_REGEX('/[\x{7F}-\x{A0}]/u', ' ', $input);
        return $input;
    }
    static public function PHP (string $input, bool $encode=false): string
    {
        if($encode===true){
            $input = FILTER::REPLACE('<?', '&#60;&#63;', $input);
            $input = FILTER::REPLACE('?>', '&#63;&#62;', $input);
        }else{
            $input = FILTER::REPLACE_CI('<?php', '', $input);
            $input = FILTER::REPLACE('<?', '', $input);
            $input = FILTER::REPLACE('?>', '', $input);
        }
        return $input;
    }
    static public function QUOTES (string $input, bool $encode=true): string
    {
        if($encode===true){
            $input = FILTER::REPLACE('"', '&#34;', $input);
            $input = FILTER::REPLACE("'", '&#39;', $input);
            $input = FILTER::REPLACE('`', '&#39;', $input);
            $input = FILTER::REPLACE('´', '&#39;', $input);
        }else{
            $input = FILTER::REPLACE_REGEX('/(\"|\'|\`|\´)/', ' ', $input);
        }
        return $input;
    }
    static public function SECURE (string $input, bool $phpEncode=false, bool $quotesEncode=true): string
    {
        $input = FILTER::FORBIDDEN($input);
        $input = FILTER::PHP($input, $phpEncode);
        $input = FILTER::QUOTES($input, $quotesEncode);
        return $input;
    }
    //##################################################################################################################
    //### NORMALIZE ###
    //##################################################################################################################
    // Normalize the input string, translating some characters to fit the desired rule.
    // For example, the  ALPHA rule translate some characters with accents to regular characters.
    //##################################################################################################################
    static public function NORMALIZE_SPACES (string $input): string
    {
        $input = FILTER::REPLACE("\t", ' ', $input);
        $input = FILTER::REPLACE_REGEX('/( ){3,}/', '  ', $input);
        return $input;
    }
    static public function NORMALIZE_BREAKS (string $input): string
    {
        $input = FILTER::REPLACE("\r\n", "\n", $input); //WINDOWS
        $input = FILTER::REPLACE("\r",   "\n", $input); //MAC
        
        $input = FILTER::REPLACE_REGEX('/( |\t)+\n/', "\n", $input); //TRAILING
        
        $input = FILTER::REPLACE_REGEX('/(\n){3,}/', "\n\n", $input);//TRIPLE
        
        return $input;
    }
    static public function NORMALIZE_NUMBER (string $input): string
    {
        return FILTER::REPLACE_REGEX('/[^0-9\.\-]/', '', $input);
    }
    static public function NORMALIZE_ALPHA (string $input): string
    {
        //ACCENTS
        $input = FILTER::REPLACE_REGEX('/(\À|\Á|\Â|\Ã|\Ä|\Å)/', 'A', $input);
        $input = FILTER::REPLACE_REGEX('/(\È|\É|\Ê|\Ë)/', 'E', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ì|\Í|\Î|\Ï)/', 'I', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ò|\Ó|\Ô|\Õ|\Ö|\Ø)/', 'O', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ù|\Ú|\Û|\Ü)/', 'U', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ý|\Ÿ)/', 'Y', $input);
        
        $input = FILTER::REPLACE_REGEX('/(\à|\á|\â|\ã|\ä|\å)/', 'a', $input);
        $input = FILTER::REPLACE_REGEX('/(\è|\é|\ê|\ë)/', 'e', $input);
        $input = FILTER::REPLACE_REGEX('/(\ì|\í|\î|\ï)/', 'i', $input);
        $input = FILTER::REPLACE_REGEX('/(\ò|\ó|\ô|\õ|\ö|\ø|\ð)/', 'o', $input);
        $input = FILTER::REPLACE_REGEX('/(\ù|\ú|\û|\ü)/', 'u', $input);
        $input = FILTER::REPLACE_REGEX('/(\ý|\ÿ)/', 'y', $input);
        
        //SPECIAL
        $input = FILTER::REPLACE('Ç', 'C', $input);//199
        $input = FILTER::REPLACE('Ñ', 'N', $input);//209
        $input = FILTER::REPLACE('ç', 'c', $input);//231
        $input = FILTER::REPLACE('ñ', 'n', $input);//241
        
        //CHARS SCOPE
        $input = FILTER::REPLACE_REGEX('/[^a-zA-Z0-9 ]/', ' ', $input);
        
        return $input;
    }
    static public function NORMALIZE_KEYWORD (string $input): string
    {
        //ACCENTS
        $input = FILTER::REPLACE_REGEX('/(\À|\Á|\Â|\Ã|\Ä|\Å)/', 'A', $input);
        $input = FILTER::REPLACE_REGEX('/(\È|\É|\Ê|\Ë)/', 'E', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ì|\Í|\Î|\Ï)/', 'I', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ò|\Ó|\Ô|\Õ|\Ö|\Ø)/', 'O', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ù|\Ú|\Û|\Ü)/', 'U', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ý|\Ÿ)/', 'Y', $input);
        
        $input = FILTER::REPLACE_REGEX('/(\à|\á|\â|\ã|\ä|\å)/', 'a', $input);
        $input = FILTER::REPLACE_REGEX('/(\è|\é|\ê|\ë)/', 'e', $input);
        $input = FILTER::REPLACE_REGEX('/(\ì|\í|\î|\ï)/', 'i', $input);
        $input = FILTER::REPLACE_REGEX('/(\ò|\ó|\ô|\õ|\ö|\ø|\ð)/', 'o', $input);
        $input = FILTER::REPLACE_REGEX('/(\ù|\ú|\û|\ü)/', 'u', $input);
        $input = FILTER::REPLACE_REGEX('/(\ý|\ÿ)/', 'y', $input);
        
        //SPECIAL
        $input = FILTER::REPLACE('Ç', 'C', $input);//199
        $input = FILTER::REPLACE('Ñ', 'N', $input);//209
        $input = FILTER::REPLACE('ç', 'c', $input);//231
        $input = FILTER::REPLACE('ñ', 'n', $input);//241
        
        //CHARS SCOPE
        $input = FILTER::REPLACE_REGEX('/[^a-zA-Z0-9 \_\-\.]/', ' ', $input);
        
        return $input;
    }
    static public function NORMALIZE_ASCII (string $input): string
    {
        //ACCENTS
        $input = FILTER::REPLACE_REGEX('/(\À|\Á|\Â|\Ã|\Ä|\Å)/', 'A', $input);
        $input = FILTER::REPLACE_REGEX('/(\È|\É|\Ê|\Ë)/', 'E', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ì|\Í|\Î|\Ï)/', 'I', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ò|\Ó|\Ô|\Õ|\Ö|\Ø)/', 'O', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ù|\Ú|\Û|\Ü)/', 'U', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ý|\Ÿ)/', 'Y', $input);
        
        $input = FILTER::REPLACE_REGEX('/(\à|\á|\â|\ã|\ä|\å)/', 'a', $input);
        $input = FILTER::REPLACE_REGEX('/(\è|\é|\ê|\ë)/', 'e', $input);
        $input = FILTER::REPLACE_REGEX('/(\ì|\í|\î|\ï)/', 'i', $input);
        $input = FILTER::REPLACE_REGEX('/(\ò|\ó|\ô|\õ|\ö|\ø|\ð)/', 'o', $input);
        $input = FILTER::REPLACE_REGEX('/(\ù|\ú|\û|\ü)/', 'u', $input);
        $input = FILTER::REPLACE_REGEX('/(\ý|\ÿ)/', 'y', $input);
        
        //SPECIAL
        $input = FILTER::REPLACE('Ç', 'C', $input);//199
        $input = FILTER::REPLACE('Ñ', 'N', $input);//209
        $input = FILTER::REPLACE('ç', 'c', $input);//231
        $input = FILTER::REPLACE('ñ', 'n', $input);//241
        
        //CHARS SCOPE
        $input = FILTER::REPLACE_REGEX('/[^\x{20}-\x{7E}]/u', ' ', $input);
        
        return $input;
    }
    static public function NORMALIZE_PT_BR (string $input): string
    {
        $input = FILTER::REPLACE(mb_chr(161), '!', $input);
        $input = FILTER::REPLACE(mb_chr(166), '|', $input);
        $input = FILTER::REPLACE(mb_chr(173), '-', $input);
        $input = FILTER::REPLACE(mb_chr(175), '-', $input);
        $input = FILTER::REPLACE(mb_chr(191), '?', $input);
        
        $input = FILTER::REPLACE('Æ', 'AE', $input);//mb_chr(198)
        $input = FILTER::REPLACE('Ð', 'D', $input);//mb_chr(208)
        $input = FILTER::REPLACE('Ñ', 'N', $input);
        
        $input = FILTER::REPLACE('æ', 'ae', $input);
        $input = FILTER::REPLACE('ñ', 'n', $input);
        $input = FILTER::REPLACE('×', 'x', $input);
        
        $input = FILTER::REPLACE_REGEX('/(\ä|\å)/', 'a', $input);
        $input = FILTER::REPLACE_REGEX('/(\ë)/', 'e', $input);
        $input = FILTER::REPLACE_REGEX('/(\ï)/', 'i', $input);
        $input = FILTER::REPLACE_REGEX('/(\ö|\ø|\ð)/', 'o', $input);
        $input = FILTER::REPLACE_REGEX('/(\ü)/', 'u', $input);
        $input = FILTER::REPLACE_REGEX('/(\ý|\ÿ)/', 'y', $input);
        
        $input = FILTER::REPLACE_REGEX('/(\Ä|\Å)/', 'A', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ë)/', 'E', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ï)/', 'I', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ö|\Ø)/', 'O', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ü)/', 'U', $input);
        $input = FILTER::REPLACE_REGEX('/(\Ý|\Ÿ)/', 'Y', $input);
        
        $input = FILTER::REPLACE_REGEX('/[^\x{0A}\x{20}-\x{7E}\x{A2}-\x{FF}]/u', ' ', $input);
        
        return $input;
    }
    //##################################################################################################################
    //### SPACES ###
    //##################################################################################################################
    static public function DOUBLE_BREAKS (string $input): string
    {
        $input = FILTER::REPLACE("\n\n", "\n", $input);
        return $input;
    }
    static public function DOUBLE_SPACES (string $input): string
    {
        $input = FILTER::REPLACE('  ', ' ', $input);
        return $input;
    }
    static public function BREAKS (string $input): string
    {
        $input = FILTER::REPLACE("\n", ' ', $input);
        return $input;
    }
    static public function SPACES (string $input): string
    {
        $input = FILTER::REPLACE(' ', '', $input);
        return $input;
    }
    //##################################################################################################################
    //### SANITIZE ###
    //##################################################################################################################
    static public function BOOL (string $input): string
    {
        if($input===' '){
            return '1';
        }
        if($input===''){
            return '0';
        }
        
        $input = FILTER::NORMALIZE_KEYWORD($input);
        $input = FILTER::SPACES($input);
        $input = strtoupper($input);
        
        if( $input==='1' ||
            $input==='T' ||
            $input==='TRUE'
        ){
            return '1';
        }
        
        if( $input==='0' ||
            $input==='F' ||
            $input==='FALSE'
        ){
            return '0';
        }
        
        return '';
    }
    static public function NUMBER (string $input): string
    {
        $input = FILTER::REPLACE_REGEX('/([0-9])(\,)([0-9])/', '${1}${3}', $input);
        if(!is_numeric($input)){
            return '';
        }
        $input = FILTER::NORMALIZE_NUMBER($input);
        if($input!=='' && $input[0]==='.'){
            $input = '0'.$input;
        }
        return $input;
    }
    static public function INT (string $input): string
    {
        $input = FILTER::NUMBER($input);
        $input = FILTER::REPLACE_REGEX('/\..*/', '', $input);
        return $input;
    }
    static public function KEYWORD (string $input): string
    {
        //Email, OrderField, DbField, FileName
        $input = FILTER::NORMALIZE_KEYWORD($input);
        $input = FILTER::QUOTES($input, false);
        $input = FILTER::SPACES($input);
        $input = FILTER::PHP($input, false);
        $input = FILTER::SPACES($input);
        return trim($input);
    }
    static public function ASCII (string $input): string
    {
        //URL, Host
        $input = FILTER::NORMALIZE_ASCII($input);
        $input = FILTER::QUOTES($input, false);
        $input = FILTER::PHP($input, false);
        $input = FILTER::DOUBLE_SPACES($input);
        return trim($input);
    }
    static public function NAME (string $input): string
    {
        //UserName, UserSurname, NewsTitle, TagName, CategoryName, SearchTerms
        $input = FILTER::NORMALIZE_SPACES($input);
        $input = FILTER::NORMALIZE_BREAKS($input);
        $input = FILTER::NORMALIZE_PT_BR($input);
        $input = FILTER::BREAKS($input);
        $input = FILTER::QUOTES($input, true);
        $input = FILTER::PHP($input, false);
        $input = FILTER::DOUBLE_SPACES($input);
        return trim($input);
    }
    static public function TEXT (string $input): string
    {
        //NewsDescription, NewsContent, CategoryDescription
        $input = FILTER::NORMALIZE_SPACES($input);
        $input = FILTER::NORMALIZE_BREAKS($input);
        $input = FILTER::NORMALIZE_PT_BR($input);
        $input = FILTER::QUOTES($input, true);
        $input = FILTER::PHP($input, false);
        $input = FILTER::NORMALIZE_SPACES($input);
        $input = FILTER::NORMALIZE_BREAKS($input);
        return trim($input);
    }
    static public function URL (string $input): string
    {
        $input = FILTER::NORMALIZE_KEYWORD($input);
        $input = FILTER::PHP($input, false);
        $input = FILTER::QUOTES($input, false);
        $input = self::REPLACE_REGEX('/( |\.|\_)+/', '-', $input);
        $input = self::REPLACE_REGEX('/(\-\-)+/', '-', $input);
        $input = strtolower($input);
        //TRIM
        $input = self::REPLACE_REGEX('/^( |\-|\_)+/', '', $input);
        $input = self::REPLACE_REGEX('/( |\-|\_)+$/', '', $input);
        return $input;
    }
    //##################################################################################################################
    //### SANITIZE - COMPLEX DATA ###
    //##################################################################################################################
    static public function ID_LIST (mixed $input): array
    {
        $result = [];
        
        if(is_string($input)){
            $input = FILTER::SPACES($input);
            if($input!==''){
                try{
                    $input = json_decode($input, true, 2, JSON_THROW_ON_ERROR);
                }
                catch(Exception $exception){
                    throw new Exception("FILTER error!\n".$exception->getMessage()."!");
                }
            }
        }
        
        if(is_array($input)){
            foreach($input as $inputId){
                $inputId = intval(FILTER::INT($inputId));
                if($inputId>0){
                    $result[$inputId] = $inputId;
                }
            }
        }
        
        return $result;
    }
    static public function SQL_SEARCH (string $input): string
    {
        $searchTerms = [];
        
        $input = FILTER::REPLACE("'", '', $input);
        $input = FILTER::NORMALIZE_ALPHA($input);
        $input = FILTER::NORMALIZE_SPACES($input);
        $input = FILTER::QUOTES($input, false);
        $input = FILTER::PHP($input, false);
        $input = FILTER::DOUBLE_SPACES($input);
        $input = trim($input);
        
        if($input===''){
            return '';
        }
        
        $inputTerms = explode(' ', $input);
        foreach($inputTerms as $term){
            $termIndex = strtolower($term);
            $searchTerms[$termIndex] = $term;
        }
        
        return implode(' ', $searchTerms);
    }
    static public function SQL_ORDER (string $input, array $allowedFields): string
    {
        return '';
    }
    //##################################################################################################################
    //### RUN ###
    //##################################################################################################################
    // Run a filter or a set of filters in a input string.
    //##################################################################################################################
    static public function RUN(string $input, mixed $sanitizers): string
    {
        if(is_string($sanitizers)){
            $sanitizers=[$sanitizers];
        }elseif(!is_array($sanitizers)){
            throw new Exception("FILTER error!\nInvalid sanitizers!");
        }
        
        foreach($sanitizers as $filter)
        {
            //### SECURITY ###
            if($filter==="forbidden"){
                $input = self::FORBIDDEN($input);
                continue;
            }
            if($filter==="php"){
                $input = self::PHP($input, false);
                continue;
            }
            if($filter==="php-encode"){
                $input = self::PHP($input, true);
                continue;
            }
            if($filter==="quotes"){
                $input = self::QUOTES($input, false);
                continue;
            }
            if($filter==="quotes-encode"){
                $input = self::QUOTES($input, true);
                continue;
            }
            if($filter==="secure"){
                $input = self::SECURE($input, false, true);
                continue;
            }
            if($filter==="secure-strip"){
                $input = self::SECURE($input, false, false);
                continue;
            }
            if($filter==="secure-encode"){
                $input = self::SECURE($input, true, true);
                continue;
            }
            //### NORMALIZE ###
            if($filter==="normalize-spaces"){
                $input = self::NORMALIZE_SPACES($input);
                continue;
            }
            if($filter==="normalize-breaks"){
                $input = self::NORMALIZE_BREAKS($input);
                continue;
            }
            if($filter==="normalize-number"){
                $input = self::NORMALIZE_NUMBER($input);
                continue;
            }
            if($filter==="normalize-alpha"){
                $input = self::NORMALIZE_ALPHA($input);
                continue;
            }
            if($filter==="normalize-keyword"){
                $input = self::NORMALIZE_KEYWORD($input);
                continue;
            }
            if($filter==="normalize-ascii"){
                $input = self::NORMALIZE_ASCII($input);
                continue;
            }
            if($filter==="normalize-pt-br"){
                $input = self::NORMALIZE_PT_BR($input);
                continue;
            }
            //### SPACES ###
            if($filter==="double-breaks"){
                $input = self::DOUBLE_BREAKS($input);
                continue;
            }
            if($filter==="double-spaces"){
                $input = self::DOUBLE_SPACES($input);
                continue;
            }
            if($filter==="breaks"){
                $input = self::BREAKS($input);
                continue;
            }
            if($filter==="spaces"){
                $input = self::SPACES($input);
                continue;
            }
            //### SANITIZE ###
            if($filter==="bool"){
                $input = self::BOOL($input);
                continue;
            }
            if($filter==="number"){
                $input = self::NUMBER($input);
                continue;
            }
            if($filter==="int"){
                $input = self::INT($input);
                continue;
            }
            if($filter==="keyword"){
                $input = self::KEYWORD($input);
                continue;
            }
            if($filter==="ascii"){
                $input = self::ASCII($input);
                continue;
            }
            if($filter==="name"){
                $input = self::NAME($input);
                continue;
            }
            if($filter==="text"){
                $input = self::TEXT($input);
                continue;
            }
            if($filter==="url"){
                $input = self::URL($input);
                continue;
            }
            //### ERROR ###
            throw new Exception("FILTER error!\nFilter '$filter' not found!");
        }
        return $input;
    }

}
