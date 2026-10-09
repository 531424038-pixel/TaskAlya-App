<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::query()->orderBy('date', 'asc')->orderBy('start_time', 'asc');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tasks = $query->paginate(12)->withQueryString();
        $filters = $request->only(['search', 'status', 'priority', 'category']);

        $statuses = ['belum selesai', 'sedang dikerjakan', 'selesai'];
        $priorities = ['Rendah', 'Sedang', 'Tinggi'];
        $categories = ['Kuliah', 'Tugas', 'Pekerjaan', 'Pribadi', 'Olahraga', 'Lainnya'];

        return view('tasks.index', compact('tasks', 'filters', 'statuses', 'priorities', 'categories'));
    }

    public function create(): View
    {
        return view('tasks.form', [
            'task' => new Task,
            'categories' => ['Kuliah', 'Tugas', 'Pekerjaan', 'Pribadi', 'Olahraga', 'Lainnya'],
            'priorities' => ['Rendah', 'Sedang', 'Tinggi'],
            'reminders' => ['Tidak ada', 'Saat kegiatan dimulai', '5 menit sebelumnya', '10 menit sebelumnya', '15 menit sebelumnya', '30 menit sebelumnya', '1 jam sebelumnya'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'category' => ['required', 'in:Kuliah,Tugas,Pekerjaan,Pribadi,Olahraga,Lainnya'],
            'priority' => ['required', 'in:Rendah,Sedang,Tinggi'],
            'reminder' => ['required', 'in:Tidak ada,Saat kegiatan dimulai,5 menit sebelumnya,10 menit sebelumnya,15 menit sebelumnya,30 menit sebelumnya,1 jam sebelumnya'],
        ]);

        $data['reminder_at'] = $this->computeReminderAt($data);

        $task = Task::create($data);

        return redirect()->route('tasks.index')->with('success', "Kegiatan \"{$task->name}\" berhasil ditambahkan.");
    }

    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        return view('tasks.form', [
            'task' => $task,
            'categories' => ['Kuliah', 'Tugas', 'Pekerjaan', 'Pribadi', 'Olahraga', 'Lainnya'],
            'priorities' => ['Rendah', 'Sedang', 'Tinggi'],
            'reminders' => ['Tidak ada', 'Saat kegiatan dimulai', '5 menit sebelumnya', '10 menit sebelumnya', '15 menit sebelumnya', '30 menit sebelumnya', '1 jam sebelumnya'],
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'category' => ['required', 'in:Kuliah,Tugas,Pekerjaan,Pribadi,Olahraga,Lainnya'],
            'priority' => ['required', 'in:Rendah,Sedang,Tinggi'],
            'reminder' => ['required', 'in:Tidak ada,Saat kegiatan dimulai,5 menit sebelumnya,10 menit sebelumnya,15 menit sebelumnya,30 menit sebelumnya,1 jam sebelumnya'],
        ]);

        $data['reminder_at'] = $this->computeReminderAt($data);

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', "Kegiatan \"{$task->name}\" berhasil diperbarui.");
    }

    public function destroy(Task $task): RedirectResponse
    {
        $name = $task->name;
        $task->delete();

        return redirect()->route('tasks.index')->with('success', "Kegiatan \"{$name}\" berhasil dihapus.");
    }

    public function markDone(Task $task): RedirectResponse
    {
        $task->update(['status' => 'selesai']);

        return redirect()->back()->with('success', "Kegiatan \"{$task->name}\" ditandai selesai.");
    }

    public function markUndone(Task $task): RedirectResponse
    {
        $task->update(['status' => 'belum selesai']);

        return redirect()->back()->with('success', "Kegiatan \"{$task->name}\" ditandai belum selesai.");
    }

    public function dashboard(Request $request): View
    {
        $now = now();
        $today = $now->format('Y-m-d');

        $tasksToday = Task::where('date', $today)
            ->orderBy('start_time', 'asc')
            ->get();

        $startedToday = Task::where('date', $today)
            ->where('status', 'sedang dikerjakan')
            ->count();

        $dueSoon = Task::where('date', '>=', $today)
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'belum selesai')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', [
            'tasksToday' => $tasksToday,
            'today' => $now->translatedFormat('l, j F Y'),
            'startedToday' => $startedToday,
            'doneToday' => $tasksToday->where('status', 'selesai')->count(),
            'todoToday' => $tasksToday->where('status', 'belum selesai')->count(),
            'totalAll' => Task::count(),
            'totalDone' => Task::where('status', 'selesai')->count(),
            'totalTodo' => Task::where('status', 'belum selesai')->count(),
            'dueSoon' => $dueSoon,
        ]);
    }

    public function calendar(Request $request): View
    {
        $base = $request->input('date') ? $request->input('date') : now()->format('Y-m-d');
        $month = \Carbon\Carbon::parse($base)->format('Y-m');

        $tasks = Task::whereMonth('date', \Carbon\Carbon::parse($base)->month)
            ->whereYear('date', \Carbon\Carbon::parse($base)->year)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('date');

        $firstDay = \Carbon\Carbon::parse($base)->startOfMonth();
        $lastDay = \Carbon\Carbon::parse($base)->endOfMonth();
        $today = now()->format('Y-m-d');

        $weekDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $weeks = [];

        // Sejajarkan tanggal 1 di bawah hari yang benar (0 = Minggu);
        // hari sebelum tanggal 1 diisi bingkai bulan sebelumnya.
        $current = $firstDay->copy()->subDays((int) $firstDay->dayOfWeek);

        while ($current->lte($lastDay)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = [
                    'date' => $current->format('Y-m-d'),
                    'isCurrentMonth' => $current->format('Y-m') === $month,
                    'isToday' => $current->format('Y-m-d') === $today,
                    'tasks' => collect(),
                ];
                $current->addDay();
            }
            $weeks[] = $week;
        }

        return view('calendar', [
            'tasks' => $tasks,
            'today' => \Carbon\Carbon::parse($today)->translatedFormat('l, j F'),
            'month' => \Carbon\Carbon::parse($base)->translatedFormat('F Y'),
            'weeks' => $weeks,
            'weekDays' => $weekDays,
            'activities' => $this->getCalendarActivitySummary($base),
            'dates' => $weeks,
        ]);
    }

    public function history(): View
    {
        $tasks = Task::where('status', 'selesai')
            ->orderBy('id', 'desc')
            ->get();

        return view('tasks.history', compact('tasks'));
    }

    public function settings(): View
    {
        // Preferensi disimpan di session oleh updateSettings().
        $settings = array_merge([
            'notifications_enabled' => true,
            'sound_enabled' => true,
            'reminder_default' => 'Tidak ada',
            'theme' => 'biru',
        ], session('settings', []));

        return view('settings', [
            'settings' => $settings,
            'categories' => ['Kuliah', 'Tugas', 'Pekerjaan', 'Pribadi', 'Olahraga', 'Lainnya'],
            'priorities' => ['Rendah', 'Sedang', 'Tinggi'],
            'reminders' => ['Tidak ada', 'Saat kegiatan dimulai', '5 menit sebelumnya', '10 menit sebelumnya', '15 menit sebelumnya', '30 menit sebelumnya', '1 jam sebelumnya'],
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'notifications_enabled' => ['boolean'],
            'sound_enabled' => ['boolean'],
            'reminder_default' => ['sometimes', 'in:Tidak ada,Saat kegiatan dimulai,5 menit sebelumnya,10 menit sebelumnya,15 menit sebelumnya,30 menit sebelumnya,1 jam sebelumnya'],
            'theme' => ['sometimes', 'in:biru,light,dark'],
        ]);

        $settings = [
            'notifications_enabled' => $request->boolean('notifications_enabled'),
            'sound_enabled' => $request->boolean('sound_enabled'),
            'reminder_default' => $request->input('reminder_default', 'Tidak ada'),
            'theme' => $request->input('theme', 'biru'),
        ];

        // Simpan ke session agar tetap tersimpan setelah redirect.
        session(['settings' => $settings]);

        return redirect()->route('settings')->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Hitung waktu pengingat dari jadwal kegiatan (bukan dari "sekarang"),
     * mis. "10 menit sebelumnya" = waktu mulai - 10 menit.
     */
    private function computeReminderAt(array $data): ?string
    {
        if (($data['reminder'] ?? 'Tidak ada') === 'Tidak ada') {
            return null;
        }

        $offsets = [
            'Saat kegiatan dimulai' => 0,
            '5 menit sebelumnya' => 5,
            '10 menit sebelumnya' => 10,
            '15 menit sebelumnya' => 15,
            '30 menit sebelumnya' => 30,
            '1 jam sebelumnya' => 60,
        ];

        $minutes = $offsets[$data['reminder']] ?? 0;
        $start = \Carbon\Carbon::parse("{$data['date']} {$data['start_time']}");

        return $start->copy()->subMinutes($minutes)->format('Y-m-d H:i:s');
    }

    private function getCalendarActivitySummary(string $date): array
    {
        $month = \Carbon\Carbon::parse($date)->format('Y-m');

        $todos = DB::table('tasks')
            ->selectRaw('DATE(date) as activity_date, count(*) as total')
            ->whereMonth('date', \Carbon\Carbon::parse($date)->month)
            ->whereYear('date', \Carbon\Carbon::parse($date)->year)
            ->where('status', '!=', 'selesai')
            ->groupBy('activity_date')
            ->pluck('total', 'activity_date');

        $done = DB::table('tasks')
            ->selectRaw('DATE(date) as activity_date, count(*) as total')
            ->whereMonth('date', \Carbon\Carbon::parse($date)->month)
            ->whereYear('date', \Carbon\Carbon::parse($date)->year)
            ->where('status', 'selesai')
            ->groupBy('activity_date')
            ->pluck('total', 'activity_date');

        $merged = [];
        $todos = $todos->mapWithKeys(fn ($v, $k) => [$k => (int) $v]);
        $done = $done->mapWithKeys(fn ($v, $k) => [$k => (int) $v]);

        foreach ($todos->keys() as $key) {
            $merged[$key] = ['todos' => $todos[$key] ?? 0, 'done' => $done[$key] ?? 0];
        }

        foreach ($done->keys() as $key) {
            if (!isset($merged[$key])) {
                $merged[$key] = ['todos' => 0, 'done' => $done[$key]];
            }
        }

        return $merged;
    }
}
