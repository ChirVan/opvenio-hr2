<?php

namespace App\Modules\training_management\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\training_management\Models\TrainingCatalog;
use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\training_management\Requests\StoreTrainingCatalogRequest;
use App\Modules\training_management\Requests\UpdateTrainingCatalogRequest;

class TrainingCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $framework = $request->get('framework');
        
        if ($framework) {
            // Show framework-specific training materials
            return view('training_management.framework', compact('framework'));
        }
        
        // Get all training catalogs with their related frameworks
        $trainingCatalogs = TrainingCatalog::with('framework')
            ->orderBy('title')
            ->get();
        
        // Show main catalog with training catalog entries
        return view('training_management.catalog', compact('trainingCatalogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $frameworks = CompetencyFramework::where('status', 'active')
            ->orderBy('framework_name')
            ->get();
            
        return view('training_management.catalogCRUD.create', compact('frameworks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrainingCatalogRequest $request)
    {
        // Find the framework by name to get the ID
        $framework = CompetencyFramework::where('framework_name', $request->title)->first();
        
        $trainingCatalog = TrainingCatalog::create([
            'title' => $request->title,
            'label' => $request->label,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'framework_id' => $framework ? $framework->id : null,
        ]);

        return redirect()
            ->route('training.catalog.index')
            ->with('success', 'Training catalog entry created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingCatalog $catalog)
    {
        $catalog->load('framework');
        return view('training_management.catalogCRUD.show', ['trainingCatalog' => $catalog]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingCatalog $catalog)
    {
        $frameworks = CompetencyFramework::where('status', 'active')
            ->orderBy('framework_name')
            ->get();
            
        return view('training_management.catalogCRUD.edit', ['trainingCatalog' => $catalog, 'frameworks' => $frameworks]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainingCatalogRequest $request, TrainingCatalog $catalog)
    {
        // Find the framework by name to get the ID
        $framework = CompetencyFramework::where('framework_name', $request->title)->first();
        
        $catalog->update([
            'title' => $request->title,
            'label' => $request->label,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'framework_id' => $framework ? $framework->id : null,
        ]);

        return redirect()
            ->route('training.catalog.index')
            ->with('success', 'Training catalog entry updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingCatalog $catalog)
    {
        $catalog->delete();

        return redirect()
            ->route('training.catalog.index')
            ->with('success', 'Training catalog entry deleted successfully!');
    }

    /**
     * Display the detailed view of a training catalog with materials.
     */
    public function detail(TrainingCatalog $catalog)
    {
        $catalog->load('framework');
        
        // Load training materials with relationships
        $trainingMaterials = $catalog->trainingMaterials()
            ->with(['competency.framework'])
            ->orderBy('lesson_title')
            ->get();
        
        return view('training_management.catalog_detail', compact('catalog', 'trainingMaterials'));
    }

    /**
     * Show the form for creating a new training material.
     */
    public function createMaterial(TrainingCatalog $catalog)
    {
        $catalog->load('framework');
        
        // Get competencies from the competency management system
        $competencies = \App\Modules\competency_management\Models\Competency::where('status', 'active')
            ->with('framework')
            ->orderBy('competency_name')
            ->get();
        
        return view('training_management.materialCRUD.create', compact('catalog', 'competencies'));
    }
}
