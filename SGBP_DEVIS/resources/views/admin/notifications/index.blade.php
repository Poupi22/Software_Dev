@extends('admin.layouts.app')
@section('title', 'Notifications')
@section('content')

    <div class="min-h-screen bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Notifications</h2>
                    @php $nonLues = $notifications->where('lu', false)->count(); @endphp
                    @if ($nonLues > 0)
                        <span
                            class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">{{ $nonLues }}</span>
                    @endif
                </div>
                @if ($notifications->where('lu', false)->count() > 0)
                    <form action="{{ route('admin.notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm flex items-center gap-1">
                            <span class="material-icons text-sm">done_all</span>
                            Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2"><span
                        class="material-icons">check_circle</span>{{ session('success') }}</p>
            </div>
        @endif

        <div class="p-4 md:p-8 max-w-3xl mx-auto">

            @forelse($notifications as $notif)
                <div
                    class="bg-white rounded-xl shadow-sm mb-3 overflow-hidden flex {{ $notif->lu ? 'opacity-70' : 'border-l-4 border-blue-500' }} hover:shadow-md transition">
                    <!-- Icône colorée -->
                    <div class="flex items-center justify-center w-16 bg-{{ $notif->couleur ?? 'blue' }}-50 flex-shrink-0">
                        <span
                            class="material-icons text-{{ $notif->couleur ?? 'blue' }}-500 text-2xl">{{ $notif->icone ?? 'notifications' }}</span>
                    </div>

                    <!-- Contenu -->
                    <div class="flex-1 p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 {{ $notif->lu ? '' : 'font-bold' }}">
                                    {{ $notif->titre }}</p>
                                <p class="text-sm text-gray-600 mt-0.5">{{ $notif->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if ($notif->lien)
                                    <a href="{{ $notif->lien }}" class="text-blue-600 hover:text-blue-800">
                                        <span class="material-icons text-sm">open_in_new</span>
                                    </a>
                                @endif
                                @if (!$notif->lu)
                                    <form action="{{ route('admin.notifications.read', $notif) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit" title="Marquer comme lu"
                                            class="text-gray-400 hover:text-green-600">
                                            <span class="material-icons text-sm">check_circle_outline</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-green-500" title="Lu le {{ $notif->lu_at?->format('d/m/Y H:i') }}">
                                        <span class="material-icons text-sm">check_circle</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20">
                    <span class="material-icons text-6xl text-gray-200">notifications_none</span>
                    <p class="text-gray-400 mt-4 text-lg">Aucune notification pour l'instant</p>
                    <p class="text-gray-300 text-sm">Vos notifications apparaîtront ici</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
