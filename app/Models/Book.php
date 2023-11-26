<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle($query, $title){
        return $query->where("title", "LIKE", "%$title%");
    }

    public function scopeWithReviewsCount($query, $from = null, $to = null){
        // reviews in relation method name
        return $query->withCount(['reviews' => fn($q) => $this->dateRangeFilter($q, $from, $to)]);
    }

    public function scopeWithAvgReviews($query, $from = null, $to = null){
        // reviews in relation method name
        return $query->withAvg(['reviews' => fn($q) => $this->dateRangeFilter($q, $from, $to)],'rating');
    }

    public function scopePopular($query, $from = null, $to = null){
        return $query
            ->withReviewsCount()
            ->orderBy('reviews_count','desc');
    }

    public function scopeHighestRated($query, $from = null, $to = null){
        return $query
            ->withAvgReviews()
            ->orderBy('reviews_avg_rating','desc');
    }

    public function scopeMinReviews($query,$minReviews){
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeFilter($query, $from = null, $to = null){
        if($from && !$to){
            $query->where('created_at','>=',$from);
        }elseif (!$from && $to){
            $query->where('created_at', '<=' , $to);
        }elseif ($from && $to){
            $query->whereBetween('created_at',[$from, $to]);
        }
    }

    public function scopePopularLastMonth($query){
        return $query
            ->popular(now()->subMonth(),now())
            ->highestRated(now()->subMonth(),now())
            ->minReviews(2);
    }

    public function scopePopularLast6Months($query){
        return $query
            ->popular(now()->subMonth(6),now())
            ->highestRated(now()->subMonth(),now())
            ->minReviews(5);
    }

    public function scopeHighestRatedMonth($query){
        return $query
            ->highestRated(now()->subMonth(),now())
            ->popular(now()->subMonth(),now())
            ->minReviews(2);
    }
    public function scopeHighestRated6Months($query){
        return $query
            ->highestRated(now()->subMonth(6),now())
            ->popular(now()->subMonth(),now())
            ->minReviews(5);
    }


}
