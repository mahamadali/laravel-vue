<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::where(['user_id' => Auth::user()->id])->get();
        return response()->json([
            'Contacts'    => $contacts,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name'        => 'required|max:255',
            'address' => 'required',
            'email'        => 'required|email|max:255',
            'photo' => 'required|file|image'
        ]);

        $imageName = time().'.'.$request->photo->getClientOriginalExtension();
        $request->photo->move(public_path('images'), $imageName);
        
        $contact = Contact::create([
            'full_name'        => request('full_name'),
            'address' => request('address'),
            'email'        => request('email'),
            'user_id'     => Auth::user()->id,
            'photo'     => $imageName
        ]);
 
        return response()->json([
            'contact'    => $contact,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $this->validate($request, [
            'full_name'        => 'required|max:255',
            'address' => 'required',
            'email'        => 'required|email|max:255',
        ]);
 
        $contact->full_name = request('full_name');
        $contact->address = request('address');
        $contact->email = request('email');
        $contact->save();
 
        return response()->json([
            'message' => 'Contact updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact = Contact::find($contact->id);
        $contact->delete();

        return response()->json('Successfully Deleted');
    }
}
