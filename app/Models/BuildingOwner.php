<?php

namespace App\Models;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BuildingProfile;
class BuildingOwner extends Model
{
    use CrudTrait;

    protected $table = 'building_owners';

    protected $guarded = ['id'];


    public function BuildingProfiles(){
        return  $this->belongsToMany(BuildingProfile::class);
    }

}
