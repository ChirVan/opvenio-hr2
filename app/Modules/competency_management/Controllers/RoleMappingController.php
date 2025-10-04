<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\RoleMapping;
use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\competency_management\Models\Competency;
use App\Modules\competency_management\Requests\StoreRoleMappingRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleMappingController extends Controller
{
    public function index(Request $request)
    {
        $query = RoleMapping::with(['framework', 'competency']);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('role_name', 'like', "%{$searchTerm}%")
                  ->orWhereHas('framework', function($subQ) use ($searchTerm) {
                      $subQ->where('framework_name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('competency', function($subQ) use ($searchTerm) {
                      $subQ->where('competency_name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $roleMappings = $query->orderBy('id', 'asc')->get();

        return view('competency_management.rolemapping', compact('roleMappings'));
    }

    public function create()
    {
        $frameworks = CompetencyFramework::active()->get();
        $competencies = Competency::active()->get();
        return view('competency_management.RoleMappingCRUD.create', compact('frameworks', 'competencies'));
        
    }

    public function store(StoreRoleMappingRequest $request)
    {
        try {
            RoleMapping::create($request->validated());

            return redirect()
                ->route('competency.rolemapping')
                ->with('success', 'Role mapping created successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the role mapping.');
        }
    }

    public function show(RoleMapping $roleMapping)
    {
        $roleMapping->load(['framework', 'competency']);
        return view('competency_management.role_mappings.show', compact('roleMapping'));
    }

    public function edit(RoleMapping $roleMapping)
    {
        $frameworks = CompetencyFramework::active()->get();
        $competencies = Competency::active()->get();
        return view('competency_management.role_mappings.edit', compact('roleMapping', 'frameworks', 'competencies'));
    }

    public function update(Request $request, RoleMapping $roleMapping)
    {
        // We'll add this later
    }

    public function destroy(RoleMapping $roleMapping)
    {
        try {
            $roleMapping->delete();
            return redirect()
                ->route('competency.rolemapping')
                ->with('success', 'Role mapping deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting role mapping.');
        }
    }
}