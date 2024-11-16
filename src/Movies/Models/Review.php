<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['reviewerId', 'review', 'movieId', 'created_at', 'rating'];


    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieId');
    }
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewerId');
    }
    public function getAllReviews() {
        $reviews = Review::all();
        $payload = [];
        foreach ($reviews as $review) {
            $payload[$review->id] = [
                'review' => $review->review,
                'movieId' => $review->movieId,
                'created_at' => $review->created_at,
                'reviewerId' => $review->reviewerId,
                'rating' => $review->rating,
            ];
        }
        return $payload;
    }
    public function getReview($id) {
        $review = Review::find($id);
        $payload[$review->id] = [
            'review' => $review->review,
            'movieId' => $review->movieId,
            'created_at' => $review->created_at,
            'reviewerId' => $review->reviewerId,
            'rating' => $review->rating,
        ];
        return $payload;
    }
    public function createReview($request) {
        $review = new Review();
        $review->reviewerId = $request->getParsedBodyParam('reviewerId', '');
        $review->review = $request->getParsedBodyParam('review', '');
        $review->movieId = $request->getParsedBodyParam('movieId', '');
        $review->created_at = date('Y-m-d H:i:s');
        $review->rating = $request->getParsedBodyParam('rating', '');
        $review->save();
        return [
            'status' => 'successful'
        ];
    }
    public function updateReview($id, $params) {
        $review = Review::find($id);
        foreach ($params as $field => $value) {
            $review->$field = $value;
        }
        $review->save();
        return [
            'status' => 'successful'
        ];
    }
    public function deleteReview($id) {
        $review = Review::find($id);
        $review->delete();
        return [
            'status' => 'successful'
        ];
    }

}