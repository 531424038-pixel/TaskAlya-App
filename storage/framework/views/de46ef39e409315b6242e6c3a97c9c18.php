<?php $__env->startSection('title', 'Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-2xl font-bold text-biru-700">Daftar Kegiatan</h1>
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
            <span>+</span>
            <span>Tambah Kegiatan</span>
        </a>
    </div>

    <div class="card">
        <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari kegiatan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                    <input
                        id="search"
                        name="search"
                        value="<?php echo e($filters['search'] ?? ''); ?>"
                        placeholder="Cari nama kegiatan..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-biru-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <select name="status" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php echo e(($filters['status'] ?? '') === $status ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace(' ', '', $status))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="priority" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Prioritas</option>
                    <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($priority); ?>" <?php echo e(($filters['priority'] ?? '') === $priority ? 'selected' : ''); ?>><?php echo e($priority); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="category" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category); ?>" <?php echo e(($filters['category'] ?? '') === $category ? 'selected' : ''); ?>><?php echo e($category); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-biru-600 text-white rounded-lg text-sm font-semibold hover:bg-biru-700 transition">Filter</button>
                <?php if($filters): ?>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card rounded-xl p-5 flex flex-col gap-3 <?php echo e($task->status === 'sedang dikerjakan' ? 'border-biru-200 bg-biru-50/40' : ''); ?>">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-biru-700 truncate"><?php echo e($task->name); ?></h3>
                        <?php if($task->description): ?>
                            <p class="text-sm text-gray-500 line-clamp-2 mt-1"><?php echo e($task->description); ?></p>
                        <?php endif; ?>
                    </div>
                    <span class="status-badge <?php echo e($task->status === 'selesai' ? 'status-selesai' : ($task->status === 'sedang dikerjakan' ? 'status-sedang-dikerjakan' : 'status-belum-selesai')); ?>">
                        <?php echo e(ucwords(str_replace(' ', '-', $task->status))); ?>

                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1">🕐 <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('H:i')); ?></span>
                    <span class="flex items-center gap-1">📅 <?php echo e(\Carbon\Carbon::parse($task->date)->format('j F Y')); ?></span>
                    <span class="category-badge"><?php echo e($task->category); ?></span>
                    <span class="priority-badge <?php echo e('priority-' . $task->priority); ?>"><?php echo e($task->priority); ?></span>
                </div>

                <div class="flex items-center gap-2 mt-auto">
                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-sm text-biru-600 hover:text-biru-800 font-medium">Detail</a>
                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="text-sm text-gray-500 hover:text-biru-600 font-medium">Edit</a>
                    <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>" class="ml-auto">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-sm text-gray-400 hover:text-danger transition" onclick="return confirm('Hapus kegiatan ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-1 md:col-span-3 card">
                <p class="text-gray-500">Belum ada kegiatan.</p>
                <a href="<?php echo e(route('tasks.create')); ?>" class="mt-3 text-biru-600 text-sm font-medium">Buat kegiatan pertama</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($tasks->hasPages()): ?>
        <div class="pagination mt-4">
            <?php echo e($tasks->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/tasks/index.blade.php ENDPATH**/ ?>