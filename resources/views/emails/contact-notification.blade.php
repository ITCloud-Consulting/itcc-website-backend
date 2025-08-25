@component('mail::message')
# Nouveau message de contact

**De:** {{ $contact->name }} ({{ $contact->email }})
**Sujet:** {{ $contact->subject }}
**Date:** {{ $contact->created_at->format('d/m/Y à H:i') }}

## Message

{{ $contact->message }}

---

**Métadonnées:**
@if($contact->metadata)
- IP: {{ $contact->metadata['ip'] ?? 'N/A' }}
- Navigateur: {{ $contact->metadata['user_agent'] ?? 'N/A' }}
@endif

@component('mail::button', ['url' => config('app.url') . '/admin/contacts/' . $contact->id])
Voir dans l'admin
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent