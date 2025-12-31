<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanSelectionController extends Controller
{
    public function __invoke(Request $request, $planId)
    {
        if (Auth::check()) {
            return redirect()->route('payment.show', ['plan_id' => $planId]);
        }
        return redirect()->route('register', ['plan_id' => $planId]);
    }
}
