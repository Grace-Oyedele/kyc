<?php

namespace App\Http\Controllers;

use App\Models\CustomerBank;
use Illuminate\Http\Request;

class CustomerBankController extends Controller
{

    use ApiResponser;

    public function index()
    {
        $loginUser = $request->user();
        if ($loginUser->role == "customer") {
            return $this->error("Only accessible to admin user", 400);
        }

        $customer_banks = CustomerBank::all();
        return $this->success(['data' =>  $customer_banks]);

    }


    public function store(Request $request)
    {
        $loginUser = $request->user();

        $validatedData = $request->validate([
            "bank_name" => "required",
            "bank_code" => "required|integer",
            "account_number" => 'required|integer|min:10|unique:customer_banks,account_number',
            "account_name" => "required|string",
        ]);

        $validatedData["user_id"] = $loginUser->id;
        $customer = CustomerBank::create($validatedData);
        $response = ['data'=> '' , 'message' => 'Bank details uploaded successfully.'];
        return $this->success($response);
    }


    public function show($customer_id)
    {

        $customer_bank = CustomerBank::where('customer_id', $customer_id)->first();
        if (empty($customer_bank)) {
            return $this->error("details not found", 404);
        }
        return $this->success(['data' => $customer_bank]);
    }


    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            "customer_id" => "required",
            "bank_name" => "required",
            "bank_code" => "required|integer",
            "account_number" => 'required|integer|min:10|unique:customer_banks,account_number',
            "account_name" => "required|string"
        ]);

        $customer_bank = CustomerBank::where("id", $id)->first();
        if (empty($customer_bank)) {
            return $this->error("Details not found", 404);
        }

        $customer_bank->update($validatedData);
        $response = ['data' => $customer_bank, 'message' => 'details updated successfully.'];
        return $this->success($response);
    }

    public function destroy($id)
    {
        $customer_bank = CustomerBank::where('id', $id)->first();
        if (empty($customer_bank)) {
            return $this->error("details not found", 404);
        }
        $customer_bank->delete();
        return $this->success([
            "data" => "details deleted"
        ]);

    }
}
