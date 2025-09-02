@component('mail::message')
# Merci {{ $contact->name }}!

Nous avons bien reçu votre message concernant "{{ $contact->subject }}".

Notre équipe vous répondra dans les plus brefs délais.

**Votre message:**
{{ $contact->message }}

Si vous avez des questions urgentes, n'hésitez pas à nous contacter directement.

Cordialement,<br>
L'équipe {{ config('app.name', '') }}
@endcomponent