<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CitizenProfileDropdown extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    public function toArray($request)
    {
        // dd($this);
        $d = [];
        foreach($this as $item){
            array_push($d,[
                'id'=>$item->id,
                'fullname'=>$item->full_name_with_id,
                'address'=>$item->address
            ]);
        }
        return $d;
    }
}
