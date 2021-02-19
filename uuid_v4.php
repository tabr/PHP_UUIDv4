//https://habr.com/ru/company/mailru/blog/522094/
/*
 * 2021 tabr - feel free to use, distribute and contribute =)
*/
class UUIDv4
    {
    public const KEY_LENGTH_BYTES = 16;
    public const UUID_VERSION_BYTE = 7;
    public const UUID_VERSION_CLEANUP_MASK = 0x0F;
    public const UUID_VERSION_SET_MASK = 0x40;
    public const UUID_VARIANT_BYTE = 9;
    public const UUID_VARIANT_CLEANUP_MASK = 0x3F;
    public const UUID_VARIANT_SET_MASK = 0x80;
    private $uuid_str = '';
    private function bin2dec($dec) : String //because built-in function bindec can return float
        {
        return hexdec(bin2hex($dec));
        }
    private function dec2bin($bin) : String
        {
        return hex2bin(dechex($bin));
        }
    public function generate()
        {
        $uuid = random_bytes (self::KEY_LENGTH_BYTES);
        $this->uuid_str = '';
        //setting version
        $version = $this->bin2dec($uuid[self::UUID_VERSION_BYTE]);
        $version &= self::UUID_VERSION_CLEANUP_MASK;
        $version |= self::UUID_VERSION_SET_MASK;
        $uuid[self::UUID_VERSION_BYTE] = $this->dec2bin($version);

        //setting variant
        $variant = $this->bin2dec($uuid[self::UUID_VARIANT_BYTE]);
        $variant &= self::UUID_VARIANT_CLEANUP_MASK;
        $variant |= self::UUID_VARIANT_SET_MASK;
        $uuid[self::UUID_VARIANT_BYTE] = $this->dec2bin($variant);
        //8-4-4-4-12    chars
        //8-12-16-20-32 bytes
        //4-6-8-10-16   nibble
        $delimitter_position = array(4,6,8,10,16);//16 is unused
        for ($i=0;$i<self::KEY_LENGTH_BYTES;$i++)
            {
            if (in_array($i,$delimitter_position))
                {
                $this->uuid_str.='-';
                }
            $this->uuid_str .= bin2hex($uuid[$i]);
            }
        return $uuid_str;
        }
    public function __construct($new_uuid = '')
        {
        if (!empty($new_uuid))
            {
            if (is_string($new_uuid))
                {
                //TODO: validate?
                $this->uuid_str = $new_uuid;
                }
            elseif ($new_uuid instanceof UUIDv4)
                {
                $this->uuid_str = $new_uuid->get();
                }
            else
                {
                throw new Exception('Unknown type');
                }
            }
        else
            {
            $this->generate();
            }
        }
    public function get() : String
        {
        return $this->uuid_str;
        }
    public function __toString() : String
        {
        return $this->get();
        }
    public function equals(UUIDv4 $Uuid_to_compare)
        {
        return $this->get() === $Uuid_to_compare->get();
        }
    }
//$Uuid1 = new UUIDv4('11');
//$Uuid2 = new UUIDv4($Uuid1);
//echo $Uuid1->get();
//echo '[',($Uuid1 == $Uuid2),']';
