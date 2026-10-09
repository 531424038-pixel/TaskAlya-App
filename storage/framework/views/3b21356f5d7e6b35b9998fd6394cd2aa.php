<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-bold text-biru-700">Pengaturan</h1>
        <p class="text-sm text-gray-500 mt-1">Atur preferensi aplikasi harianmu.</p>
    </div>

    <form method="POST" action="<?php echo e(route('settings.update')); ?>" class="card">
        <?php echo csrf_field(); ?>

        <h2 class="text-lg font-semibold text-biru-700 border-b pb-2 mb-4">Notifikasi</h2>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-biru-700">Aktifkan notifikasi</p>
                    <p class="text-sm text-gray-500">Tampilkan notifikasi saat pengingat kegiatan tiba.</p>
                </div>
                <input
                    type="checkbox"
                    name="notifications_enabled"
                    value="1"
                    <?php echo e($settings['notifications_enabled'] ? 'checked' : ''); ?>

                    class="w-5 h-5 rounded border-gray-300 text-biru-600 focus:ring-biru-200"
                >
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-biru-700">Aktifkan suara notifikasi</p>
                    <p class="text-sm text-gray-500">Putar suara saat ada notifikasi muncul.</p>
                </div>
                <input
                    type="checkbox"
                    name="sound_enabled"
                    value="1"
                    <?php echo e($settings['sound_enabled'] ? 'checked' : ''); ?>

                    class="w-5 h-5 rounded border-gray-300 text-biru-600 focus:ring-biru-200"
                >
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-semibold text-biru-700 border-b pb-2 mb-4">Default Pengingat</h2>
            <div class="flex flex-wrap gap-3">
                <?php $__currentLoopData = $reminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 text-sm border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:border-gray-700">
                        <input
                            type="radio"
                            name="reminder_default"
                            value="<?php echo e($reminder); ?>"
                            <?php echo e(($settings['reminder_default'] ?? 'Tidak ada') === $reminder ? 'checked' : ''); ?>

                            class="w-4 h-4 border-gray-300 text-biru-600 focus:ring-biru-200"
                        >
                        <?php echo e($reminder); ?>

                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-semibold text-biru-700 border-b pb-2 mb-4">Tema Aplikasi</h2>
            <div class="flex flex-wrap gap-3">
                <label class="flex items-center gap-2 text-sm border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:border-gray-700">
                    <input
                        type="radio"
                        name="theme"
                        value="biru"
                        <?php echo e(($settings['theme'] ?? 'biru') === 'biru' ? 'checked' : ''); ?>

                        class="w-4 h-4 border-gray-300 text-biru-600 focus:ring-biru-200"
                    >
                    Biru
                </label>
                <label class="flex items-center gap-2 text-sm border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:border-gray-700">
                    <input
                        type="radio"
                        name="theme"
                        value="light"
                        <?php echo e(($settings['theme'] ?? 'biru') === 'light' ? 'checked' : ''); ?>

                        class="w-4 h-4 border-gray-300 text-biru-600 focus:ring-biru-200"
                    >
                    Light
                </label>
                <label class="flex items-center gap-2 text-sm border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:border-gray-700">
                    <input
                        type="radio"
                        name="theme"
                        value="dark"
                        <?php echo e(($settings['theme'] ?? 'biru') === 'dark' ? 'checked' : ''); ?>

                        class="w-4 h-4 border-gray-300 text-biru-600 focus:ring-biru-200"
                    >
                    Gelap
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-2">
            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/settings.blade.php ENDPATH**/ ?>