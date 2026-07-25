<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $review = Review::with(['user', 'warung'])
            ->latest('created_at')
            ->get();

        return view('admin.review.index', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.review.index')
            ->with('success', 'Review berhasil dihapus.');
    }
}
