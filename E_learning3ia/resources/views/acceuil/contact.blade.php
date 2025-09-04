@extends('acceuil.layouts.app')

@section('content')
<!-- ======================================================= -->
<!--             HÉRO DE LA PAGE CONTACT                   -->
<!-- ======================================================= -->
<section class="contact-hero">
    <div class="container text-center">
        <h1 class="hero-title">Contactez-nous</h1>
        <p class="hero-subtitle">Nous sommes là pour répondre à toutes vos questions. N'hésitez pas !</p>
    </div>
</section>

<!-- WhatsApp Floating Icon -->
@if (!empty($contact->whatsapp))
    <a href="https://wa.me/{{ str_replace(' ', '', $contact->whatsapp) }}" class="whatsapp-float" target="_blank" title="Contactez-nous sur WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
@endif


<!-- ======================================================= -->
<!--       CONTENU PRINCIPAL (FORMULAIRE & INFOS)          -->
<!-- ======================================================= -->
<section class="contact-content-section">
    <div class="container">
        <div class="row g-5">

            <!-- COLONNE FORMULAIRE -->
            <div class="col-lg-7">
                <div class="contact-form-container">
                    <h3 class="form-title">Envoyez-nous un message</h3>
                    <p class="text-muted mb-4">Remplissez le formulaire et notre équipe vous répondra dans les plus brefs délais.</p>

                    <!-- ✅ ALERTES DE CONFIRMATION -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- ✅ FORMULAIRE -->
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact-nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="contact-nom" name="nom" placeholder="Votre nom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contact-prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="contact-prenom" name="prenom" placeholder="Votre prénom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contact-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="contact-email" name="email" placeholder="exemple@domaine.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contact-tel" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="contact-tel" name="tel" placeholder="Votre numéro">
                            </div>
                            <div class="col-12">
                                <label for="contact-sujet" class="form-label">Sujet de votre demande</label>
                                <select class="form-select" id="contact-sujet" name="sujet">
                                    <option selected>Choisir un sujet...</option>
                                    <option value="Inscription">Demande d'inscription</option>
                                    <option value="Stage">Demande de stage</option>
                                    <option value="Information">Demande d'information</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="contact-message" class="form-label">Votre message</label>
                                <textarea class="form-control" id="contact-message" name="message" rows="6" placeholder="Écrivez votre message ici..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Envoyer le message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COLONNE INFOS & MAP -->

            <div class="col-lg-5">
    <div class="contact-info-container">
        <div class="map-container mb-4">
            <iframe src="{{ $contact->iframe_localisation ?? '' }}" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <ul class="info-list">
            <li>
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Adresse</strong>
                    <span>{{ $contact->adresse ?? 'Non défini' }}</span>
                </div>
            </li>
            <li>
                <i class="fas fa-phone"></i>
                <div>
                    <strong>Téléphone</strong>
                    <span>{{ $contact->telephone ?? 'Non défini' }}</span>
                </div>
            </li>
            <li>
                <i class="fas fa-envelope"></i>
                <div>
                    <strong>Email</strong>
                    <span>{{ $contact->email ?? 'Non défini' }}</span>
                </div>
            </li>
            <li>
                <i class="fab fa-whatsapp"></i>
                <div>
                    <strong>WhatsApp</strong>
                    <span>{{ $contact->whatsapp ?? 'Non défini' }}</span>
                </div>
            </li>
        </ul>

        <!-- HEURES D'OUVERTURE -->
        <div class="hours-section">
            <h4 class="hours-title">
                <i class="fas fa-clock"></i>
                Heures d'ouverture
            </h4>
            <ul class="hours-list">
                <li>
                    <span class="day">Lundi-Vendredi</span>
                    <span class="time">08h00 - 18h00</span>
                </li>
                <li>
                    <span class="day">Samedi</span>
                    <span class="time">09h00 - 13h00</span>
                </li>
                <li>
                    <span class="day">Dimanche</span>
                    <span class="closed">Fermé</span>
                </li>
            </ul>
        </div>

        <div class="social-links">
            <a href="{{ $contact->facebook_link ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
            <a href="{{ $contact->tiktok_link ?? '#' }}"><i class="fab fa-tiktok"></i></a>
            <a href="{{ $contact->linkedin_link ?? '#' }}"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</div>


        </div>
    </div>
</section>

<style>
    /* ======================================================= */
    /*                   SECTION HÉRO                         */
    /* ======================================================= */
    .contact-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 80px 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ======================================================= */
    /*                 SECTION CONTENU                        */
    /* ======================================================= */
    .contact-content-section {
        padding: 80px 0;
        background-color: #f8fafc;
    }

    /* FORMULAIRE */
    .contact-form-container {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.1);
        height: fit-content;
    }

    .form-title {
        color: #1e3a8a;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.8rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border: none;
        border-radius: 10px;
        padding: 15px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    /* INFOS CONTACT */
    .contact-info-container {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.1);
        height: fit-content;
    }

    .map-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(30, 58, 138, 0.1);
    }

    .map-container iframe {
        border-radius: 10px;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 30px 0;
    }

    .info-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .info-list li:hover {
        background: #e2e8f0;
        transform: translateX(5px);
    }

    .info-list li i {
        font-size: 1.3rem;
        color: #3b82f6;
        margin-right: 15px;
        margin-top: 2px;
        min-width: 25px;
    }

    .info-list li div strong {
        display: block;
        color: #1e3a8a;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .info-list li div span {
        color: #64748b;
        font-size: 0.95rem;
    }

    /* HEURES D'OUVERTURE */
    .hours-section {
        margin-top: 30px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 10px;
    }

    .hours-title {
        color: #1e3a8a;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
    }

    .hours-title i {
        color: #3b82f6;
        margin-right: 10px;
    }

    .hours-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .hours-list li {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .hours-list li:last-child {
        border-bottom: none;
    }

    .hours-list .day {
        font-weight: 600;
        color: #1e293b;
    }

    .hours-list .time {
        color: #64748b;
    }

    .hours-list .closed {
        color: #dc2626;
        font-weight: 500;
    }

    /* LIENS SOCIAUX */
    .social-links {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    /* ======================================================= */
    /*                ICÔNE WHATSAPP FLOTTANTE                */
    /* ======================================================= */
    .whatsapp-float {
        position: fixed;
        width: 70px;
        height: 70px;
        bottom: 30px;
        right: 30px;
        background-color: #25d366;
        color: white;
        border-radius: 50%;
        text-align: center;
        font-size: 2rem;
        box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        z-index: 1000;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        animation: pulse 2s infinite;
    }

    .whatsapp-float:hover {
        background-color: #128c7e;
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
        color: white;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        }
        50% {
            box-shadow: 0 4px 30px rgba(37, 211, 102, 0.7);
        }
        100% {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        }
    }

    /* ======================================================= */
    /*                    RESPONSIVE                           */
    /* ======================================================= */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .contact-form-container,
        .contact-info-container {
            padding: 25px;
        }

        .whatsapp-float {
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
            font-size: 1.7rem;
        }

        .form-title {
            font-size: 1.5rem;
        }
    }

    /* ALERTES */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-left: 5px solid #10b981;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        border-left: 5px solid #ef4444;
    }
</style>
@endsection
