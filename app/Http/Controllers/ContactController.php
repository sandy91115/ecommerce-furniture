<?php

namespace App\Http\Controllers;

use App\Mail\Contactus;
use App\Models\Contact;
<<<<<<< HEAD
use App\Models\Setting;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('emails.contact');
    }

    public function send(Request $request)
    {
        try {
            // Validation logic
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'number' => 'required|numeric',
                'subject' => 'required|string|max:255',
<<<<<<< HEAD
                'Message' => 'required|string|max:2000',
=======
                'Message' => 'required|string',
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            ]);

            // Prepare enquiry data
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'number' => $request->number,
                'subject' => $request->subject,
                'message' => $request->Message,
            ];

            // Save to database
            Contact::create($data);
            
<<<<<<< HEAD
            // Notify the site owner by email.
=======
            // Send email (optional: keep or remove)
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            try {
                Mail::raw('Name: ' . $data['name'] . "\n" . 
                        'Email: ' . $data['email'] . "\n" . 
                        'Number: ' . $data['number'] . "\n" . 
                        'Subject: ' . $data['subject'] . "\n" . 
                        'Message: ' . $data['message'], 
                    function ($message) use ($data) {
<<<<<<< HEAD
                        $message->to(Setting::get('admin_email', config('mail.from.address')))
=======
                        $message->to('your-email@gmail.com') // Replace with your email
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                                ->subject($data['subject']);
                    }
                );
            } catch (\Exception $e) {
                \Log::warning('Email sending failed: ' . $e->getMessage());
            }

            // Return raw HTML for success
<<<<<<< HEAD
            $safeName = e($data['name']);
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            $successMessage = "
                <fieldset class='border border-green-300 p-6 mb-4 rounded-md bg-green-50'>
                    <div>
                        <h3 class='text-green-700 text-lg font-semibold mb-2'>Email Sent Successfully.</h3>
<<<<<<< HEAD
                        <p class='text-green-600'>Thank you <strong class='font-medium text-green-800'>{$safeName}</strong>, your message has been submitted to us.</p>
=======
                        <p class='text-green-600'>Thank you <strong class='font-medium text-green-800'>{$data['name']}</strong>, your message has been submitted to us.</p>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                    </div>
                </fieldset>
            ";

            return response()->json([
                'message' => $successMessage,
            ]);
            

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
