<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $table = 'studios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function movies()
    {
        return $this->hasMany(Movie::class, 'studioId');
    }
    public static function getAllStudios() {
        $studios = self::all();
        $payload = [];
        foreach ($studios as $studio) {
            $payload[] = [
                'id' => $studio->id,
                'studioName' => $studio->studioName,
                'studio_uri' => '/studios/' . $studio->id
            ];
        }
        return $payload;
    }
    public static function getStudio($id) {
        $studio = self::find($id);
        $payload = [
            'id' => $studio->id,
            'name' => $studio->name,
            'description' => $studio->description,
            'foundingDate' => $studio->foundingDate
        ];
        return $payload;
    }
    public static function createStudio($params) {
        $studio = new Studio();
        $studio->studioName = $params['studioName'];
        $studio->description = $params['description'];
        $studio->foundingDate = $params['foundingDate'];
        $studio->save();
        return $studio;
    }
    public static function updateStudio($entry, $params) {
        foreach ($params as $field => $value) {
            $entry->$field = $value;
        }
        $entry->save();
        return $entry;
    }
    public static function deleteStudio($id) {
        $studio = self::find($id);
        $studio->delete();
        return $studio;
    }

}
