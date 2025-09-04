@extends('admin.layouts.app')
@vite('resources/css/app.css')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 glass-effect rounded-2xl shadow-2xl">
        <style>
            :root {
                --primary: #3b82f6; /* blue-500 */
                --accent: #1e3a8a;  /* blue-900 */
            }

            .gradient-text {
                background: linear-gradient(to right, var(--primary), var(--accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .glass-effect {
                background: rgba(255, 255, 255, 0.75);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.25);
            }

            .animate-float {
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }

            .animate-fade-in {
                animation: fadeIn 0.8s ease-in;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <!-- System Overview Section -->
        <section class="animate-fade-in">
            <h3 class="text-2xl sm:text-3xl font-bold gradient-text mb-6">System Overview</h3>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Students -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-blue-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 13a4 4 0 016.879 4.804M15 12a4 4 0 10-6 0m6 0v2a4 4 0 01-4 4H9a4 4 0 01-4-4v-2" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Students</div>
                    <div class="text-sm text-gray-500">0 Registered</div>
                </a>

                <!-- Teachers -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-green-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7a4 4 0 018 0v2a4 4 0 01-4 4H8a4 4 0 01-4-4V7a4 4 0 014-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2a4 4 0 00-4-4H4a4 4 0 00-4 4v2" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Teachers</div>
                    <div class="text-sm text-gray-500">0 Active</div>
                </a>

                <!-- Questions -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-purple-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 13h6M9 17h6M21 21H3V3h18v18z" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Questions</div>
                    <div class="text-sm text-gray-500">0 Total</div>
                </a>

                <!-- Quizzes -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-1a3 3 0 013-3h1m-1-3V7a2 2 0 012-2h2a2 2 0 012 2v3a2 2 0 01-2 2h-2l-1 1v3" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Quizzes</div>
                    <div class="text-sm text-gray-500">0 Published</div>
                </a>

                <!-- Training Sessions -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-yellow-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Training</div>
                    <div class="text-sm text-gray-500">0 Sessions</div>
                </a>

                <!-- Messages -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-pink-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Messages</div>
                    <div class="text-sm text-gray-500">0 Sent</div>
                </a>

                <!-- Notifications -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-indigo-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Notifications</div>
                    <div class="text-sm text-gray-500">0 Unread</div>
                </a>

                <!-- Courses -->
                <a href="#" class="block text-center p-5 rounded-xl bg-white border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="mb-2">
                        <svg class="w-8 h-8 text-teal-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9 6 9-6-9-4.5L3 6zm0 6l9 6 9-6" />
                        </svg>
                    </div>
                    <div class="text-xl font-semibold gradient-text mb-1">Courses</div>
                    <div class="text-sm text-gray-500">0 Offered</div>
                </a>
            </div>
        </section>

        <!-- Quick Stats Section -->
        <section class="mt-12 animate-fade-in">
            <h3 class="text-2xl sm:text-3xl font-bold gradient-text mb-6">Quick Stats</h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Products -->
                <div class="text-center p-6 rounded-xl bg-white border border-blue-100 shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="text-3xl sm:text-4xl font-bold gradient-text mb-2">0</div>
                    <div class="text-sm text-gray-600">Products</div>
                </div>

                <!-- Permissions -->
                <div class="text-center p-6 rounded-xl bg-white border border-blue-100 shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="text-3xl sm:text-4xl font-bold gradient-text mb-2">0</div>
                    <div class="text-sm text-gray-600">Permissions</div>
                </div>

                <!-- Roles -->
                <div class="text-center p-6 rounded-xl bg-white border border-blue-100 shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="text-3xl sm:text-4xl font-bold gradient-text mb-2">0</div>
                    <div class="text-sm text-gray-600">Roles</div>
                </div>

                <!-- Users -->
                <div class="text-center p-6 rounded-xl bg-white border border-blue-100 shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 animate-float">
                    <div class="text-3xl sm:text-4xl font-bold gradient-text mb-2">0</div>
                    <div class="text-sm text-gray-600">Users</div>
                </div>
            </div>
        </section>
    </div>
@endsection
