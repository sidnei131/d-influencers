<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Influencer;
use App\Http\Requests\CampaignRequest;
use App\Http\Resources\CampaignResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CampaignController extends Controller
{
    public function index()
    {
        try {
            $campaigns = Campaign::with('influencers')->get();

            return CampaignResource::collection($campaigns);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch campaigns'], 500);
        }
    }

    public function show($id)
    {
        try {
            $campaign = Campaign::with('influencers')->findOrFail($id);

            return new CampaignResource($campaign);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Campaign not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch campaign.'], 500);
        }
    }

    public function store(CampaignRequest $request)
    {
        try {
            $campaign = Campaign::create($request->validated());

            if ($request->has('influencers')) {
                $campaign->influencers()->attach($request->input('influencers'));
            }

            $campaign->load('influencers');
     
            return new CampaignResource($campaign);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create campaign.'], 500);
        }
    }

    public function update(CampaignRequest $request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->update($request->validated());

            if ($request->has('influencers')) {
                $influencersIds = $request->input('influencers', []);
                $validIds = Influencer::whereIn('id', $influencersIds)->pluck('id')->toArray();
                $invalidIds = array_diff($influencersIds, $validIds);

                if (!empty($invalidIds)) {
                    return response()->json(['error' => 'Invalid influencer ID.'], 400);
                }

                $campaign->influencers()->sync($influencersIds);
            }

            $campaign->load('influencers');

            return new CampaignResource($campaign);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Campaign not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update campaign.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Campaign deleted successfully!',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Campaign not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete campaign.'], 500);
        }
    }
}
