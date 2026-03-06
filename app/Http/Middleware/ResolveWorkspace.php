<?php

namespace App\Http\Middleware;

use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveWorkspace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspace = Workspace::where('slug', $request->route('workspace'))
            ->firstOrFail();

        $isMember = $workspace->members()
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isMember) {
            abort(403, 'You are not the member of this workspace');
        }

        session(['current_workspace_id' => $workspace->id]);

        view()->share('currentWorkspace', $workspace);

        return $next($request);
    }
}
