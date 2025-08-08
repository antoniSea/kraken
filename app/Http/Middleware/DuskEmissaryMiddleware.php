<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DuskEmissaryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Sprawdź czy zalogowany użytkownik ma nickname "DuskEmissary"
        if (Auth::user()->nickname !== 'DuskEmissary') {
            // Wyloguj użytkownika
            Auth::logout();
            
            // Wyczyść sesję
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Przekieruj do strony logowania z komunikatem przez URL
            return redirect()->route('login', ['error' => 'Dostęp tylko dla użytkownika DuskEmissary.']);
        }

        return $next($request);
    }
} 