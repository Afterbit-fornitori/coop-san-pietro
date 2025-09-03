<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        // Spatie Multi-tenancy gestisce automaticamente lo scoping
        $documents = Document::all();
        return view('company.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('company.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'type' => 'required|string|in:ddt,invoice,other',
            // altri campi necessari
        ]);

        // Spatie Multi-tenancy imposterà automaticamente il company_id
        $validated['path'] = $request->file('file')->store('documents');
        Document::create($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Documento caricato con successo.');
    }

    public function edit(Document $document)
    {
        return view('company.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
            'type' => 'required|string|in:ddt,invoice,other',
            // altri campi necessari
        ]);

        if ($request->hasFile('file')) {
            // Elimina il vecchio file
            Storage::delete($document->path);
            $validated['path'] = $request->file('file')->store('documents');
        }

        $document->update($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Documento aggiornato con successo.');
    }

    public function destroy(Document $document)
    {
        Storage::delete($document->path);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Documento eliminato con successo.');
    }
}
