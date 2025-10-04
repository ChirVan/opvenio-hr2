<?php

namespace App\Modules\competency_management\Controllers;

use App\Modules\competency_management\Models\GapAnalysis;
use App\Modules\competency_management\Requests\StoreGapAnalysisRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class GapAnalysisController extends Controller
{
   public function index()
    {
        $gapAnalyses = DB::connection('competency_management')
            ->table('gap_analyses')
            ->join('employees', 'gap_analyses.employee_id', '=', 'employees.id')
            ->join('competencies', 'gap_analyses.competency_id', '=', 'competencies.id')
            ->select(
                'gap_analyses.*',
                'employees.firstname as employee_firstname',
                'employees.lastname as employee_lastname',
                'competencies.competency_name'
            )
            ->get();

        return view('competency_management.gap_analysis', compact('gapAnalyses'));
    }

    public function create()
    {
        $employees = DB::connection('competency_management')->table('employees')->get();
        $roleMappings = \App\Modules\competency_management\Models\RoleMapping::with(['framework', 'competency'])->get();
        return view('competency_management.GapCRUD.create', compact('employees', 'roleMappings'));
    }

    public function store(StoreGapAnalysisRequest $request)
    {
        GapAnalysis::create($request->validated());
        return redirect()->route('competency.gapanalysis')->with('success', 'Gap analysis record created.');
    }

    public function show($id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        return view('competency_management.GapCRUD.show', compact('gapAnalysis'));
    }

    public function edit($id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        return view('competency_management.GapCRUD.edit', compact('gapAnalysis'));
    }

    public function update(StoreGapAnalysisRequest $request, $id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        $gapAnalysis->update($request->validated());
        return redirect()->route('competency.gapanalysis')->with('success', 'Gap analysis record updated.');
    }

    public function destroy($id)
    {
        $gapAnalysis = GapAnalysis::findOrFail($id);
        $gapAnalysis->delete();
        return redirect()->route('competency.gapanalysis')->with('success', 'Gap analysis record deleted.');
    }
}