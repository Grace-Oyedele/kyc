<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    use ApiResponser;

    public function index($request )
    {
        $loginUser = $request->user();
        if ($loginUser->role == "customer") {
            return $this->error("Only accessible to admin user", 400);
        }

        $newsletters = Newsletter::all();
        return $this->success(['data' =>  $newsletters]);

    }

    publlic function store(Request $request)
    {
        $validatedData = $request->validate([
            "email" => "required|string",
        ]);

        $newsletter = Newsletter::create($validatedData);
        $response = ['data'=> '' , 'message' => 'Email stored.'];
        return $this->success($response);

    }

    public function show($id)
    {
        $newsletter = Newsletter :: where('id', $id)->first();
        if (empty($newsletter)) {
            return $this->error ("details not found", 404);
        }
        return $this->success (["data"=>$newsletter]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            "email" => "required|string",
        ]);

        $newsletter = Newsletter::where("id", $id)->first();
        if (empty($newsletter)) {
            return $this->error("Customer not found", 404);
        }
        $newsletter->update($validatedData);
        $response = ['data' => $newsletter, 'message' => 'newsletter updated successfully.'];
        return $this->success($response);
    }

    public function destroy($id)
    {
        $newsletter = Newsletter::where('id', $id)->first();
        if (empty($newsletter)) {
            return $this->error("detail not found", 404);
        }
        $newsletter->delete();
        return $this->success([
            "data" => "detail deleted"
        ]);

    }
}
