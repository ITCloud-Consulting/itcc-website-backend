<?php

namespace App\Services;

use App\DTOs\ContactDTO;
use App\Events\ContactSubmitted;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function createContact(ContactDTO $contactDTO): Contact
    {
        try {
            //code...
            return DB::transaction(function () use ($contactDTO) {
                $contact = Contact::create($contactDTO->toArray());

                // Declencher l'evenement
                ContactSubmitted::dispatch($contact, $contactDTO->metadata);
                
                Log::info('Contact created successfully', [
                    'contact_id' => $contact->id,
                    'email' => $contact->email
                ]);
                return $contact;
            });
        } catch (\Exception $e) {
            //throw $th;
            Log::error('Failed to create contact', [
                'error' => $e->getMessage(),
                'data' => $contactDTO->toArray()
            ]);
            throw $e;
        }
    }

    public function getContacts(array $filters = [], int $perPage = 15){
        $query = Contact::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage);
    }
}