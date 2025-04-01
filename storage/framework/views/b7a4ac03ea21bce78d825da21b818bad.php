<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="<?php echo e(route('admin.product.import')); ?>" class="btn btn-primary ml-2">
                <i class="fas fa-file-import"></i> Import
            </a>
        </div>
        <h1 class="mb-0">Product Management</h1>
        <a href="<?php echo e(route('admin.product.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Supplier</th>
                            <th>Details</th>
                            <th>Pricing</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <?php if($productItem->images->isNotEmpty()): ?>
                                        <img src="<?php echo e(asset('storage/' . $productItem->images->first()->image_path)); ?>" 
                                             alt="<?php echo e($productItem->product_name); ?>" 
                                             class="img-thumbnail" 
                                             width="80"
                                             style="object-fit: cover; height: 80px;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($productItem->product_name); ?></strong>
                                    <div class="small text-muted">ID: <?php echo e($productItem->product_id); ?></div>
                                </td>
                                <td>
                                    <?php if($productItem->supplier): ?>
                                        <?php echo e($productItem->supplier->brand_name); ?>

                                    <?php else: ?>
                                        <span class="text-danger">No Supplier</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                <span class="badge bg-light text-dark border"><?php echo e($productItem->size); ?></span>
                                <span class="badge bg-light text-dark border"><?php echo e($productItem->category); ?></span>
                                <span class="badge bg-light text-dark border"><?php echo e($productItem->types); ?></span>
                                </td>
                                <td>
                                    <div>Cost: <strong>$<?php echo e(number_format($productItem->cost_price, 2)); ?></strong></div>
                                    <div>Sell: <strong>$<?php echo e(number_format($productItem->sell_price, 2)); ?></strong></div>
                                    <div class="small text-<?php echo e($productItem->sell_price > $productItem->cost_price ? 'success' : 'danger'); ?>">
                                        Margin: $<?php echo e(number_format($productItem->sell_price - $productItem->cost_price, 2)); ?>

                                    </div>
                                </td>
                                <td>
                                <span class="badge badge-<?php echo e($productItem->stock > 10 ? 'success' : ($productItem->stock > 0 ? 'warning' : 'danger')); ?> text-dark">
                                    <?php echo e($productItem->stock); ?> in stock
                                </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="<?php echo e(route('admin.product.edit', $productItem->product_id)); ?>" 
                                           class="btn btn-sm btn-primary mb-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="<?php echo e(route('admin.product.destroy', $productItem->product_id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">No products found.</div>
                                    <a href="<?php echo e(route('admin.product.create')); ?>" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Add Your First Product
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($product->hasPages()): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($product->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .img-thumbnail {
        transition: transform 0.2s;
    }
    .img-thumbnail:hover {
        transform: scale(1.5);
        z-index: 10;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    .badge {
        font-size: 0.8rem;
        font-weight: normal;
        color: black; /* Added this line to make badge text black */
        background-color: #f8f9fa; /* Light gray background for better contrast */
        border: 1px solid #dee2e6; /* Subtle border */
        margin: 2px; /* Slight spacing between badges */
    }
    .badge-info {
        background-color: #e2f4ff; /* Lighter blue for info */
    }
    .badge-secondary {
        background-color: #f0f0f0; /* Lighter gray for secondary */
    }
    .badge-primary {
        background-color: #e6f2ff; /* Lighter blue for primary */
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/product/index.blade.php ENDPATH**/ ?>