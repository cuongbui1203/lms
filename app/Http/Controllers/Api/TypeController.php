<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index($for)
    {
        if (!in_array($for, ['work-plates', 'vehicles']))
            return $this->sendError('invalid', '');
        $fo = 1;
        if ($for === 'work-plates')
            $fo = 1;
        else {
            $fo = 2;
        }
        $types = Type::where('for', '=', $fo)->get(['id', 'name']);

        return $this->sendSuccess($types, 'Get all Type success');
    }
}
