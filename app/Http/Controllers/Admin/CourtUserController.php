<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CourtUserController extends Controller
{
    /**
     * Display a listing of judges.
     */
    public function judges()
    {
        $judges = User::whereHas('role', function ($q) {
            $q->where('name', 'judge');
        })->paginate(15);

        return view('admin.court-users.judges.index', [
            'users' => $judges,
            'title' => 'إدارة القضاة',
        ]);
    }

    /**
     * Display a listing of students.
     */
    public function students()
    {
        $students = User::whereHas('role', function ($q) {
            $q->where('name', 'student');
        })->paginate(15);

        return view('admin.court-users.students.index', [
            'users' => $students,
            'title' => 'إدارة الطلاب',
        ]);
    }

    /**
     * Display a listing of witnesses.
     */
    public function witnesses()
    {
        $witnesses = User::whereHas('role', function ($q) {
            $q->where('name', 'witness');
        })->paginate(15);

        return view('admin.court-users.witnesses.index', [
            'users' => $witnesses,
            'title' => 'إدارة الشهود',
        ]);
    }
} 