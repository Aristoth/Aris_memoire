<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'doc_desc' => 'required|string|max:255',
            'type' => 'required|string|max:50',
        ]);

        $hash = Str::uuid();  // Générer un hash unique

        $document = Document::create([
            'title' => $request->input('title'),
            'doc_desc' => $request->input('doc_desc'),
            'type' => $request->input('type'),
            'hash' => $hash,
        ]);

        return response()->json([
            'message' => 'Document enregistré avec succès.',
            'document' => $document,
        ]);
    }


    public function generateQrCode($id)
    {
        $document = Document::findOrFail($id);
        $qrCode = QrCode::format('png')->size(300)->generate($document->hash);

        $path = 'qrcodes/' . $document->hash . '.png';
        Storage::disk('public')->put($path, $qrCode);

        return response()->json([
            'message' => 'QR Code généré avec succès.',
            'qr_code_path' => Storage::url($path),
        ]);
    }


    public function index()
    {
        $documents = Document::all();
        return response()->json($documents);
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return response()->json($document);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        Storage::disk('public')->delete('qrcodes/' . $document->hash . '.png');
        $document->delete();

        return response()->json(['message' => 'Document supprimé avec succès.']);
    }

    public function downloadQrCode($id)
    {
        $document = Document::findOrFail($id);
        $path = 'qrcodes/' . $document->hash . '.png';
        return response()->download(storage_path('app/public/' . $path));
    }

}

