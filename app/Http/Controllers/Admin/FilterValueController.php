<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterRequest;
use App\Http\Requests\Admin\FilterValueRequest;
use App\Models\Filter;
use App\Models\FilterValue;
use App\Services\Admin\FilterValueService;
use Illuminate\Http\Request;

class FilterValueController extends Controller
{
    protected $filterValueService;
    public function __construct(FilterValueService $filterValueService)
    {
        $this->filterValueService = $filterValueService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index($filterId)
    {
        $filter = Filter::findOrFail($filterId);
        $filtervalues = $this->filterValueService->getAll($filterId);
        return view('admin.filter_values.index', compact('filter', 'filtervalues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($filterId)
    {
       $filter = Filter::findOrFail($filterId);
       $title = 'Add Filter Value';
       return view('admin.filter_values.add_edit_filter_value', compact('filter', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FilterValueRequest $request, $filterId)
    {
        $filter = Filter::findOrFail($filterId);
        $this->filterValueService->store($request->validated(), $filterId);

        return redirect()
            ->route('filter-values.index', $filter->id)
            ->with('success', 'Filter Value added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($filterId, $id)
    {
       $filter = Filter::findOrFail($filterId);
       $filtervalue = $this->filterValueService->find($filterId,$id);
       $title = 'Edit Filter Value';
        return view('admin.filter_values.add_edit_filter_value', compact('filter', 'filtervalue', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FilterValueRequest $request, string $id,$filterId)
    {
        $filter = Filter::findOrFail($filterId);
        $this->filterValueService->update($request->validated(),$id, $filterId);
        return redirect()->route('admin.filter_values.index',$filter->id)->with('success_message', 'Filter Value updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($filterId, $valueId)
    {
        // Model থেকে ডাটা ফাইনড করো
        $filterValue = FilterValue::where('filter_id', $filterId)->where('id', $valueId)->firstOrFail();
        $filterValue->delete();

        return redirect()->route('filter-values.index', $filterId)->with('success_message', 'Filter Value deleted successfully');
    }

}
