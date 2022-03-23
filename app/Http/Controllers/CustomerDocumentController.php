<?php

namespace App\Http\Controllers;

use App\Models\CustomerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerDocumentController extends Controller
{
    use ApiResponser;
    public function index(Request $request)

    {

        $loginUser = $request->user();
        if ($loginUser->role == "customer") {
            return $this->error("Only accessible to admin user", 400);
        }

        $customer_documents = CustomerDocument::all();
        return $this->success(['data' => $customer_documents]);

    }


    public function store(Request $request)
    {
        $loginUser = $request->user();
        $validatedData = $request->validate([
            "document_name" => "required|in:cac,certificate,dob",
            "document_file" => "required|file",

        ]);

        //handle  upload  certificate.pdf // tdgajdvstya.pdf
        if ($request->hasFile("document_file")) {
            $file = $request->file("document_file");
            $name = Str::random(12) . "." . $file->getClientOriginalExtension();
            $dir = public_path("documents");
            $file->move($dir, $name);
        }

        CustomerDocument::create([
            "user_id" => $loginUser->id,
            "document_name" => $request->document_name,
            "document_file" => $name
        ]);
        $response = ['data' => '', 'message' => 'Bank document uploaded successfully.'];
        return $this->success($response);
    }


    public function show($docId)
    {

        $customer_document = CustomerDocument::where('id', $docId)->first();
        if (empty($customer_document)) {
            return $this->error("details not found", 404);
        }
        return $this->success(['data' => $customer_document]);
    }


    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            "customer_id" => "required",
            "document_name" => "required",
            "document_file" => "required|file",

        ]);

        $customer_document = CustomerDocument::where("id", $id)->first();
        if (empty($customer_document)) {
            return $this->error("Details not found", 404);
        }

        if ($request->hasFile("document_file")) {
            $file = $request->file("document_file");
            $name = Str::random(12) . "." . $file->getClientOriginalExtension();
            $dir = public_path("documents");
            $file->move($dir, $name);
        }

        $customer_document->update([
            "document_name" => $request->document_name,
            "document_file" => $name
        ]);
        $response = ['data' => $customer_document, 'message' => 'details updated successfully.'];
        return $this->success($response);
    }

    public function destroy($id)
    {

        $customer_document = CustomerDocument::where('id', $id)->first();
        if (empty($customer_document)) {
            return $this->error("details not found", 404);
        }
        $customer_document->delete();
        return $this->success([
            "data" => "details deleted"
        ]);
    }




}
