<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DefaultModel;
use Auth;

class Model extends DefaultModel
{
    public static $enableSearchInFullTable = false;
    public function newQuery()
    {
        $query = parent::newQuery();
        if ($query->from == 'profile_masters') {
            return $query;
        }
        if (Auth::check() && !Auth::user()->isRaktamUser() &&  !static::$enableSearchInFullTable) {
            if (!Auth::user()->profile->is_individual) {
                if ($query->from == 'profile_masters') {
                    return $query;
                } elseif ($query->from == 'roles') {
                    // $query->where('roles.organization_id', '=', Auth::user()->organizationEntity->organization_id);
                } elseif ($query->from == 'org_entities') {
                    $orgEntity =  OrganizationEntity::searchInFullTable()->where('master_table_data_id', Auth::user()->id) //phpcs:ignore
                            ->leftJoin('org_structures', 'org_structures.id', 'org_entities.org_entity_type_id')
                            ->where('org_structures.slug', \DB::raw('"individual"'))
                            ->first();
                    $query->where('org_entities.organization_id', '=', $orgEntity->organization_id)
                            ->where('org_entities.branch_id', $orgEntity->branch_id)
                            ;
                } else {
                    $query->where('org_entities.organization_id', '=', Auth::user()->organizationEntity->organization_id)->where('org_entities.branch_id', Auth::user()->organizationEntity->branch_id);//phpcs:ignore
                }
            }
        }
        return $query;
    }
    public static function searchInFullTable()
    {
        static::$enableSearchInFullTable = true;
        return new static;
    }
}
