<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use ApiResponser;


    public function index(Request $request)
    {
        $loginUser = $request->user();
        if ($loginUser->role == "customer") {
            return $this->error("Only accessible to admin user", 400);
        }

        $customers = Customer::all();
        return $this->success(['data' => $customers]);

    }


    public function store(Request $request)
    {
        $loginUser = $request->user();
        $validatedData = $request->validate([
            "first_name" => "required|string",
            "last_name" => "required|string",
            "phone_number" => 'required|string|unique:customers,phone_number',
            "image" => "nullable",
            "bvn" => "nullable",
        ]);
        DB::beginTransaction();
        try {

            $validatedData["user_id"] = $loginUser->id;
            $customer = Customer::create($validatedData);

            DB::commit();
            $response = ['data' => $customer, 'message' => 'Customer created successfully.'];
            return $this->success($response);

        } catch (\Exception $exception) {
            DB::rollBack();
            logger($exception);
            return $this->error("Unable to create customer", 404);
        }

    }


    public function show($id)
    {
        //$customer = Customer::where('id', $id)->first();
       $customer = Customer::with("user.customerDocuments", "user.customerBank")->where('id', $id)->first();
        if (empty($customer)) {
            return   $this->error("Customer not found", 404);
        }
        return $this->success(['data' => $customer]);
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:customers,email,".$id,
            "phone_number" => 'required|string',
            "image" => "nullable",
            "status" => 'nullable|string',
            "kyc_level" => "nullable",
            "bvn" => "nullable",
        ]);

        $customer = Customer::where("id", $id)->first();
        if (empty($customer)) {
            return $this->error("Customer not found", 404);
        }
        $customer->update($validatedData);
        $response = ['data' => $customer, 'message' => 'customer updated successfully.'];
        return $this->success($response);

    }

    public function destroy($id)
    {
        $customer = Customer::where('id', $id)->first();
        if (empty($customer)) {
            return $this->error("customer not found", 404);
        }
        $customer->delete();
        return $this->success([
            "data" => "Customer deleted"
        ]);

    }

    public function activateCustomer($customerId) // customerId is the id on customers table
    {
        $customer = Customer::where('id', $customerId)->first();
        if (empty($customer)) {
            return $this->error("customer not found", 404);
        }

        $customer->status = "active";
        $customer->save();
        return $this->success($customer, "Customer activated successfully");

    }

    public function deactivateCustomer($customerId) // customerId is the id on customers table
    {
        $customer = Customer::where('id', $customerId)->first();
        if (empty($customer)) {
            return $this->error("customer not found", 404);
        }

        $customer->status = "inactive";
        $customer->save();
        return $this->success($customer, "Customer deactivated successfully");

    }

    public function changeCustomerKyc(Request $request) // customerId is the id on customers table
    {
        $request->validate([
           "customer_id" => "required|exists:customers,id",
           "kyc_level" => "required|in:1,2,3"
        ]);
        $customer = Customer::where('id', $request->customer_id)->first();
        if (empty($customer)) {
            return $this->error("customer not found", 404);
        }

        $customer->kyc_level = $request->kyc_level;
        $customer->save();
        return $this->success($customer, "Customer kyc updated successfully");

    }

}
