<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return response()->json($documents);
    }


    public function store(StorePostRequest $request)
    {
        $hash = Str::uuid();  // Générer un hash unique

        $document = Document::create([
            'title' => $request->input('title'),
            'description' => $request->input('doc_desc'),
            'type' => $request->input('type'),
            'hash' => $hash,
        ]);

        return response()->json([
            'message' => 'Document enregistré avec succès.',
            'document' => $document,
        ]);
    }

    public function update(UpdatePostRequest $request)
    {
        $hash = Str::uuid();  // Générer un hash unique

        $document = Document::updated([
            'title' => $request->input('title'),
            'description' => $request->input('doc_desc'),
            'type' => $request->input('type'),
            'hash' => $hash,
        ]);

        return response()->json([
            'message' => 'Document mis à jour avec succès.',
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

        return response()->json([204]);
    }

    public function downloadQrCode($id)
    {
        $document = Document::findOrFail($id);
        $path = 'qrcodes/' . $document->hash . '.png';
        return response()->download(storage_path('app/public/' . $path));
    }

}

