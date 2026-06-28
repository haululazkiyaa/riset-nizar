<?php

namespace Database\Seeders;

use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\VitalSign;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'create patient',
            'create health record',
            'create vital sign',
            'view audit logs',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $doctorRole = Role::findOrCreate('doctor', 'web');
        $auditorRole = Role::findOrCreate('auditor', 'web');

        $permissionModels = Permission::query()->where('guard_name', 'web')->get()->keyBy('name');

        $adminRole->givePermissionTo($permissionModels->all());
        $doctorRole->givePermissionTo([
            $permissionModels->get('create patient'),
            $permissionModels->get('create health record'),
            $permissionModels->get('create vital sign'),
        ]);
        $auditorRole->givePermissionTo($permissionModels->get('view audit logs'));

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '+6281110000001',
        ]);
        $admin->assignRole($adminRole);

        $doctor = User::factory()->create([
            'name' => 'Dr. Nizar',
            'email' => 'doctor@example.com',
            'phone' => '+6281110000002',
        ]);
        $doctor->assignRole($doctorRole);

        $auditor = User::factory()->create([
            'name' => 'Audit User',
            'email' => 'auditor@example.com',
            'phone' => '+6281110000003',
        ]);
        $auditor->assignRole($auditorRole);

        $patients = collect([
            [
                'medical_record_number' => 'MRN-2026-0001',
                'name' => 'Alya Putri',
                'date_of_birth' => '1994-06-12',
                'gender' => 'female',
                'address' => 'Jakarta Selatan',
                'phone' => '+6281211111111',
            ],
            [
                'medical_record_number' => 'MRN-2026-0002',
                'name' => 'Bima Pratama',
                'date_of_birth' => '1988-11-05',
                'gender' => 'male',
                'address' => 'Bandung',
                'phone' => '+6281211111112',
            ],
            [
                'medical_record_number' => 'MRN-2026-0003',
                'name' => 'Citra Lestari',
                'date_of_birth' => '2001-02-21',
                'gender' => 'other',
                'address' => 'Yogyakarta',
                'phone' => '+6281211111113',
            ],
        ])->map(function (array $data) use ($doctor) {
            return Patient::create([
                ...$data,
                'created_by' => $doctor->id,
            ]);
        });

        $patients->each(function (Patient $patient) use ($doctor, $admin): void {
            HealthRecord::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'diagnosis' => 'Routine check-up',
                'notes' => 'Seeded health record for API testing.',
                'metadata' => [
                    'source' => 'seeder',
                    'severity' => 'low',
                ],
            ]);

            VitalSign::create([
                'patient_id' => $patient->id,
                'recorded_by' => $admin->id,
                'systolic' => 120,
                'diastolic' => 80,
                'pulse' => 72,
                'temperature' => 36.7,
                'recorded_at' => now()->subDay(),
            ]);
        });
    }
}
