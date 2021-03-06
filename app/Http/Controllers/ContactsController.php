<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Resources\Contact as ContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Contact::class);

        return ContactResource::collection(request()->user()->contacts);
    }

    public function store()
    {
        $this->authorize('create', Contact::class);

         $contact = request()->user()->contacts()->create( $this->validateDate());

         return (new ContactResource($contact))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);

        return new ContactResource($contact);
    }
    public function update(Contact $contact)
    {
        $this->authorize('update', $contact);

        $contact->update($this->validateDate());

        return (new ContactResource($contact))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    private function validateDate()
    {
        return request()->validate([
            'name'=> 'required',
            'email'=> 'required|email',
            'birthday'=> 'required',
            'company'=> 'required',
        ]);
    }

}
