@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label required-field">Nom</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label required-field">Prénom</label>
        <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom ?? '') }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label required-field">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Photo de profil (Optionnel)</label>
    <input type="file" name="photo" class="form-control" accept="image/*">
    @if(isset($user) && $user->photo)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo actuelle" style="max-height: 80px; border-radius: 8px;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                <label class="form-check-label text-danger" for="remove_photo">
                    Supprimer la photo actuelle
                </label>
            </div>
        </div>
    @endif
</div>

<hr>
<div class="mb-3">
    <label class="form-label required-field">Rôles</label>
    <select name="roles[]" class="form-select" multiple required>
        @foreach($roles as $role)
            <option value="{{ $role->name }}"
                @if(isset($user) && $user->roles->contains($role)) selected @endif>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs rôles.</small>
</div>
