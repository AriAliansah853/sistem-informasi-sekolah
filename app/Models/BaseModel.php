<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Get the value of the model's route key.
     *
     * This encodes the numeric primary key into a UUID-like string.
     */
    public function getRouteKey()
    {
        return static::encodeRouteKey($this->getKey());
    }

    /**
     * Resolve the route binding value into a model instance.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $key = static::decodeRouteKey($value);

        if ($field === null) {
            $field = $this->getRouteKeyName();
        }

        return $this->where($field, $key)->firstOrFail();
    }

    /**
     * Encode a numeric ID into a UUID formatted string.
     */
    public static function encodeRouteKey($id)
    {
        if (!is_numeric($id)) {
            return $id;
        }

        $id = (string) ((int) $id);

        $key = static::getEncryptionKeyBytes();

        $cipher = openssl_encrypt($id, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        if ($cipher === false) {
            return $id;
        }

        $hex = strtolower(bin2hex($cipher));

        return sprintf('%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }

    /**
     * Decode a UUID formatted route key back into the numeric ID.
     */
    public static function decodeRouteKey($value)
    {
        $clean = str_replace('-', '', $value);

        if (!preg_match('/^[0-9a-f0-9]{32}$/i', $clean)) {
            return is_numeric($value) ? (int) $value : $value;
        }

        $bin = @hex2bin($clean);

        if ($bin === false) {
            return is_numeric($value) ? (int) $value : $value;
        }

        $key = static::getEncryptionKeyBytes();

        $decrypted = @openssl_decrypt($bin, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        if ($decrypted === false) {
            return is_numeric($value) ? (int) $value : $value;
        }

        return is_numeric($decrypted) ? (int) $decrypted : $decrypted;
    }

    /**
     * Get a 16-byte encryption key derived from the application key.
     *
     * - If `config('app.key')` is the base64 form (starts with "base64:"), decode it.
     * - Otherwise, derive 16 bytes via MD5.
     */
    protected static function getEncryptionKeyBytes()
    {
        $appKey = config('app.key');

        if (empty($appKey)) {
            $appKey = env('APP_KEY');
        }

        if (!is_string($appKey)) {
            return substr(hash('sha256', 'fallback_key', true), 0, 16);
        }

        if (strpos($appKey, 'base64:') === 0) {
            $decoded = base64_decode(substr($appKey, 7));

            if ($decoded !== false && strlen($decoded) >= 16) {
                return substr($decoded, 0, 16);
            }
        }

        return substr(md5($appKey, true), 0, 16);
    }
}
