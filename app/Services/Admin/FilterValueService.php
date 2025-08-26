<?php

namespace App\Services\Admin;

use App\Models\FilterValue;

class FilterValueService
{
  public function getAll($filterId)
  {
      return FilterValue::where('filter_id',$filterId)->orderBy('sort','asc')->get();
  }
  public function store(array $data,$filterId)
  {
      return FilterValue::create([
          'filter_id' => $filterId,
          'value' => $data['value'],
          'sort' => $data['sort']?? 0,
          'status' => $data['status']?? 1,
      ]);
  }
  public function find($filterId,$id)
  {
      return FilterValue::where('filter_id',$filterId)->findOrFail($id);
  }
  public function update($filterId,array $data,$id)
  {
      $filterValue= $this->find($filterId,$id);
      $filterValue->update([
          'value' => $data['value'],
          'sort' => $data['sort']?? 0,
          'status' => $data['status']?? 1,
      ]);
      return $filterValue;
  }
  public function delete($filterId,$id)
  {
      $filterValue= $this->find($filterId,$id);
      return $filterValue->delete();
  }
}
