<?php

namespace Movies\Models;

use Illuminate\Database\Eloquent\Model;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Reviewer extends Model
{
 protected $table = 'reviewer';
 protected $primaryKey = 'id';

 public $timestamps = false;

    const JWT_KEY = 'my token';
    const JWT_EXPIRE = 600;

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewerId');
    }
    public static function AuthenticateReviewer($username,$password) {
        $user = Reviewer::where('username', $username)->first();
        if (!$user) {
            return false;
        }
        return password_verify($password, $user->password) ? $user : false;
    }
    public static function getAllReviewers() {
        $reviewers = Reviewer::all();
        $payload = [];
        foreach ($reviewers as $reviewer) {
            $payload[$reviewer->id] = [
                'name' => $reviewer->name,
                'id' => $reviewer->id,
            ];
        }
        return $payload;
    }
    public static function createReviewer($request) {
        $reviewer = new Reviewer();
        $reviewer->name = $request->getParsedBodyParam('name', '');
        $reviewer->username = $request->getParsedBodyParam('username', '');
        $reviewer->password = password_hash($request->getParsedBodyParam('password', ''), PASSWORD_DEFAULT);
        $reviewer->created_at = date('Y-m-d H:i:s');
        $reviewer->save();
        return [
            'status' => 'successful'
        ];
    }
    public static function getReviewer($id) {
        $reviewer = Reviewer::find($id);
        $payload[$reviewer->id] = [
            'name' => $reviewer->name,
            'id' => $reviewer->id,
        ];
        return $payload;
    }
    public static function updateReviewer($entry, $params) {
        foreach ($params as $field => $value) {
            $entry->$field = $value;
        }
        $entry->save();
        return $entry;
    }
    public static function deleteReviewer($id) {
        $reviewer = Reviewer::find($id);
        $reviewer->delete();
        return [
            'status' => 'successful'
        ];
    }
    public static function getReviews($id) {
        $reviewer = Reviewer::find($id);
        return $reviewer->reviews()->get()->toArray();
    }

    public static function generateJWT($id){
        //Data for payload
        $user = $user = self::findOrFail($id);
        if(!$user){
            return false;
        }

        $key = self::JWT_KEY;
        $expiration = time() + self::JWT_EXPIRE;
        $issuer = 'movie-api.com';

        $token = [
            'iss' => $issuer,
            'exp' => $expiration,
            'iat' => time(),
            'data' => [
                'uid' => $id,
                'name' => $user->username,
                'email' => $user->email
            ]
        ];

        //Generate and return token
        return JWT::encode(
            $token,
            $key,
            'HS256'
        );
    }

    public static function validateJWT($token){
        $decoded = JWT::decode($token, new Key(self::JWT_KEY, 'HS256'));

        return $decoded;
    }
    
}