<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\competency_management\Models\Competency; // <-- Added import
use App\Modules\competency_management\Requests\StoreCompetencyFrameworkRequest;
use App\Modules\competency_management\Requests\UpdateCompetencyFrameworkRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompetencyFrameworkController extends Controller
{
    public function index(Request $request)
    {
        $query = CompetencyFramework::query();

        // Search functionality for frameworks
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('framework_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $frameworks = $query->orderBy('id', 'asc')->get();

        // NEW: Get competencies for the competencies table
        $competenciesQuery = Competency::with('framework');
        
        // Search functionality for competencies
        if ($request->filled('competency_search')) {
            $searchTerm = $request->competency_search;
            $competenciesQuery->where(function($q) use ($searchTerm) {
                $q->where('competency_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        $competencies = $competenciesQuery->orderBy('id', 'asc')->get();

        return view('competency_management.frameworks', compact('frameworks', 'competencies'));
    }

    public function create()
    {
        // Use your existing view path
        return view('competency_management.FrameworkCRUD.create');
    }

    public function store(StoreCompetencyFrameworkRequest $request)
    {
        try {
            CompetencyFramework::create($request->validated());

            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Framework created successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the framework.');
        }
    }

    public function show(CompetencyFramework $framework)
    {
        return view('competency_management.FrameworkCRUD.show', compact('framework'));
    }

    public function edit(CompetencyFramework $framework)
    {
        return view('competency_management.FrameworkCRUD.edit', compact('framework'));
    }

    public function update(UpdateCompetencyFrameworkRequest $request, CompetencyFramework $framework)
    {
        try {
            $framework->update($request->validated());

            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Framework updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the framework.');
        }
    }

    public function destroy(CompetencyFramework $framework)
    {
        try {
            $framework->delete();
            return redirect()
                ->route('competency.frameworks')
                ->with('success', 'Framework deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting framework.');
        }
    }
}