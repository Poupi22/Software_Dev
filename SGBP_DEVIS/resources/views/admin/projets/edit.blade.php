@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projets.index') }}" class="text-gray-600 hover:text-gray-800">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Modifier : {{ $projet->titre }}</h2>
                    <p class="text-xs text-gray-500">Modifier les informations du projet</p>
                </div>
            </div>
        </div>

        <div class="content-with-mobile-nav p-4 md:p-8 max-w-3xl mx-auto">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <ul class="text-red-700 text-sm list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <p class="text-green-700 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('admin.projets.update', $projet) }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre du projet <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" value="{{ old('titre', $projet->titre) }}" required
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <input type="text" name="client_nom" value="{{ old('client_nom', $projet->client_nom) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                        <input type="text" name="lieu" value="{{ old('lieu', $projet->lieu) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="categorie"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Sélectionner...</option>
                            @foreach (['résidentiel', 'commercial', 'rénovation', 'construction neuve', 'industriel'] as $cat)
                                <option value="{{ $cat }}"
                                    {{ old('categorie', $projet->categorie) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                        <input type="number" name="annee" value="{{ old('annee', $projet->annee) }}" min="1900" max="2100"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durée</label>
                        <input type="text" name="duree" value="{{ old('duree', $projet->duree) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Superficie</label>
                        <input type="text" name="superficie" value="{{ old('superficie', $projet->superficie) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500 resize-none">{{ old('description', $projet->description) }}</textarea>
                </div>

                {{-- ── PHOTOS EXISTANTES ── --}}
                @if ($projet->photos->count() > 0)
                    <div class="border border-gray-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                <span class="material-icons text-blue-600">photo_library</span>
                                Photos actuelles ({{ $projet->photos->count() }} / 10)
                            </h4>
                            <p class="text-xs text-gray-500">Cochez pour supprimer — Cliquez ⭐ pour définir comme principale</p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($projet->photos as $photo)
                                <div class="relative group rounded-lg overflow-hidden border-2 {{ $photo->principale ? 'border-blue-500' : 'border-gray-200' }}"
                                     id="photo-card-{{ $photo->id }}">
                                    <img src="{{ asset('storage/' . $photo->path) }}"
                                         class="w-full h-28 object-cover cursor-pointer"
                                         onclick="openLightbox('{{ asset('storage/' . $photo->path) }}')">

                                    <!-- Badge principale -->
                                    @if ($photo->principale)
                                        <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded flex items-center gap-1">
                                            <span class="material-icons text-xs">star</span> Principale
                                        </span>
                                    @endif

                                    <!-- Actions au survol -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center gap-2">
                                        @if (!$photo->principale)
                                            <button type="button"
                                                onclick="setPrincipale({{ $projet->id }}, {{ $photo->id }})"
                                                class="hidden group-hover:flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full"
                                                title="Définir comme principale">
                                                <span class="material-icons text-sm">star</span>
                                            </button>
                                        @endif
                                        <button type="button"
                                            onclick="deletePhoto({{ $projet->id }}, {{ $photo->id }})"
                                            class="hidden group-hover:flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-full"
                                            title="Supprimer cette photo">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── AJOUTER DE NOUVELLES PHOTOS ── --}}
                @php $existingCount = $projet->photos->count(); $maxNew = 10 - $existingCount; @endphp
                <div class="border-2 border-dashed border-blue-300 rounded-xl p-6 bg-blue-50" id="add-photos-section">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-icons text-blue-600 text-3xl">add_photo_alternate</span>
                        <div>
                            <h4 class="font-bold text-gray-800">Ajouter des photos</h4>
                            <p class="text-xs text-gray-500" id="photos-section-hint">
                                @if ($maxNew > 0)
                                    Encore {{ $maxNew }} photo(s) possible(s) — Max 3 Mo par photo
                                @else
                                    Limite de 10 photos atteinte. Supprimez des photos existantes pour en ajouter.
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($maxNew > 0)
                        <label for="photos-input" id="photos-label"
                            class="flex flex-col items-center justify-center w-full h-28 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-blue-50 transition mb-4">
                            <span class="material-icons text-blue-400 text-3xl mb-1">add_photo_alternate</span>
                            <p class="text-sm text-gray-600">Cliquez pour ajouter des photos</p>
                            <p class="text-xs text-gray-400" id="photos-label-hint">Vous pouvez encore ajouter {{ $maxNew }} photo(s)</p>
                            <input id="photos-input" type="file" name="photos[]" multiple accept="image/*"
                                class="hidden" onchange="addNewPhotos(this)">
                        </label>

                        <div id="photos-preview" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-2"></div>
                        <p id="photos-count" class="text-xs text-gray-500"></p>
                    @else
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-center gap-3">
                            <span class="material-icons text-amber-600">info</span>
                            <p class="text-sm text-amber-800">Limite de 10 photos atteinte. Supprimez des photos existantes pour en ajouter de nouvelles.</p>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                        <input type="number" name="ordre" value="{{ old('ordre', $projet->ordre) }}" min="0"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="actif" value="0">
                            <input type="checkbox" name="actif" value="1"
                                {{ old('actif', $projet->actif) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-700">Actif (visible sur le site)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.projets.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Annuler</a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                        <span class="material-icons">save</span> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lightbox simple --}}
    <div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4"
         onclick="closeLightbox()">
        <button class="absolute top-4 right-4 text-white text-4xl font-bold" onclick="closeLightbox()">&times;</button>
        <img id="lightbox-img" src="" class="max-w-full max-h-full rounded-lg shadow-2xl" onclick="event.stopPropagation()">
    </div>

    <script>
        var MAX_PHOTOS    = 10;
        var existingCount = {{ $existingCount }};
        var selectedFiles = []; // Nouvelles photos à ajouter

        // ── AJOUTER des nouvelles photos (accumulation) ───────────────────
        function addNewPhotos(input) {
            var newFiles  = Array.from(input.files);
            var remaining = MAX_PHOTOS - existingCount - selectedFiles.length;

            if (remaining <= 0) {
                alert('⚠️ Limite de ' + MAX_PHOTOS + ' photos atteinte.');
                input.value = '';
                return;
            }

            if (newFiles.length > remaining) {
                newFiles = newFiles.slice(0, remaining);
                alert('⚠️ Seules ' + remaining + ' photo(s) supplémentaire(s) ont été ajoutées.');
            }

            newFiles.forEach(function(f) { selectedFiles.push(f); });
            input.value = ''; // Reset pour permettre re-sélection

            refreshNewPreview();
            syncInputFiles();
        }

        // ── SUPPRIMER une nouvelle photo (avant envoi) ────────────────────
        function removeNewPhoto(index) {
            selectedFiles.splice(index, 1);
            refreshNewPreview();
            syncInputFiles();
        }

        // ── SYNCHRONISER l'input file ─────────────────────────────────────
        function syncInputFiles() {
            var input = document.getElementById('photos-input');
            if (!input) return;
            var dt = new DataTransfer();
            selectedFiles.forEach(function(f) { dt.items.add(f); });
            input.files = dt.files;
        }

        // ── RAFRAÎCHIR la prévisualisation des nouvelles photos ───────────
        function refreshNewPreview() {
            var preview  = document.getElementById('photos-preview');
            var counter  = document.getElementById('photos-count');
            var hint     = document.getElementById('photos-label-hint');
            var label    = document.getElementById('photos-label');
            if (!preview) return;

            preview.innerHTML = '';
            var total     = existingCount + selectedFiles.length;
            var remaining = MAX_PHOTOS - total;

            if (counter) counter.textContent = selectedFiles.length + ' nouvelle(s) photo(s) sélectionnée(s)';
            if (hint)    hint.textContent    = remaining > 0 ? 'Vous pouvez encore ajouter ' + remaining + ' photo(s)' : 'Limite atteinte';
            if (label)   label.style.display = remaining > 0 ? '' : 'none';

            selectedFiles.forEach(function(file, index) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'relative rounded-lg overflow-hidden border-2 border-green-300';
                    div.innerHTML =
                        '<img src="' + e.target.result + '" class="w-full h-24 object-cover">' +
                        '<span class="absolute top-1 left-1 bg-green-600 text-white text-xs px-1 py-0.5 rounded">Nouveau</span>' +
                        '<button type="button" onclick="removeNewPhoto(' + index + ')" ' +
                        'class="absolute top-1 right-1 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center shadow hover:bg-red-700 transition" ' +
                        'title="Retirer cette photo">' +
                        '<span class="material-icons text-sm">close</span>' +
                        '</button>';
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // ── ANCIENNE FONCTION (conservée pour compatibilité) ──────────────
        function previewNewPhotos(input, maxNew) {
            var files = Array.from(input.files);
            if (files.length > maxNew) {
                files = files.slice(0, maxNew);
                alert('⚠️ Maximum ' + maxNew + ' photo(s) supplémentaire(s) autorisée(s).');
            }

            selectedFiles = files;
            preview.innerHTML = '';
            counter.textContent = files.length + ' nouvelle(s) photo(s) sélectionnée(s)';

            files.forEach(function(file, index) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'relative group rounded-lg overflow-hidden border-2 border-green-300';
                    div.innerHTML =
                        '<img src="' + e.target.result + '" class="w-full h-24 object-cover">' +
                        '<span class="absolute top-1 right-1 bg-green-600 text-white text-xs px-1 py-0.5 rounded">Nouveau</span>';
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // ── SUPPRIMER UNE PHOTO (AJAX) ────────────────────────────────────
        function deletePhoto(projetId, photoId) {
            if (!confirm('Supprimer cette photo définitivement ?')) return;

            fetch('/admin/projets/' + projetId + '/photos/' + photoId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?
                        document.querySelector('meta[name="csrf-token"]').content :
                        '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    var card = document.getElementById('photo-card-' + photoId);
                    if (card) card.remove();
                    // Mettre à jour le compteur
                    var countEl = document.querySelector('.font-bold.text-gray-800.flex.items-center.gap-2');
                    // Recharger la page pour mettre à jour le compteur
                    location.reload();
                }
            })
            .catch(function() { alert('Erreur lors de la suppression.'); });
        }

        // ── DÉFINIR PHOTO PRINCIPALE (AJAX) ──────────────────────────────
        function setPrincipale(projetId, photoId) {
            fetch('/admin/projets/' + projetId + '/photos/' + photoId + '/principale', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(function() { alert('Erreur lors de la mise à jour.'); });
        }

        // ── LIGHTBOX ──────────────────────────────────────────────────────
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.remove('hidden');
            document.getElementById('lightbox').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.getElementById('lightbox').classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endsection
