<?php

namespace App\Services\Admin;

use App\Models\Filter;

class FilterService
{
  public function getAll()
  {
      return Filter::with('categories')->orderBy('id','desc')->get();
  }

  public function store(array $data)
  {
      $filter = Filter::create([
          'filter_name' => $data['filter_name'],
          'filter_column'=> $data['filter_column'],
          'sort' => $data['sort']??0,
          'status' => $data['status']??1,
      ]);

      $filter->categories()->sync($data['category_ids']);
      return $filter;
  }
  public function find($id)
  {
    return Filter::with('categories')->findOrFail($id);
  }
  public function update($id,array $data)
  {
      $filter = $this->find($id);
      $filter->update([
          'filter_name' => $data['filter_name'],
          'filter_column' => $data['filter_column'],
          'sort' => $data['sort']??0,
          'status' => $data['status']??1,
      ]);
      $filter->categories()->sync($data['category_ids']);
      return $filter;
  }
  public function destroy($id)
  {
      $filter = $this->find($id);
      $filter->categories()->detach();
      return $filter->delete();
  }
  public function updateFilterstatus($data)
  {
      $status = ($data['status']=='Active')?0:1;
      Filter::where('id',$data['filter_id'])->update(['status'=>$status]);
      return $status;
  }
}
