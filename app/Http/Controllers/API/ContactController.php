<?php

namespace App\Http\Controllers\API;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Contact::paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'phone_number' => ['required'],
        ]);

        try {
            $data = Contact::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
            ]
            );
            return response()->json($data, 201);

        } catch (\Exception $e) {
            info($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return $contact;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        Contact::where('id', $contact->id)->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        return response(['message' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'Success'], 200);
    }

    public function storeRecords()
    {
        $content = (new FileController)->uploadCSV();

        try{
        foreach ($content as $data) {
            list($firstName, $lastName, $number) = $data;
            Contact::create([
                "name" => implode(" ", [$firstName, $lastName]),
                "phone_number" => $number,
            ]);
        }
        return response()->json([
            "message" => "Success",
        ], 201);}
        catch(\Illuminate\Database\QueryException $e) {
        Log::error("An error occured " . $e->getMessage());
        }

    }
}
