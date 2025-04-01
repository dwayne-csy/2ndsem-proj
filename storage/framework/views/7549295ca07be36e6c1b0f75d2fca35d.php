

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="<?php echo e(route('admin.product.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Import Products</h2>
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

            <form action="<?php echo e(route('admin.product.import.submit')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="supplier">Select Supplier</label>
                    <select class="form-control" id="supplier" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <?php $__currentLoopData = $supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supplier->supplier_id); ?>"><?php echo e($supplier->brand_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="form-group">
        <label for="file">Select Excel File</label>
        <input type="file" class="form-control-file" id="file" name="file" required>
        <small class="form-text text-muted">
            Supported formats: .xlsx, .xls, .csv
        </small>
    </div>
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-file-import"></i> Import
                </button>
            </form>

            <div class="mt-4">
                <h5>File Format Example (for selected supplier):</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>product_name</th>
                            <th>size</th>
                            <th>category</th>
                            <th>types</th>
                            <th>description</th>
                            <th>cost_price</th>
                            <th>sell_price</th>
                            <th>stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dino Shirt</td>
                            <td>M</td>
                            <td>Men</td>
                            <td>T-Shirt</td>
                            <td>100% Cotton</td>
                            <td>50.00</td>
                            <td>79.99</td>
                            <td>100</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> 
                    <ul>
                        <li>All imported products will be assigned to the selected supplier</li>
                        <li>Sell price must be greater than or equal to cost price</li>
                        <li>Ensure categories match your existing product categories</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/product/import.blade.php ENDPATH**/ ?>