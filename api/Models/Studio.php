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
        return self::find($id);
    }

    public static function createStudio($params) {
        $studio = new Studio();
        $studio->name = $params['name'];
        $studio->description = $params['description'];
        $studio->foundingDate = $params['foundingDate'];
        $studio->save();
        return $studio;
    }
    public static function updateStudio($entry, $params) {
        foreach ($params as $field => $value) {
            $entry->$field = $value;
        }
        try {
            $entry->save();
        } catch (\Exception $e) {
            error_log('Save failed: ' . $e->getMessage());
        }
        return $entry;
    }
    public static function deleteStudio($studio)
    {
        error_log('Received Studio for deletion: ' . print_r($studio, true));

        if (!$studio instanceof self) {
            $studio = self::find($studio->id);
        }

        if (!$studio) {
            error_log('Studio not found during deletion.');
            return false;
        }

        try {
            $studio->delete();
            error_log('Studio deleted: ' . $studio->id);
            return true;
        } catch (Exception $e) {
            error_log('Error during deletion: ' . $e->getMessage());
            return false;
        }
    }


}
