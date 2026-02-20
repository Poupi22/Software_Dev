@extends('home.layouts.app')
<link rel="stylesheet" href="{{ asset('home/assets/css/home.css') }}">
<link rel="stylesheet" href="{{ asset('home/assets/css/contact.css') }}">

@section('styles')
    <style>
        /* Message Styles */
        .message-success {
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid hsl(142 76% 36% / 0.3);
            background: hsl(142 76% 36% / 0.1);
            color: hsl(142 76% 36%);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .message-error {
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid hsl(0 72% 51% / 0.3);
            background: hsl(0 72% 51% / 0.1);
            color: hsl(0 72% 51%);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .message-success.show, .message-error.show {
            opacity: 1;
            transform: translateY(0);
        }
        .message-success i, .message-error i {
            font-size: 1.25rem;
        }
        @media (max-width: 768px) {
            .message-success, .message-error {
                font-size: 0.875rem;
                padding: 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Contact Hero Section -->
    <section class="hero">
        <div class="image-slideshow">
            @forelse(($slides ?? []) as $index => $slide)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ $slide->image1 ? Storage::url($slide->image1) : asset('home/assets/img/form.png') }}')">
                </div>
            @empty
                <!-- Fallback slides if no slides exist -->
                <div class="slide active" style="background-image: url('{{ asset('home/assets/img/form.png') }}')"></div>
            @endforelse
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Contactez nous</h1>
                <p class="hero-subtitle">
                    Contactez nous pour en savoir plus
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Nos Coordonnées</h2>
                    <p>Nous sommes disponibles pour répondre à toutes vos questions sur nos services et formations.</p>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Siège Social</h3>
                            <p>{{ $contact->address ?? 'Quartier Foto, Dschang, Cameroun' }}</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Téléphone</h3>
                            <p>{{ $contact->telephone ?? '+237 6 94 56 78 90' }}</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Email</h3>
                            <p>contact@soft-construction.org </p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Heures d'Ouverture</h3>
                            <p>Lundi - Vendredi: 7:30 - 17:30</p>
                            <p>Samedi: 8:00 - 13:00</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="{{ $contactInfo->facebook ?? '#' }}" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $contactInfo->twitter ?? '#' }}" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="{{ $contactInfo->linkedin ?? '#' }}" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="{{ $contactInfo->instagram ?? '#' }}" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h2>Envoyez-nous un Message</h2>
                    <form id="contactForm" method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label">Votre Nom Complet</label>
                            <input type="text" name="name" id="name" class="form-control" required placeholder="Entrez votre nom complet">
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Votre Email</label>
                            <input type="email" name="email" id="email" class="form-control" required placeholder="votre@email.com">
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Numéro de Téléphone</label>
                            <input type="tel" name="phone" id="phone" class="form-control" required placeholder="+237 6XX XXX XXX">
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label">Sujet</label>
                            <select name="subject" id="subject" class="form-control" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="construction">Demande de service construction</option>
                                <option value="renovation">Demande de service rénovation</option>
                                <option value="training">Demande d'information formation</option>
                                <option value="other">Autre demande</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Votre Message</label>
                            <textarea name="message" id="message" class="form-control" rows="5" required placeholder="Décrivez votre projet ou votre demande en détail..."></textarea>
                        </div>

                        <div id="formMessage"></div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Envoyer le Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.755238724279!2d-4.008950925686591!3d5.320857435064363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1eb7f6a5b5e9b%3A0x8df51a1a0e3b4211!2sDschang%2C%20Cameroun!5e0!3m2!1sfr!2sfr!4v1686754323456!5m2!1sfr!2sfr" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        title="Localisation de SoftConstruction">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Branches Section -->
    <section class="section" style="background: var(--gray-light);">
        <div class="container">
            <div class="section-title">
                <h2>Nos Implantations au Cameroun</h2>
                <p>Retrouvez-nous dans plusieurs villes du Cameroun</p>
            </div>
            
            <div class="branches-grid">
                <div class="branch-card">
                    <h3>Dschang (Siège)</h3>
                    <p> Quartier foto, immeuble passo</p>
                    <p><i class="fas fa-phone"></i> 237 693630574</p>
                </div>
                
                <div class="branch-card">
                    <h3>Douala</h3>
                    <p>#</p>
                    <p><i class="fas fa-phone"></i> 237 693630574</p>
                </div>
                
                <div class="branch-card">
                    <h3>Yaoundé</h3>
                    <p>#</p>
                    <p><i class="fas fa-phone"></i> 237 693630574</p>
                </div>
                
                <div class="branch-card">
                    <h3>Bafoussam</h3>
                    <p>#</p>
                    <p><i class="fas fa-phone"></i> 237 693630574</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = e.target;
            let formData = new FormData(form);
            let messageBox = document.getElementById('formMessage');
            let submitButton = form.querySelector('button[type="submit"]');

            // Clear previous messages and disable button
            messageBox.innerHTML = '';
            submitButton.disabled = true;
            submitButton.textContent = 'Envoi en cours...';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                messageBox.innerHTML = `<div class="message-success show"><i class="fas fa-check-circle"></i> ${data.message || 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'}</div>`;
                form.reset();
            })
            .catch(err => {
                messageBox.innerHTML = `<div class="message-error show"><i class="fas fa-exclamation-circle"></i> Une erreur est survenue, merci de réessayer.</div>`;
                console.error(err);
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Envoyer le Message';
                // Auto-clear message after 5 seconds
                setTimeout(() => {
                    messageBox.innerHTML = '';
                }, 5000);
            });
        });
    </script>
@endsection