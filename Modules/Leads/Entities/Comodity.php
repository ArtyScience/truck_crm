<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comodity extends Model
{
    use HasFactory;

    protected $table = 'comodities';

    protected $fillable = ['name'];

    public static function getLeadComodities(int $lead_id)
    {
        return Comodity::select('comodities.id', 'comodities.name')
            ->leftJoin('lead_comodities', 'lead_comodities.comodity_id', '=', 'comodities.id')
            ->where('lead_comodities.lead_id', '=', $lead_id)->get();
    }
}
