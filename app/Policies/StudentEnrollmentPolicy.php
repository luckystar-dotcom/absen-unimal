<?php

namespace App\Policies;

use App\Models\StudentEnrollment;
use App\Models\User;

class StudentEnrollmentPolicy
{
    /**
     * Hanya Admin yang boleh melihat daftar KRS.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya Admin yang boleh melihat detail KRS.
     */
    public function view(User $user, StudentEnrollment $enrollment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya Admin yang boleh membuat KRS baru.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya Admin yang boleh mengubah KRS.
     */
    public function update(User $user, StudentEnrollment $enrollment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya Admin yang boleh menghapus KRS.
     */
    public function delete(User $user, StudentEnrollment $enrollment): bool
    {
        return $user->role === 'admin';
    }
}
