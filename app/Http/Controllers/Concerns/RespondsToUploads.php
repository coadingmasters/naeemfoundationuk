<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Lets the video/image upload screens answer both a normal form post and the
 * XHR the upload-progress bar uses.
 *
 * The progress bar has to use XHR to get upload progress events, and XHR
 * follows redirects transparently — which silently *consumes* the flashed
 * success/error messages before the browser ever renders them. So for an XHR
 * request we flash the message (for the page we're about to send them to) and
 * hand back the target URL as JSON instead of redirecting. Laravel already
 * returns 422 JSON for validation failures on XHR, so those surface properly
 * too rather than vanishing into a discarded redirect.
 */
trait RespondsToUploads
{
    protected function uploadRedirect(Request $request, string $route, string $message): RedirectResponse|JsonResponse
    {
        if ($request->ajax()) {
            $request->session()->flash('success', $message);

            return response()->json(['redirect' => route($route)]);
        }

        return redirect()->route($route)->with('success', $message);
    }
}
