<?php $__env->startSection('title', $task->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-bold text-biru-700"><?php echo e($task->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan'); ?></h1>
        <p class="text-sm text-gray-500 mt-1">Isi data kegiatan berikut ini.</p>
    </div>

    <form method="POST" action="<?php echo e($task->exists ? route('tasks.update', $task) : route('tasks.store')); ?>" class="card">
        <?php echo csrf_field(); ?>
        <?php if($task->exists): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan *</label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name', $task->name)); ?>" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                <select id="category" name="category" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Pilih kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category); ?>" <?php echo e(old('category', $task->category) == $category ? 'selected' : ''); ?>><?php echo e($category); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioritas *</label>
                <select id="priority" name="priority" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Pilih prioritas</option>
                    <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($priority); ?>" <?php echo e(old('priority', $task->priority) == $priority ? 'selected' : ''); ?>><?php echo e($priority); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="reminder" class="block text-sm font-medium text-gray-700 mb-1">Waktu Pengingat *</label>
                <select id="reminder" name="reminder" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                    <option value="Tidak ada" <?php echo e(old('reminder', $task->reminder) == 'Tidak ada' ? 'selected' : ''); ?>>Tidak ada</option>
                    <option value="Saat kegiatan dimulai" <?php echo e(old('reminder', $task->reminder) == 'Saat kegiatan dimulai' ? 'selected' : ''); ?>>Saat kegiatan dimulai</option>
                    <option value="5 menit sebelumnya" <?php echo e(old('reminder', $task->reminder) == '5 menit sebelumnya' ? 'selected' : ''); ?>>5 menit sebelumnya</option>
                    <option value="10 menit sebelumnya" <?php echo e(old('reminder', $task->reminder) == '10 menit sebelumnya' ? 'selected' : ''); ?>>10 menit sebelumnya</option>
                    <option value="15 menit sebelumnya" <?php echo e(old('reminder', $task->reminder) == '15 menit sebelumnya' ? 'selected' : ''); ?>>15 menit sebelumnya</option>
                    <option value="30 menit sebelumnya" <?php echo e(old('reminder', $task->reminder) == '30 menit sebelumnya' ? 'selected' : ''); ?>>30 menit sebelumnya</option>
                    <option value="1 jam sebelumnya" <?php echo e(old('reminder', $task->reminder) == '1 jam sebelumnya' ? 'selected' : ''); ?>>1 jam sebelumnya</option>
                </select>
                <?php $__errorArgs = ['reminder'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" id="date" name="date" value="<?php echo e(old('date', $task->date ? $task->date->format('Y-m-d') : now()->format('Y-m-d'))); ?>" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai *</label>
                <input type="time" id="start_time" name="start_time" value="<?php echo e(old('start_time', $task->start_time ? $task->start_time->format('H:i') : '')); ?>" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai *</label>
                <input type="time" id="end_time" name="end_time" value="<?php echo e(old('end_time', $task->end_time ? $task->end_time->format('H:i') : '')); ?>" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-danger mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="mt-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800"><?php echo e(old('description', $task->description)); ?></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <?php echo e($task->exists ? 'Simpan Perubahan' : 'Simpan Kegiatan'); ?>

            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/tasks/form.blade.php ENDPATH**/ ?>