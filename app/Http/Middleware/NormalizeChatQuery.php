<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NormalizeChatQuery
{
    /**
     * Normalize user language before passing to controller.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('prompt')) {
            $prompt = strtolower($request->input('prompt'));

            // Common parent/student term normalization
            $replacements = [
                'my child'   => 'student',
                'kid'        => 'student',
                'son'        => 'student',
                'daughter'   => 'student',
                'children'   => 'students',
                'pupil'      => 'student',
                'he '        => 'the student ',
                'she '       => 'the student ',
                'they '      => 'the student ',
                'his '       => 'the student’s ',
                'her '       => 'the student’s ',
            ];

            $normalized = str_ireplace(array_keys($replacements), array_values($replacements), $prompt);
            $request->merge(['prompt' => trim($normalized)]);
        }

        return $next($request);
    }
}
