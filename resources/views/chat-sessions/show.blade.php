<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('chat-sessions.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; {{ __('Semua sesi') }}</a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $chatSession->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @foreach ($chatSession->messages as $message)
                <article class="bg-white shadow-sm sm:rounded-lg p-6 {{ $message->role === 'user' ? 'border-l-4 border-indigo-500' : 'border-l-4 border-emerald-500' }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $message->role }}</p>
                    <p class="mt-2 text-gray-900">{{ $message->content }}</p>

                    @if ($message->toolExecutionLogs->isNotEmpty())
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-left text-gray-500">
                                    <tr>
                                        <th class="py-2 pr-4 font-medium">Agent</th>
                                        <th class="py-2 pr-4 font-medium">Alat</th>
                                        <th class="py-2 pr-4 font-medium">Status</th>
                                        <th class="py-2 font-medium text-right">Durasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($message->toolExecutionLogs as $log)
                                        <tr>
                                            <td class="py-2 pr-4">
                                                {{ $log->loggable->name }}
                                                <span class="text-gray-400">({{ class_basename($log->loggable_type) }})</span>
                                            </td>
                                            <td class="py-2 pr-4 font-mono">{{ $log->tool_name }}</td>
                                            <td class="py-2 pr-4 {{ $log->status === 'success' ? 'text-emerald-700' : 'text-red-700' }}">{{ $log->status }}</td>
                                            <td class="py-2 text-right tabular-nums">{{ $log->duration_ms }} ms</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</x-app-layout>
