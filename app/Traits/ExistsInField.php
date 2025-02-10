<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ExistsInField
{
    
    public function existsInField(Request $request, string $field): bool
    {
        if ($request->has('fields')) {
            $fields = explode(',', $request->get('fields'));
            return in_array($field, $fields);
        }

        return false;
    }
}
