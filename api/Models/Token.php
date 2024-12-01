<?php

namespace Movies\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Token extends Model
{
    // Bearer token expires: seconds
    const EXPIRE = 3600;

    protected $table = 'tokens';
    protected $fillable = ['user', 'value'];

    // Disable automatic timestamp handling
    public $timestamps = false;

    public static function generateBearer($id)
    {
        $token = self::where('user', $id)->first();
        $now = new DateTime();
        $expire = time() - self::EXPIRE;

        if ($token) {
            $updated = new DateTime($token->updated_at);
            if ($expire > $updated->getTimestamp()) {
                $token->value = bin2hex(random_bytes(64));
                $token->updated_at = $now->format('Y-m-d H:i:s');
                $token->save();
            }
            return $token->value;
        }

        // Create a new token
        $token = new Token();
        $token->user = $id;
        $token->value = bin2hex(random_bytes(64));
        $token->created_at = $now->format('Y-m-d H:i:s');
        $token->updated_at = $now->format('Y-m-d H:i:s');
        $token->save();

        return $token->value;
    }

    public static function validateBearer($value)
    {
        $token = self::where('value', $value)->first();

        if (!$token) {
            return false;
        }

        $expire = time() - self::EXPIRE;
        $updated = new DateTime($token->updated_at);

        return ($expire < $updated->getTimestamp()) ? $token : false;
    }
}