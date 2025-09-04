@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="mb-3">
    <label for="fiche" class="form-label required-field">Fichier PDF de la fiche</label>
    <input type="file" name="fiche" id="fiche" class="form-control" required accept=".pdf">
</div>

@if(isset($fichePreinscription) && $fichePreinscription->chemin_fichier)
    <div class="alert alert-info">
        Fichier actuel : <a href="{{ asset('storage/' . $fichePreinscription->chemin_fichier) }}" target="_blank">{{ $fichePreinscription->nom_original }}</a>
    </div>
@endif
