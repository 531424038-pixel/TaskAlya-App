<?php $__env->startSection('title', 'Halaman Tidak Ditemukan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center text-center py-20">
    <span class="text-6xl">😕</span>
    <h1 class="text-2xl font-bold text-biru-700 mt-6">Halaman Tidak Ditemukan</h1>
    <p class="text-gray-500 mt-3 max-w-md">Maaf, halaman yang Anda cari belum tersedia atau telah dipindahkan.</p>
    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary mt-8">
        <span>←</span>
        <span>Kembali ke Dashboard</span>
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Task-app\resources\views/errors/404.blade.php ENDPATH**/ ?>