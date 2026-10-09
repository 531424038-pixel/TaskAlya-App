<?php $__env->startSection('title', 'Kalender'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-biru-700">Kalender Kegiatan</h1>
            <p class="text-sm text-gray-500 mt-1">
                <?php echo e($month); ?>

                <?php if($today): ?>
                    <span class="text-sm text-gray-400">| Hari ini: <?php echo e($today); ?></span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-gray-500 mb-2">
            <?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-2"><?php echo e($day); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="grid grid-cols-7 gap-1">
            <?php $__currentLoopData = $weeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $week; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="relative min-h-[120px] p-2 rounded-lg border transition hover:bg-gray-50 <?php echo e($day['isCurrentMonth'] ? 'border-gray-200' : 'border-transparent opacity-40'); ?> <?php echo e($day['isToday'] ? 'ring-2 ring-biru-400' : ''); ?>"
                    >
                        <span class="text-sm font-medium <?php echo e($day['isToday'] ? 'text-biru-700 font-bold' : ''); ?>">
                            <?php echo e(\Carbon\Carbon::parse($day['date'])->format('j')); ?>

                        </span>

                        <?php if($tasks->has($day['date'])): ?>
                            <div class="mt-2 space-y-1 min-h-[40px]">
                                <?php $__currentLoopData = $tasks->get($day['date']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="text-xs px-2 py-1 rounded bg-biru-100 text-biru-700 truncate <?php echo e($task->status === 'sedang dikerjakan' ? 'bg-amber-100 text-amber-700' : ''); ?> <?php echo e($task->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ''); ?>">
                                        <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('H:i')); ?> <?php echo e($task->name); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="card">
        <h2 class="text-sm font-semibold text-gray-500 mb-3">Ringkasan Kegiatan Berdasarkan Tanggal</h2>
        <div class="flex flex-col gap-2">
            <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between text-sm py-1 border-b border-gray-100 last:border-0 dark:border-gray-800">
                    <span><?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('j F')); ?></span>
                    <span class="flex items-center gap-2">
                        <span class="text-biru-600 font-medium"><?php echo e($summary['todos'] ?? 0); ?></span>
                        <span class="text-xs text-gray-400">belum selesai</span>
                        <?php if(isset($summary['done'])): ?>
                            <span class="text-xs text-emerald-600">• <?php echo e($summary['done']); ?> selesai</span>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if(count($activities) === 0): ?>
                <p class="text-sm text-gray-500">Belum ada aktivitas kegiatan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/calendar.blade.php ENDPATH**/ ?>