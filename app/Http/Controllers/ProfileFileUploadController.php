<?php
namespace App\Http\Controllers;

use App\Models\Plik;
use App\Models\Konkurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlikiUploaded;
use Illuminate\Support\Facades\Log;

class ProfileFileUploadController extends Controller
{
    public function store(Request $request)
    {
        Log::info('UPLOAD: request->all()', $request->all());
        Log::info('UPLOAD: konkurs_id', ['konkurs_id' => $request->input('konkurs_id')]);
        $request->validate([
            'files.*' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,mov',
            'konkurs_id' => 'nullable|exists:konkurs,id',
        ]);
        $user = Auth::user();
        $konkurs = $request->input('konkurs_id')
            ? Konkurs::find($request->input('konkurs_id'))
            : Konkurs::orderByDesc('id')->first();
        Log::info('UPLOAD: konkurs found', ['konkurs' => $konkurs]);
        if (!$konkurs) {
            Log::error('UPLOAD: Brak aktywnego konkursu');
            return response()->json(['message' => 'Brak aktywnego konkursu.'], 422);
        }
        if ($konkurs->is_blocked) {
            Log::error('UPLOAD: Konkurs zablokowany');
            return response()->json(['message' => 'Konkurs jest zablokowany.'], 422);
        }
        $uploaded = [];
        foreach ($request->file('files', []) as $file) {
            $path = $file->store('uploads', 'public');
            $plik = Plik::create([
                'user_id' => $user->id,
                'konkurs_id' => $konkurs->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
            $uploaded[] = $plik;
        }
        Mail::to($user->email)->send(new PlikiUploaded($user, $uploaded));
        return response()->json(['success' => true, 'files' => $uploaded]);
    }

    public function konkursy() {
        return response()->json(\App\Models\Konkurs::select('id','name','is_blocked')->get());
    }
} 