<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button and Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="mb-0">Your Cart</h1>
        <div></div> <!-- Empty div for spacing balance -->
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php if($cartItems->isEmpty()): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e($cartProduct->product->product_name); ?> (ID: <?php echo e($cartProduct->product->product_id); ?>)
                            <?php if($cartProduct->product->images->isNotEmpty()): ?>
                                <img src="<?php echo e(asset('storage/' . $cartProduct->product->images->first()->image_path)); ?>" 
                                     class="img-thumbnail" 
                                     width="80"
                                     style="object-fit: cover; height: 80px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?php echo e(route('cart.update', $cartProduct->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="number" name="quantity" value="<?php echo e($cartProduct->quantity); ?>" min="1" class="form-control" style="width: 80px;">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td>$<?php echo e(number_format($cartProduct->product->sell_price, 2)); ?></td>
                        <td>$<?php echo e(number_format($cartProduct->quantity * $cartProduct->product->sell_price, 2)); ?></td>
                        <td>
                            <form action="<?php echo e(route('cart.remove', $cartProduct->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <h3>Total: $<?php echo e(number_format($cartItems->sum(function($cartProduct) { 
                return $cartProduct->quantity * $cartProduct->product->sell_price; 
            }), 2)); ?></h3>
            <form action="<?php echo e(route('cart.checkout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success btn-lg">Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Font Awesome for the arrow icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/cart/index.blade.php ENDPATH**/ ?>