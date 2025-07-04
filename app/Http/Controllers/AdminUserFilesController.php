<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserFilesController extends Controller
{
    public function index()
    {
        $this->authorize('admin'); // zakładamy, że masz gate/policy admin
        $users = User::with(['pliki', 'pliki.konkurs'])->get()->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'konkurs' => optional($u->pliki->first())->konkurs ? [ 'name' => $u->pliki->first()->konkurs->name ] : null,
                'pliki' => $u->pliki->map(fn($p) => [ 'id' => $p->id, 'original_name' => $p->original_name, 'path' => $p->path ])
            ];
        });
        return response()->json($users);
    }
} 