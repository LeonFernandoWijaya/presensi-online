<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $session = Session::where('user_id', $user->id)->first();

            if ($session && $session->session_id != session()->getId()) {
                Auth::logout();
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
?>