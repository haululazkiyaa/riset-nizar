<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $user = request()->user();
        $page = (int) request('page', 1);

        $patients = Cache::remember("patients:user:{$user->id}:page:{$page}", 30, function () use ($user) {
            return Patient::query()
                ->select(['id', 'created_by', 'medical_record_number', 'name', 'date_of_birth', 'gender', 'created_at'])
                ->where('created_by', $user->id)
                ->latest()
                ->paginate(20);
        });

        return PatientResource::collection($patients);
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = DB::transaction(function () use ($request) {
            return Patient::create([
                ...$request->validated(),
                'created_by' => $request->user()->id,
            ]);
        });

        Cache::flush();

        return response()->json(new PatientResource($patient), 201);
    }

    public function show(Patient $patient): PatientResource
    {
        $this->authorize('view', $patient);

        return Cache::remember("patient:{$patient->id}", 60, fn() => new PatientResource($patient));
    }

    public function update(StorePatientRequest $request, Patient $patient): PatientResource
    {
        $this->authorize('update', $patient);

        $patient->update($request->validated());

        Cache::flush();

        return new PatientResource($patient->refresh());
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $this->authorize('delete', $patient);

        $patient->delete();

        Cache::flush();

        return response()->json([], 204);
    }
}
