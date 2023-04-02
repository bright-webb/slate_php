<?php
    namespace Slate;

    class Str{
        protected static $studlyCache = [];
        protected static $camelCache = [];
        protected static $snakeCache = [];

        // Check if a string matches a pattern
        public static function is($pattern, $value){
            if($pattern == $value){
                return true;
            }
            return false;
        }

        // Get the plural form of an English word
        public static function plural($value, $count = 2){
            if($count == 1){
                return $value;
            }
            return $value.'s';
        }

        // Get the singular form of an English word
        public static function singular($value){
            if(Str::endsWith($value, 's')){
                return substr($value, 0, -1);
            }
            return $value;
        }

        // Check if a string starts with a given substring
        public static function startsWith($haystack, $needles){
            foreach((array) $needles as $needle){
                if($needle != '' && mb_strpos($haystack, $needle) === 0){
                    return true;
                }
            }
            return false;
        }

        // Check if a string ends with a given substring
        public static function endsWith($haystack, $needles){
            foreach((array) $needles as $needle){
                if($needle != '' && substr($haystack, -strlen($needle)) === (string) $needle){
                    return true;
                }
            }
            return false;
        }

        // Convert a string to snake case
        public static function snake($value, $delimiter = '_'){
            $key = $value;
            if(isset(static::$snakeCache[$key][$delimiter])){
                return static::$snakeCache[$key][$delimiter];
            }
            if(!ctype_lower($value)){
                $value = preg_replace('/\s+/u', '', ucwords($value));
                $value = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value), 'UTF-8');
            }
            return static::$snakeCache[$key][$delimiter] = $value;
        }

        // Convert a string to camel case
        public static function camel($value){
            if(isset(static::$camelCache[$value])){
                return static::$camelCache[$value];
            }
            return static::$camelCache[$value] = lcfirst(static::studly($value));
        }

        // Convert a value to studly caps case
        public static function studly($value){
            $key = $value;
            if(isset(static::$studlyCache[$key])){
                return static::$studlyCache[$key];
            }
            $value = ucwords(str_replace(['-', '_'], ' ', $value));
            return static::$studlyCache[$key] = str_replace(' ', '', $value);
        }

        // Generate a more truly "random" alpha-numeric string
        public static function random($length = 16){
            $string = '';
            while(($len = mb_strlen($string)) < $length){
                $size = $length - $len;
                $bytes = random_bytes($size);
                $string .= mb_substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size, '8bit');
            }
            return $string;
        }

        // Generate a URL friendly "slug" from a given string
        public static function slug($title, $separator = '-', $language = 'en'){
            $title = static::ascii($title, $language);
            // Convert all dashes/underscores into separator
            $flip = $separator == '-' ? '_' : '-';
            $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);
            // Remove all characters that are not the separator, letters, numbers, or whitespace
            $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title, 'UTF-8'));
            // Replace all separator characters and whitespace by a single separator
            $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);
            return trim($title, $separator);
        }

        // Get the portion of a string before the first occurrence of a given value
        public static function before($subject, $search){
            return $search === '' ? $subject : explode($search, $subject)[0];
        }

        // Get the portion of a string after the first occurrence of a given value
        public static function after($subject, $search){
            return $search === '' ? $subject : array_reverse(explode($search, $subject))[0];
        }

        // Get the portion of a string before the last occurrence of a given value
        public static function beforeLast($subject, $search){
            if($search === ''){
                return $subject;
            }
            $position = mb_strrpos($subject, $search);
            if($position === false){
                return $subject;
            }
            return mb_substr($subject, 0, $position);
        }

        // Get the portion of a string after the last occurrence of a given value
        public static function afterLast($subject, $search){
            if($search === ''){
                return $subject;
            }
            $position = mb_strrpos($subject, $search);
            if($position === false){
                return $subject;
            }
            return mb_substr($subject, $position + mb_strlen($search));
        }

        // Convert a string to title case
        public static function title($value){
            return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        }

        // Limit the number of characters in a string
        public static function limit($value, $limit = 100, $end = '...'){
            if(mb_strwidth($value, 'UTF-8') <= $limit){
                return $value;
            }
            return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
        }

        // Limit the number of words in a string
        public static function words($value, $words = 100, $end = '...'){
            preg_match('/^\s*+(?:\S++\s*+){1,'.$words.'}/u', $value, $matches);
            if(!isset($matches[0]) || mb_strlen($value) === mb_strlen($matches[0])){
                return $value;
            }
            return rtrim($matches[0]).$end;
        }

        // Convert a value to kebab case
        public static function kebab($value){
            return static::snake($value, '-');
        }

        // Determine if a given string contains a given substring
        public static function contains($haystack, $needles){
            foreach((array) $needles as $needle){
                if($needle != '' && mb_strpos($haystack, $needle) !== false){
                    return true;
                }
            }
            return false;
        }

        /**
         * @return array
         */
        private static function getLanguageNeutralReplacements(){
            $languageNeutral = [
                ' ' => '-', '_' => '-', '.' => '-', '[' => '-', ']' => '-', '(' => '-', ')' => '-', '{' => '-', '}' => '-', '+' => '-', '#' => '-', '@' => '-', '!' => '-', '$' => '-', '%' => '-', '^' => '-', '&' => '-', '*' => '-', '?' => '-', '/' => '-', '\\' => '-', '|' => '-', '\'' => '', '"' => '', '`' => '', '’' => '', '‘' => '', '“' => '', '”' => '', '–' => '-', '—' => '-', ' ' => '-', '…' => '-', '‹' => '-', '›' => '-', '«' => '-', '»' => '-', '°' => '-', '·' => '-', '‚' => '-', '„' => '-', '´' => '-', '¿' => '-', '¡' => '-', '¼' => '-', '½' => '-', '¾' => '-', '⅓' => '-', '⅔' => '-', '⅛' => '-', '⅜' => '-', '⅝' => '-', '⅞' => '-', '⅙' => '-', '⅚' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅐' => '-', '⅑' =>
                '-', '⅒' => '-', '⅛' => '-', '⅜' => '-', '⅝' => '-', '⅞' => '-', '⅙' => '-', '⅚' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅙' => '-', '⅚' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅙' => '-', '⅚' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅙' => '-', '⅚' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅙' => '-', '⅚' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅙' => '-', '⅚' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-', '⅖' => '-', '⅗' => '-', '⅘' => '-', '⅙' => '-', '⅚' => '-', '⅐' => '-', '⅑' => '-', '⅒' => '-', '⅓' => '-', '⅔' => '-', '⅕' => '-',
            ];

            return $languageNeutral;
        }


        /**
         * @param string $string
         * @param string $language
         * @return string
         */

         /**
          * @param string $str
          * @return string
          */
         public static function lower($str){
            return mb_strtolower($str, 'UTF-8');
         }

        /**
        * @param string $str
        * @return string
        */
        public static function upper($str){
            return mb_strtoupper($str, 'UTF-8');
        }

       // replace all non-ascii characters with their ascii equivalents
       public static function ascii($str){
           $foreign_characters = array(
               '/ä|æ|ǽ/' => 'ae',
               '/ö|œ/' => 'oe',
               '/ü/' => 'ue',
               '/Ä/' => 'Ae',
               '/Ü/' => 'Ue',
               '/Ö/' => 'Oe',
               '/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
               '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',
               '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
               '/ç|ć|ĉ|ċ|č/' => 'c',
               '/Ð|Ď|Đ/' => 'D',
               '/ð|ď|đ/' => 'd',
               '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
               '/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
               '/Ĝ|Ğ|Ġ|Ģ/' => 'G',
               '/ĝ|ğ|ġ|ģ/' => 'g',
               '/Ĥ|Ħ/' => 'H',
               '/ĥ|ħ/' => 'h',
               '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' => 'I',
               '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/' => 'i',
               '/Ĵ/' => 'J',
               '/ĵ/' => 'j',
               '/Ķ/' => 'K',
               '/ķ/' => 'k',
               '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
               '/ĺ|ļ|ľ|ŀ|ł/' => 'l',
               '/Ñ|Ń|Ņ|Ň/' => 'N',
               '/ñ|ń|ņ|ň|ŉ/' => 'n',
               '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
                '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
                '/Ŕ|Ŗ|Ř/' => 'R',
                '/ŕ|ŗ|ř/' => 'r',
                '/Ś|Ŝ|Ş|Š/' => 'S',
                '/ś|ŝ|ş|š|ſ/' => 's',
                '/Ţ|Ť|Ŧ/' => 'T',
                '/ţ|ť|ŧ/' => 't',
                '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
                '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
                '/Ý|Ÿ|Ŷ/' => 'Y',
                '/ý|ÿ|ŷ/' => 'y',
                '/Ŵ/' => 'W',
                '/ŵ/' => 'w',
                '/Ź|Ż|Ž/' => 'Z',
                '/ź|ż|ž/' => 'z',
                '/Æ|Ǽ/' => 'AE',
                '/ß/' => 'ss',
                '/Ĳ/' => 'IJ',
                '/ĳ/' => 'ij',
                '/Œ/' => 'OE',
                '/ƒ/' => 'f',
                '/Α|Ά|Ἀ|Ἁ|Ἂ|Ἃ|Ἄ|Ἅ|Ἆ|Ἇ|ᾈ|ᾉ|ᾊ|ᾋ|ᾌ|ᾍ|ᾎ|ᾏ|Ᾰ|Ᾱ|Ὰ|Ά|ᾼ|А/' => 'A',);

              return preg_replace(array_keys($foreign_characters), array_values($foreign_characters), $str);
            // usage
            // echo ascii('Jürgen Müller'); // outputs: Juergen Mueller
            // echo ascii('Καλημέρα κόσμε'); // outputs: Kalimera kosme
           }

        /**
         * @param string $str
         * @return string
         */
        public static function replaceStr($str){
            $str = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.
            $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str); // Removes special chars.
            return preg_replace('/-+/', '-', $str); // Replaces multiple hyphens with single one.
        }

        /**
         * @param string $str
         * @return string
         */
        public static function countStr($str){ 
            return strlen($str); 
        }

        /**
         * @param string $str
         * @return string
         */
        public static function countWords($str){ 
            return str_word_count($str);
        }

        /**
         * @param string $str
         * @return string
         */

        public static function countSentences($str){
            return preg_match_all('/[.!?]+/', $str, $matches);
        }
    }
?>