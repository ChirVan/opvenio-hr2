<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\Competency;
use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\competency_management\Requests\StoreCompetencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CompetencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Competency::with('framework');

        // Search functionality
        if ($request->filled('competency_search')) {
            $searchTerm = $request->competency_search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('competency_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('framework', function($q) use ($request) {
                $q->where('framework_name', $request->category);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        if ($request->sort_name === 'az') {
            $query->orderBy('competency_name', 'asc');
        } elseif ($request->sort_name === 'za') {
            $query->orderBy('competency_name', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $competencies = $query->get();

        return view('competency_management.frameworks', compact('competencies'));
    }

    public function create()
    {
        $frameworks = CompetencyFramework::active()->get();
        return view('competency_management.CompetencyCRUD.create', compact('frameworks'));
    }

    public function store(StoreCompetencyRequest $request)
    {
        try {
            // Generate next competency_id (CMP-001, CMP-002, ...)
            $last = \App\Modules\competency_management\Models\Competency::orderByDesc('id')->first();
            $nextNumber = $last && $last->competency_id ? ((int) preg_replace('/\D/', '', $last->competency_id)) + 1 : 1;
            $nextCompetencyId = 'CMP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $data = $request->validated();
            $data['competency_id'] = $nextCompetencyId;

            $competency = Competency::create($data);

            // Log activity (CREATE)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'activity' => 'Create',
                'details' => 'Created competency: ' . $competency->competency_name,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            // Redirect to frameworks page after successful creation
            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Competency created successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the competency.');
        }
}

    public function show(Competency $competency)
    {
        $competency->load('framework');
        return view('competency_management.CompetencyCRUD.view', compact('competency'));
    }

    public function edit(Competency $competency)
    {
        $frameworks = CompetencyFramework::active()->get();
        return view('competency_management.CompetencyCRUD.edit', compact('competency', 'frameworks'));
    }

    public function update(Request $request, Competency $competency)
    {
        try {
            $competency->update($request->all());

            // Log activity (UPDATE)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'activity' => 'Edit',
                'details' => 'Updated competency: ' . $competency->competency_name,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            return redirect()
                ->route('competency.competencies.index')
                ->with('success', 'Competency updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the competency.');
        }
    }

    public function destroy(Competency $competency)
    {
        try {
            $name = $competency->competency_name;
            $competency->delete();

            // Log activity (DELETE)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'activity' => 'Delete',
                'details' => 'Deleted competency: ' . $name,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            return redirect()
                ->route('competency.competencies.index')
                ->with('success', 'Competency deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting competency.');
        }
    }
}