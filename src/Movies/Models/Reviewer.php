<?php

namespace Movies\Models;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
 protected $table = 'reviewer';
 protected $primaryKey = 'id';

 public $timestamps = false;

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewerId');
    }
    public function getAllReviewers() {
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
    public function createReviewer($request) {
        $reviewer = new Reviewer();
        $reviewer->name = $request->getParsedBodyParam('name', '');
        $reviewer->created_at = date('Y-m-d H:i:s');
        $reviewer->save();
        return [
            'status' => 'successful'
        ];
    }
    public function getReviewer($id) {
        $reviewer = Reviewer::find($id);
        $payload[$reviewer->id] = [
            'name' => $reviewer->name,
            'id' => $reviewer->id,
        ];
        return $payload;
    }
    public function updateReviewer($entry, $params) {
        foreach ($params as $field => $value) {
            $entry->$field = $value;
        }
        $entry->save();
        return $entry;
    }
    public function deleteReviewer($id) {
        $reviewer = Reviewer::find($id);
        $reviewer->delete();
        return [
            'status' => 'successful'
        ];
    }
}