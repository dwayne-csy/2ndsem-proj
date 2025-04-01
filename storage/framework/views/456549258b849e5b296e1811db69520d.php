<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Suppliers</h1>
        <div>
            <a href="<?php echo e(route('admin.supplier.create')); ?>" class="btn btn-success">Add Supplier</a>
            <a href="<?php echo e(route('admin.suppliers.import')); ?>" class="btn btn-primary ml-2">
                <i class="fas fa-file-import"></i> Import
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Brand Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($supplier->supplier_id); ?></td>
                    <td><?php echo e($supplier->brand_name); ?></td>
                    <td><?php echo e($supplier->email); ?></td>
                    <td><?php echo e($supplier->phone); ?></td>
                    <td><?php echo e($supplier->address); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.supplier.edit', $supplier->supplier_id)); ?>" class="btn btn-primary">Edit</a>
                        <form action="<?php echo e(route('admin.supplier.destroy', $supplier->supplier_id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/supplier/index.blade.php ENDPATH**/ ?>