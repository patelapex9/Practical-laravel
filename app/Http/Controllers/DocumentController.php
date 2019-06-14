<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    //
    public function index()
    {
        $documents = Document::all();
        if ($documents) {
            return response()->json($documents, 200);
        } else {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'message' => "something went wrog pleae try again.",
            ], 201);
        }

    }

    public function show($id)
    {
        return Document::find($id);
    }

    public function store(Request $request)
    {

        $targetpath= "documents";
        $uploadedFile = $request->file('file');
        $folderName = $uploadedFile->getClientOriginalName();
        $filename = time() . $uploadedFile->getClientOriginalName();
        $fileupload = Storage::disk('public_uploads')->putFileAs(
            "documents",
            $uploadedFile,
            $filename
        );
        $data = array();
        $data['document'] = $targetpath.'/' . $filename;
        $data['name'] = $request->filename;
        $ffname = explode('.', $filename);
        $data['ext'] = end($ffname);
        try {
            $article = Document::create($data);
            return response()->json($article, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 201);
        }

    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->update($request->all());

        return $document;
    }

    public function delete(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return 204;
    }
    
}