<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use App\Http\Requests\InfluencerRequest;
use App\Http\Resources\InfluencerResource;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class InfluencerController extends Controller
{
    public function index()
    {
        try {
            $influencers = Influencer::with('campaigns')->get();

            return InfluencerResource::collection($influencers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch influencers'], 500);
        }
    }

    public function show($id)
    {
        try {
            $influencer = Influencer::with('campaigns')->findOrFail($id);

            return new InfluencerResource($influencer);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Influencer not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch influencer.'], 500);
        }
    }

    public function store(InfluencerRequest $request)
    {
        try {
            $influencer = Influencer::create($request->validated());

            if ($request->has('campaigns')) {
                $influencer->campaigns()->attach($request->input('campaigns'));
            }

            $influencer->load('campaigns');
     
            return new InfluencerResource($influencer);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create influencer.'], 500);
        }
    }

    public function update(InfluencerRequest $request, $id)
    {
        try {
            $influencer = Influencer::findOrFail($id);
            $influencer->update($request->validated());

            if ($request->has('campaigns')) {
                $campaignsIds = $request->input('campaigns', []);
                $validIds = Campaign::whereIn('id', $campaignsIds)->pluck('id')->toArray();
                $invalidIds = array_diff($campaignsIds, $validIds);

                if (!empty($invalidIds)) {
                    return response()->json(['error' => 'Invalid campaign ID.'], 400);
                }

                $influencer->campaigns()->sync($campaignsIds);
            }

            $influencer->load('campaigns');

            return new InfluencerResource($influencer);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Influencer not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update influencer.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $influencer = Influencer::findOrFail($id);
            $influencer->ig_user = $influencer->ig_user . '--deleted-' . now()->timestamp;
            $influencer->saveQuietly();
            $influencer->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Influencer deleted successfully!',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Influencer not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete influencer.'], 500);
        }
    }
}
