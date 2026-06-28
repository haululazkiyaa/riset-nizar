<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVitalSignRequest;
use App\Http\Resources\VitalSignResource;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VitalSignController extends Controller
{
    use AuthorizesRequests;

    public function index(Patient $patient): AnonymousResourceCollection
    {
        $this->authorize('view', $patient);

        return VitalSignResource::collection($patient->vitalSigns()->latest()->paginate(20));
    }

    public function store(StoreVitalSignRequest $request, Patient $patient): JsonResponse
    {
        $this->authorize('update', $patient);

        $vitalSign = $patient->vitalSigns()->create([
            ...$request->validated(),
            'recorded_by' => $request->user()->id,
        ]);

        return response()->json(new VitalSignResource($vitalSign), 201);
    }
}
