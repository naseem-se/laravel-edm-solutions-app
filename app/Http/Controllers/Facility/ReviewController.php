<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private function formatRatingData($reviews, $workerName = null)
    {
        if ($reviews->isEmpty()) {
            return [
                'worker_name' => $workerName,
                'average_rating' => 0,
                'total_reviews' => 0,
                'rating_breakdown' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
                'reviews' => [],
            ];
        }

        return [
            'worker_name' => $workerName,
            'average_rating' => round($reviews->avg('rating'), 1),
            'total_reviews' => $reviews->count(),
            'rating_breakdown' => [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ],
            'reviews' => $this->formatReviewsList($reviews),
        ];
    }

    private function formatReviewsList($reviews)
    {
        return $reviews->map(fn($review) => [
            'id' => $review->id,
            'facility_name' => $review->facility->full_name,
            'facility_id' => $review->facility->id,
            'worker_id' => $review->worker_id,
            'worker_name' => $review->worker->full_name,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'created_at' => $review->created_at->format('M d, Y'),
            'created_at_human' => $review->created_at->diffForHumans(),
        ])->values();
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $facility = auth()->user();
        $worker = User::findOrFail($request->worker_id);

        $existingReview = Review::where('worker_id', $worker->id)
            ->where('facility_id', $facility->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You already reviewed this worker',
            ], 400);
        }

        $review = Review::create([
            'worker_id' => $worker->id,
            'facility_id' => $facility->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => [
                'id' => $review->id,
                'worker_name' => $worker->full_name,
                'rating' => $review->rating,
                'comment' => $review->comment,
            ],
        ], 201);
    }

    public function updateReview(Request $request, $reviewId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $facility = auth()->user();
        $review = Review::findOrFail($reviewId);

        if ($review->facility_id !== $facility->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
            ],
        ], 200);
    }

    public function deleteReview($reviewId)
    {
        $facility = auth()->user();
        $review = Review::findOrFail($reviewId);

        if ($review->facility_id !== $facility->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ], 200);
    }

    public function getFacilityReviews()
    {
        $facility = auth()->user();

        $reviews = Review::where('facility_id', $facility->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_reviews_given' => $reviews->count(),
                'reviews' => $this->formatReviewsList($reviews),
            ],
        ], 200);
    }

    public function getMyReviews()
    {
        $worker = auth()->user();

        $reviews = Review::where('worker_id', $worker->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $this->formatRatingData($reviews, $worker->full_name);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function getMyReviewsDetailed()
    {
        $worker = auth()->user();

        $reviews = Review::where('worker_id', $worker->id)
            ->with('facility')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'total_reviews' => $reviews->total(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'reviews' => $this->formatReviewsList($reviews),
            ],
        ], 200);
    }

    public function getMyRatingSummary()
    {
        $worker = auth()->user();

        $reviews = Review::where('worker_id', $worker->id)->get();

        if ($reviews->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'average_rating' => 0,
                    'total_reviews' => 0,
                    'rating_breakdown' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
                ],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'average_rating' => round($reviews->avg('rating'), 1),
                'total_reviews' => $reviews->count(),
                'rating_breakdown' => [
                    5 => $reviews->where('rating', 5)->count(),
                    4 => $reviews->where('rating', 4)->count(),
                    3 => $reviews->where('rating', 3)->count(),
                    2 => $reviews->where('rating', 2)->count(),
                    1 => $reviews->where('rating', 1)->count(),
                ],
            ],
        ], 200);
    }

    public function getReviewsByFacility($facilityId)
    {
        $worker = auth()->user();

        $reviews = Review::where('worker_id', $worker->id)
            ->where('facility_id', $facilityId)
            ->get();

        if ($reviews->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No reviews from this facility',
            ], 404);
        }

        $review = $reviews->first();

        return response()->json([
            'success' => true,
            'data' => [
                'facility_name' => $review->facility->full_name,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('M d, Y'),
            ],
        ], 200);
    }

    public function getWorkerRating($workerId)
    {
        $worker = User::findOrFail($workerId);
        $reviews = Review::where('worker_id', $workerId)->get();

        $data = $this->formatRatingData($reviews, $worker->full_name);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function getWorkerReviewsCount($workerId)
    {
        $reviews = Review::where('worker_id', $workerId)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_reviews' => $reviews->count(),
                'average_rating' => $reviews->isEmpty() ? 0 : round($reviews->avg('rating'), 1),
            ],
        ], 200);
    }
}