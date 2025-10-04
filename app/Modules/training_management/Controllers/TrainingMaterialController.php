<?php

namespace App\Modules\training_management\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\training_management\Models\TrainingMaterial;
use App\Modules\training_management\Models\TrainingCatalog;
use App\Modules\competency_management\Models\Competency;
use App\Modules\training_management\Requests\StoreTrainingMaterialRequest;
use App\Modules\training_management\Requests\UpdateTrainingMaterialRequest;

class TrainingMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TrainingCatalog $catalog)
    {
        $trainingMaterials = TrainingMaterial::where('training_catalog_id', $catalog->id)
            ->with(['competency.framework'])
            ->orderBy('lesson_title')
            ->get();

        return view('training_management.materials.index', compact('catalog', 'trainingMaterials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TrainingCatalog $catalog)
    {
        $catalog->load('framework');
        
        // Get competencies from the competency management system
        $competencies = Competency::where('status', 'active')
            ->with('framework')
            ->orderBy('competency_name')
            ->get();
        
        return view('training_management.materialCRUD.create', compact('catalog', 'competencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrainingMaterialRequest $request, TrainingCatalog $catalog)
    {
        $trainingMaterial = TrainingMaterial::create([
            'lesson_title' => $request->lesson_title,
            'training_catalog_id' => $catalog->id,
            'competency_id' => $request->competency_id,
            'proficiency_level' => $request->proficiency_level,
            'lesson_content' => $request->lesson_content,
            'status' => 'draft',
            'is_active' => true,
        ]);

        return redirect()
            ->route('training.catalog.detail', $catalog)
            ->with('success', 'Training material created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingCatalog $catalog, TrainingMaterial $material)
    {
        $material->load(['competency.framework', 'trainingCatalog']);
        
        return view('training_management.materialCRUD.show', compact('catalog', 'material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingCatalog $catalog, TrainingMaterial $material)
    {
        $material->load(['competency.framework', 'trainingCatalog']);
        
        // Get competencies from the competency management system
        $competencies = Competency::where('status', 'active')
            ->with('framework')
            ->orderBy('competency_name')
            ->get();
        
        return view('training_management.materialCRUD.edit', compact('catalog', 'material', 'competencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainingMaterialRequest $request, TrainingCatalog $catalog, TrainingMaterial $material)
    {
        $material->update([
            'lesson_title' => $request->lesson_title,
            'competency_id' => $request->competency_id,
            'proficiency_level' => $request->proficiency_level,
            'lesson_content' => $request->lesson_content,
        ]);

        return redirect()
            ->route('training.catalog.detail', $catalog)
            ->with('success', 'Training material updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingCatalog $catalog, TrainingMaterial $material)
    {
        $material->delete();

        return redirect()
            ->route('training.catalog.detail', $catalog)
            ->with('success', 'Training material deleted successfully!');
    }

    /**
     * Publish a training material.
     */
    public function publish(TrainingCatalog $catalog, TrainingMaterial $material)
    {
        $material->update([
            'status' => 'published',
            'is_active' => true,
        ]);

        return redirect()
            ->route('training.catalog.detail', $catalog)
            ->with('success', 'Training material published successfully!');
    }

    /**
     * Save as draft.
     */
    public function saveDraft(StoreTrainingMaterialRequest $request, TrainingCatalog $catalog)
    {
        $trainingMaterial = TrainingMaterial::create([
            'lesson_title' => $request->lesson_title,
            'training_catalog_id' => $catalog->id,
            'competency_id' => $request->competency_id,
            'proficiency_level' => $request->proficiency_level,
            'lesson_content' => $request->lesson_content,
            'status' => 'draft',
            'is_active' => true,
        ]);

        return redirect()
            ->route('training.material.edit', ['catalog' => $catalog, 'material' => $trainingMaterial])
            ->with('success', 'Training material saved as draft!');
    }

    /**
     * Archive a training material.
     */
    public function archive(TrainingCatalog $catalog, TrainingMaterial $material)
    {
        $material->update([
            'status' => 'archived',
            'is_active' => false,
        ]);

        return redirect()
            ->route('training.catalog.detail', $catalog)
            ->with('success', 'Training material archived successfully!');
    }
}