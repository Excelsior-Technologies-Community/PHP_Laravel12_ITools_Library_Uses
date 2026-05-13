<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Tool extends Model
{
    protected $table = 'tools';

    protected $fillable = [
        'name',
        'category',
        'website',
        'description'
    ];

    protected static function booted()
    {
        static::updated(function ($tool) {
            $changes = [];
            
            foreach ($tool->getDirty() as $key => $newValue) {
                if ($key === 'updated_at') continue; 
                
                $changes[$key] = [
                    'old' => $tool->getOriginal($key),
                    'new' => $newValue,
                ];
            }

            if (!empty($changes)) {
                $tableName = config('ltools.table_name_changelog_items', 'changelog_items');
                
                DB::table($tableName)->insert([
                    'uuid'       => (string) Str::uuid(),
                    'model'      => self::class,
                    'model_id'   => $tool->id,
                    'changes'    => json_encode($changes),
                    'created_at' => now(),
                ]);
            }
        });
    }
}