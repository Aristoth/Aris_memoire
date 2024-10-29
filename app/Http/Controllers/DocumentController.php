<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Nette\Schema\Expect;

class DocumentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    
    public function index()
    {
        $documents = Document::all();
        return response()->json($documents);
    }


    public function store(StorePostRequest $request)
    {
        $hash = Str::uuid();  // Générer un hash unique

        $document = $request->user()->documents()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'hash' => $hash,
        ]);

        return response()->json([
            'message' => 'Document recorded sucessfully',
            'document' => $document,
        ]);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $hash = Str::uuid();  // Generate a unique hash
    
        // Find the document by its ID
        $document = Document::findOrFail($id);
    
        // Update the document
        $document->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'hash' => $hash,
        ]);
        $updatedDocument = $document->fresh();
        return response()->json([
            'message' => 'Document updated successfully.',
            'document' => $updatedDocument, 
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

        return response()->json(['message' => 'The document has been deleted sucessfully']);
    }

    public function downloadQrCode($id)
    {
        $document = Document::findOrFail($id);
        $path = 'qrcodes/' . $document->hash . '.png';
        return response()->download(storage_path('app/public/' . $path));
    }

}

