<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ContactDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAutoReply;
use App\Mail\ContactNotification;

class ContactController extends Controller
{

    public function __construct(
        private readonly ContactService $contactService
    ) {
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $contactDTO = ContactDTO::fromRequest($request);
            $contact = $this->contactService->createContact($contactDTO);

            // Send autoreply
            // Mail::to($contact->email)->queue(new ContactAutoReply($contact));

            // // send mail to support@itcloudconsultings.com
            // Mail::to('support@itcloudconsultings.com')->queue(new ContactNotification($contact));
            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons bientôt!',
                'data' => [
                    'id' => $contact->id,
                    'submitted_at' => $contact->created_at->toISOString()
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(): JsonResponse
    {
        $contacts = $this->contactService->getContacts(
            filters: request()->only(['status', 'email', 'date_from', 'date_to']),
            perPage: request()->integer('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
