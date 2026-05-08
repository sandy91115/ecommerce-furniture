<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $this->authorize('contacts.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
<<<<<<< HEAD
        $this->authorize('contacts.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
<<<<<<< HEAD
        $this->authorize('contacts.delete');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Enquiry moved to Recycle Bin successfully.');
    }
}
