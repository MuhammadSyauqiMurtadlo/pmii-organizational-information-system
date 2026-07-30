<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        // Livewire MemberIndex component yang handle filter & pagination
        return view('dashboard.members.index');
    }

    public function create()
    {
        return view('dashboard.members.create');
    }

    public function show(Member $member)
    {
        $this->authorize('view', $member);

        $member->load(['user', 'rayon.komisariat', 'kaderisasiRecords', 'activities']);

        return view('dashboard.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $this->authorize('update', $member);

        return view('dashboard.members.edit', compact('member'));
    }
}
