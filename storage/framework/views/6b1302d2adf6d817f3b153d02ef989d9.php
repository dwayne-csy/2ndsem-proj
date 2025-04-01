

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="<?php echo e(route('admin.supplier.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Suppliers
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Import Suppliers</h2>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.suppliers.import.process')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="file">Select Excel File</label>
                    <input type="file" class="form-control-file" id="file" name="file" required>
                    <small class="form-text text-muted">
                        Supported formats: .xlsx, .xls, .csv
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-import"></i> Import
                </button>
            </form>

            <div class="mt-4">
                <h5>File Format Example:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>brand_name</th>
                            <th>email</th>
                            <th>phone</th>
                            <th>address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Example Brand</td>
                            <td>supplier@example.com</td>
                            <td>1234567890</td>
                            <td>123 Main Street</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/supplier/import.blade.php ENDPATH**/ ?>