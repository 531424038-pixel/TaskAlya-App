<?php $__env->startSection('title', 'Riwayat'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-biru-700">Riwayat Kegiatan</h1>
        <span class="text-sm text-gray-500"><?php echo e($tasks->count()); ?> kegiatan selesai</span>
    </div>

    <?php if($tasks->isEmpty()): ?>
        <div class="card">
            <p class="text-gray-500">Belum ada kegiatan yang selesai.</p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="space-y-3">
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 dark:border-gray-800">
                        <span class="text-xl">✅</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-biru-700 truncate"><?php echo e($task->name); ?></p>
                            <p class="text-sm text-gray-500">
                                📅 <?php echo e(\Carbon\Carbon::parse($task->date)->translatedFormat('j F Y')); ?> |
                                🕐 <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('H:i')); ?> -
                                <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('H:i')); ?> |
                                🏷️ <?php echo e($task->category); ?> |
                                ⚡ <?php echo e($task->priority); ?>

                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/tasks/history.blade.php ENDPATH**/ ?>