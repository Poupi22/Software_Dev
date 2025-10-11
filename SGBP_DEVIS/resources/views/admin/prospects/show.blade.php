@extends('admin.layouts.app')
@section('title', $prospect->nom_complet)
@section('content')

    <div class="min-h-screen bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.prospects.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-lg md:text-2xl font-bold text-gray-800">{{ $prospect->nom_complet }}</h2>
                        <p class="text-xs text-gray-500">{{ $prospect->entreprise ?? $prospect->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if ($prospect->statut !== 'converti')
                        @can('prospects.convert')
                            <form action="{{ route('admin.prospects.convert', $prospect) }}" method="POST"
                                onsubmit="return confirm('Convertir ce prospect en client ?')">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-1">
                                    <span class="material-icons text-sm">person_add</span>
                                    <span class="hidden md:inline">Convertir en client</span>
                                </button>
                            </form>
                        @endcan
                    @else
                        @if ($prospect->client)
                            <a href="{{ route('admin.clients.show', $prospect->client) }}"
                                class="px-4 py-2 border border-green-500 text-green-600 rounded-lg hover:bg-green-50 flex items-center gap-1">
                                <span class="material-icons text-sm">person</span>
                                <span class="hidden md:inline">Voir le client</span>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2"><span
                        class="material-icons">check_circle</span>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-700 flex items-center gap-2"><span
                        class="material-icons">error</span>{{ session('error') }}</p>
            </div>
        @endif

        <div class="p-4 md:p-8 max-w-4xl mx-auto grid lg:grid-cols-3 gap-6">

            <!-- Infos principales -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Coordonnées -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-icons text-blue-500">person</span> Coordonnées
                    </h3>
                    <dl class="grid md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Nom</dt>
                            <dd class="font-medium">{{ $prospect->nom_complet }}</dd>
                        </div>
                        @if ($prospect->entreprise)
                            <div>
                                <dt class="text-gray-500">Entreprise</dt>
                                <dd class="font-medium">{{ $prospect->entreprise }}</dd>
                            </div>
                        @endif
                        @if ($prospect->email)
                            <div>
                                <dt class="text-gray-500">Email</dt>
                                <dd><a href="mailto:{{ $prospect->email }}"
                                        class="text-blue-600 hover:underline">{{ $prospect->email }}</a></dd>
                            </div>
                        @endif
                        @if ($prospect->telephone)
                            <div>
                                <dt class="text-gray-500">Téléphone</dt>
                                <dd><a href="tel:{{ $prospect->telephone }}"
                                        class="text-blue-600 hover:underline">{{ $prospect->telephone }}</a></dd>
                            </div>
                        @endif
                        @if ($prospect->source)
                            <div>
                                <dt class="text-gray-500">Source</dt>
                                <dd class="font-medium">{{ ucfirst($prospect->source) }}</dd>
                            </div>
                        @endif
                        @if ($prospect->date_premier_contact)
                            <div>
                                <dt class="text-gray-500">Premier contact</dt>
                                <dd class="font-medium">{{ $prospect->date_premier_contact->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Objet et message -->
                @if ($prospect->objet || $prospect->message)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="material-icons text-blue-500">message</span> Demande
                        </h3>
                        @if ($prospect->objet)
                            <p class="font-semibold text-gray-800 mb-2">{{ $prospect->objet }}</p>
                        @endif
                        @if ($prospect->message)
                            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700 whitespace-pre-wrap">
                                {{ $prospect->message }}</div>
                        @endif
                    </div>
                @endif

                <!-- Mise à jour du statut -->
                @can('prospects.update')
                    @if ($prospect->statut !== 'converti')
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-blue-500">edit</span> Suivi
                            </h3>
                            <form action="{{ route('admin.prospects.update', $prospect) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-sm font-medium mb-1">Statut</label>
                                    <select name="statut"
                                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                                        @foreach (['nouveau' => 'Nouveau', 'contacte' => 'Contacté', 'qualifie' => 'Qualifié', 'perdu' => 'Perdu'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ $prospect->statut === $val ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Notes internes</label>
                                    <textarea name="notes" rows="4"
                                        class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-blue-500"
                                        placeholder="Notes de suivi...">{{ $prospect->notes }}</textarea>
                                </div>
                                <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    Enregistrer le suivi
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan

            </div>

            <!-- Sidebar -->
            <div class="space-y-5">

                <!-- Statut badge -->
                <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                    @php
                        $statutColors = [
                            'nouveau' => 'bg-blue-100 text-blue-700',
                            'contacte' => 'bg-yellow-100 text-yellow-700',
                            'qualifie' => 'bg-purple-100 text-purple-700',
                            'converti' => 'bg-green-100 text-green-700',
                            'perdu' => 'bg-red-100 text-red-700',
                        ];
                        $statutLabels = [
                            'nouveau' => 'Nouveau',
                            'contacte' => 'Contacté',
                            'qualifie' => 'Qualifié',
                            'converti' => 'Converti',
                            'perdu' => 'Perdu',
                        ];
                        $class = $statutColors[$prospect->statut] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="px-5 py-2 text-sm font-bold rounded-full {{ $class }}">
                        {{ $statutLabels[$prospect->statut] ?? ucfirst($prospect->statut) }}
                    </span>
                    <p class="text-xs text-gray-400 mt-2">Reçu le {{ $prospect->created_at->format('d/m/Y') }}</p>
                </div>

                <!-- Assigné à -->
                @if ($prospect->assignedTo)
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <h4 class="font-bold text-sm text-gray-500 uppercase mb-3">Assigné à</h4>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-indigo-600 rounded-full text-white font-bold flex items-center justify-center">
                                {{ strtoupper(substr($prospect->assignedTo->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium">{{ $prospect->assignedTo->name }}</p>
                                <p class="text-xs text-gray-500">{{ $prospect->assignedTo->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Client converti -->
                @if ($prospect->statut === 'converti' && $prospect->client)
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                        <h4 class="font-bold text-green-700 mb-2 flex items-center gap-1">
                            <span class="material-icons text-sm">check_circle</span> Converti en client
                        </h4>
                        <p class="font-medium text-sm">{{ $prospect->client->nom_complet }}</p>
                        <a href="{{ route('admin.clients.show', $prospect->client) }}"
                            class="text-xs text-blue-600 hover:underline mt-1 inline-block">
                            Voir la fiche →
                        </a>
                    </div>
                @endif

                <!-- Danger Zone -->
                @can('prospects.delete')
                    @if ($prospect->statut !== 'converti')
                        <div class="bg-white rounded-xl shadow-sm p-5">
                            <h4 class="text-sm font-semibold text-red-600 mb-3">Zone de danger</h4>
                            <form action="{{ route('admin.prospects.destroy', $prospect) }}" method="POST"
                                onsubmit="return confirm('Supprimer ce prospect ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 text-sm">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan

            </div>
        </div>
    </div>
@endsection
