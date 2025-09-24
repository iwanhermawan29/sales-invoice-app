{{-- resources/views/admin/agents/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Verifikasi Agent</h2>

        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div id="toast-status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter Bar --}}
        <form id="filter-form" method="GET" class="grid gap-3 md:grid-cols-6">
            <div class="md:col-span-3">
                <label class="text-sm text-gray-600 dark:text-gray-300">Cari (nama / email / agency)</label>
                <input id="q-input" name="q" type="text" value="{{ $q ?? '' }}"
                    placeholder="Ketik kata kunci…"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 dark:text-gray-300">Status</label>
                <select name="status"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                    @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'Semua'] as $k => $v)
                        <option value="{{ $k }}" @selected(($status ?? 'pending') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">
                    Terapkan
                </button>
                <a href="{{ route('admin.agents.index') }}"
                    class="inline-flex justify-center rounded-xl border px-4 py-2 text-gray-700 dark:text-gray-200">
                    Reset
                </a>
            </div>
        </form>

        {{-- Card Tabel --}}
        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div
                class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-4 py-3 text-sm">
                <div class="text-gray-600 dark:text-gray-300">
                    Menampilkan <span class="font-semibold">{{ $users->firstItem() ?? 0 }}</span>–<span
                        class="font-semibold">{{ $users->lastItem() ?? 0 }}</span>
                    dari <span class="font-semibold">{{ $users->total() }}</span> data
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-gray-50 dark:bg-gray-800/70 backdrop-blur">
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Agency</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Kode Agent</th>
                            <th class="px-4 py-3">Disetujui</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($users as $u)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $u->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $u->id }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $u->email }}</td>
                                <td class="px-4 py-3">{{ $u->agency_name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $u->profile_status_color }}">
                                        {{ $u->profile_status_label }}
                                    </span>
                                    @if ($u->profile_approval_note)
                                        <div class="text-xs text-gray-500 mt-1">Catatan:
                                            {{ $u->profile_approval_note }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $u->kode_agent ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if ($u->profile_approved_at)
                                        <div>{{ $u->profile_approved_at->format('Y-m-d H:i') }}</div>
                                        <div class="text-xs text-gray-500">oleh: {{ $u->approver?->name ?? '-' }}</div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($u->profile_status === \App\Models\User::PROFILE_PENDING)
                                        <form action="{{ route('admin.agents.approve', $u) }}" method="POST"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button
                                                class="inline-flex items-center rounded-lg px-3 py-1 text-green-700 hover:bg-green-50">
                                                Setujui & Beri Kode
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.agents.reject', $u) }}" method="POST"
                                            class="inline js-reject-form ml-1">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="approval_note" value="">
                                            <button type="submit"
                                                class="inline-flex items-center rounded-lg px-3 py-1 text-amber-700 hover:bg-amber-50">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-800 px-4 py-3">
                    {{ $users->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SweetAlert2 untuk alasan penolakan + debounce search --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toast auto-hide
        (function() {
            const t = document.getElementById('toast-status');
            if (t) setTimeout(() => t.remove(), 4000);
        })();

        // Prompt alasan penolakan
        document.addEventListener('submit', async function(e) {
            const rejectForm = e.target.closest('.js-reject-form');
            if (rejectForm) {
                e.preventDefault();
                const {
                    value: note
                } = await Swal.fire({
                    title: 'Tolak profil agent?',
                    input: 'text',
                    inputLabel: 'Alasan (opsional)',
                    inputPlaceholder: 'Tulis alasan singkat…',
                    showCancelButton: true,
                    confirmButtonText: 'Tolak',
                    cancelButtonText: 'Batal'
                });
                if (note !== undefined) {
                    rejectForm.querySelector('input[name="approval_note"]').value = note || '';
                    rejectForm.submit();
                }
            }
        });

        // Debounce search
        (function() {
            const input = document.getElementById('q-input');
            const form = document.getElementById('filter-form');
            if (!input || !form) return;
            let timer = null;
            input.addEventListener('input', function() {
                if (timer) clearTimeout(timer);
                timer = setTimeout(() => form.submit(), 500);
            });
        })();
    </script>
</x-app-layout>
