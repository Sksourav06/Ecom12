<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterRequest;
use App\Models\Category;
use App\Models\Filter;
use App\Services\Admin\FilterService;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    protected $filterService;
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $filters = $this->filterService->getAll();
       $title ="Filters";
       return view('admin.filters.index',compact('filters','title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::with('subcategories')
            ->whereNull('parent_id',null)
            ->get();
        $title = "Add Filter";
        return view('admin.filters.add_edit_filter',compact('category','title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FilterRequest $request)
    {
        $this->filterService->stor($request->validated());
        return redirect()->route('filters.index')->with('success','Filter created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $filter = Filter::with('categories')->findOrFail($id);
        $category = Category::with('subcategories')
            ->whereNull('parent_id',null)
            ->where('status',1)
            ->get();

        $selectedCategories = $filter->categories->pluck('id')->toArray();

        return view('admin.filters.add_edit_filter',[
            'filter' => $filter,
            'categories' => $category,
            'title' => "Edit Filter",
            'selectedCategories' => $selectedCategories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->filterService->update($id,$request->validated());
        return redirect()->route('filters.index')->with('success','Filter updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->filterService->delete($id);
        return redirect()->route('filters.index')->with('success','Filter deleted successfully');
    }

    public function updateFilterStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $status = $this->filterService->updateStatus($data);
            return response()->json(['status' => $status,'filter_id'=>$data['id']]);
        }
    }
}
