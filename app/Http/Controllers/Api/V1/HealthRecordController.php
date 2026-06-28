<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHealthRecordRequest;
use App\Http\Resources\HealthRecordResource;
use App\Models\HealthRecord;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HealthRecordController extends Controller
{
    use AuthorizesRequests;

    public function index(Patient $patient): AnonymousResourceCollection
    {
        $this->authorize('view', $patient);

        return HealthRecordResource::collection($patient->healthRecords()->latest()->paginate(20));
    }

    public function store(StoreHealthRecordRequest $request, Patient $patient): JsonResponse
    {
        $this->authorize('create', $patient);

        $healthRecord = $patient->healthRecords()->create([
            ...$request->validated(),
            'doctor_id' => $request->user()->id,
        ]);

        return response()->json(new HealthRecordResource($healthRecord), 201);
    }
}
