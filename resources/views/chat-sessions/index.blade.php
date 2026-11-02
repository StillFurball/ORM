<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Obrolan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <ul class="divide-y divide-gray-200">
                    @forelse ($chatSessions as $chatSession)
                        <li>
                            <a href="{{ route('chat-sessions.show', $chatSession) }}" class="flex items-center justify-between gap-4 p-6 hover:bg-gray-50">
                                <span class="font-medium text-gray-900">{{ $chatSession->title }}</span>
                                <span class="shrink-0 text-sm text-gray-500">{{ $chatSession->messages_count }} pesan</span>
                            </a>
                        </li>
                    @empty
                        <li class="p-6 text-gray-500">{{ __('Belum ada sesi obrolan.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
