<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\Competency;
use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\competency_management\Requests\StoreCompetencyRequest;
use Illuminate\Http\Request;
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

        $competencies = $query->orderBy('id', 'asc')->get();

        return view('competency_management.competencies.index', compact('competencies'));
    }

    public function create()
    {
        $frameworks = CompetencyFramework::active()->get();
        return view('competency_management.CompetencyCRUD.create', compact('frameworks'));
    }

    public function store(StoreCompetencyRequest $request)
    {
        try {
            Competency::create($request->validated());

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
        return view('competency_management.competencies.show', compact('competency'));
    }

    public function edit(Competency $competency)
    {
        $frameworks = CompetencyFramework::active()->get();
        return view('competency_management.competencies.edit', compact('competency', 'frameworks'));
    }

    public function update(Request $request, Competency $competency)
    {
        // We'll add this later
    }

    public function destroy(Competency $competency)
    {
        try {
            $competency->delete();
            return redirect()
                ->route('competency.competencies')
                ->with('success', 'Competency deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting competency.');
        }
    }
}