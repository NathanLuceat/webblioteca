<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'since_id' => 'sometimes|integer|min:0',
            'limit' => 'sometimes|integer|min:1|max:100',
        ]);

        $limit = $validated['limit'] ?? 20;

        $query = Activity::with('actor')->orderBy('id');

        if (!empty($validated['since_id'])) {
            $query->where('id', '>', $validated['since_id']);
        }

        $activities = $query->limit($limit)->get();

        return ActivityResource::collection($activities)->additional([
            'meta' => [
                'count' => $activities->count(),
                'next_since_id' => $activities->last()?->id ?? ($validated['since_id'] ?? 0),
            ],
        ]);
    }
}