<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-biru-700">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kegiatan harianmu tanpa login.</p>
        </div>
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
            <span>+</span>
            <span>Tambah Kegiatan</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📅</span>
                <span class="text-sm text-gray-500">Hari Ini</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-biru-700"><?php echo e($tasksToday->count()); ?></div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <span class="text-sm text-gray-500">Selesai</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-emerald-600"><?php echo e($doneToday); ?></div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <span class="text-sm text-gray-500">Belum Selesai</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-amber-600"><?php echo e($todoToday); ?></div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📊</span>
                <span class="text-sm text-gray-500">Total</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-biru-700"><?php echo e($totalAll); ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-lg">Kegiatan Hari Ini</h2>
                <span class="text-sm text-gray-500"><?php echo e($today); ?></span>
            </div>

            <?php if($tasksToday->isEmpty()): ?>
                <div class="text-center py-10 text-gray-500">
                    <p class="text-lg">Belum ada kegiatan hari ini.</p>
                    <a href="<?php echo e(route('tasks.create')); ?>" class="mt-3 text-biru-600 text-sm font-medium underline underline-offset-2">Tambah kegiatan pertama</a>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $tasksToday; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col gap-3 rounded-lg border border-gray-100 p-4 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/40 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="font-semibold text-biru-700"><?php echo e($task->name); ?></h3>
                                <p class="text-sm text-gray-500">
                                    ⏰ <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('H:i')); ?> |
                                    🏷️ <?php echo e($task->category); ?> |
                                    ⚡ <?php echo e($task->priority); ?>

                                </p>
                            </div>
                            <div class="flex shrink-0 gap-2">
                                <form method="POST" action="<?php echo e(route('tasks.markDone', $task)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">Selesai</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="xl:col-span-1 card">
            <h2 class="font-semibold text-lg">Kegiatan Terdekat</h2>
            <p class="text-sm text-gray-500 mb-4">Kegiatan berikutnya yang akan masuk jam.</p>

            <?php if($dueSoon->isEmpty()): ?>
                <p class="text-sm text-gray-500">Belum ada kegiatan yang akan datang.</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $dueSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-biru-600"><?php echo e(\Carbon\Carbon::parse($task->start_time)->format('H:i')); ?></span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium truncate text-biru-700"><?php echo e($task->name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($task->category); ?> / <?php echo e($task->priority); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/dashboard.blade.php ENDPATH**/ ?>