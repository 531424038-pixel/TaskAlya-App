<?php $__env->startSection('title', 'Detail Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6 max-w-3xl">
    <a href="<?php echo e(route('tasks.index')); ?>" class="text-sm text-biru-600 hover:underline">← Kembali ke daftar kegiatan</a>

    <div class="card">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-biru-700"><?php echo e($task->name); ?></h1>
                <?php if($task->description): ?>
                    <p class="text-gray-600 mt-2"><?php echo e($task->description); ?></p>
                <?php endif; ?>
            </div>
            <span class="status-badge <?php echo e($task->status === 'selesai' ? 'status-selesai' : ($task->status === 'sedang dikerjakan' ? 'status-sedang-dikerjakan' : 'status-belum-selesai')); ?>">
                <?php echo e(ucwords(str_replace(' ', '-', $task->status))); ?>

            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
            <div class="flex flex-col gap-3">
                <h2 class="text-sm font-semibold text-gray-500">Waktu</h2>
                <div class="flex flex-wrap gap-4 text-sm">
                    <span>📅 <?php echo e(\Carbon\Carbon::parse($task->date)->translatedFormat('j F Y')); ?></span>
                    <span>🕐 <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('H:i')); ?></span>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                <h2 class="text-sm font-semibold text-gray-500">Kategori & Prioritas</h2>
                <div class="flex flex-wrap gap-4 text-sm">
                    <span class="category-badge"><?php echo e($task->category); ?></span>
                    <span class="priority-badge <?php echo e('priority-' . $task->priority); ?>"><?php echo e($task->priority); ?></span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mt-6">
            <?php if($task->status !== 'selesai'): ?>
                <form method="POST" action="<?php echo e(route('tasks.markDone', $task)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary flex items-center gap-2">
                        <span class="text-lg">✅</span>
                        <span>Tandai Selesai</span>
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('tasks.markUndone', $task)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline flex items-center gap-2 text-emerald-600">
                        <span class="text-lg">🔄</span>
                        <span>Batalkan Selesai</span>
                    </button>
                </form>
            <?php endif; ?>
            <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-outline">Edit</a>
            <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus kegiatan ini?')">Hapus</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/tasks/show.blade.php ENDPATH**/ ?>