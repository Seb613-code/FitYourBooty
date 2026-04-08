@php
    $userName = $user->name ?? '—';
    $userEmail = $user->email ?? '—';
@endphp

<p>Un nouvel utilisateur vient de s'inscrire.</p>

<ul>
    <li>Nom : {{ $userName }}</li>
    <li>Email : {{ $userEmail }}</li>
</ul>
