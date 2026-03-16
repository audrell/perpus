<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorTestController extends Controller
{
    public function checkError(Request $request, int $code)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $allowedCodes = [400, 401, 403, 404, 405, 419, 422, 429, 500, 503];

        if (!in_array($code, $allowedCodes, true)) {
            abort(404);
        }

        abort($code);
    }
}
