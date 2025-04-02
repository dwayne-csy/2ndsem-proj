

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="card shadow-lg my-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0 fw-bold">
                <i class="fas fa-star me-2"></i> Review Details
            </h2>
            <div>
                <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Review Details -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h6 mb-0">Review Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Product</h5>
                                    <div class="d-flex align-items-center mt-3">
                                        <img src="<?php echo e($review->product->image_url); ?>" 
                                             alt="<?php echo e($review->product->product_name); ?>"
                                             class="rounded me-3" width="80" height="80" style="object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($review->product->product_name); ?></h6>
                                            <p class="small text-muted mb-1">
                                                <span class="badge bg-primary"><?php echo e($review->product->category); ?></span>
                                                <span class="badge bg-secondary ms-1"><?php echo e($review->product->types); ?></span>
                                            </p>
                                            <p class="small text-muted mb-0">$<?php echo e(number_format($review->product->sell_price, 2)); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Customer</h5>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <?php echo e(substr($review->user->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?php echo e($review->user->name); ?></h6>
                                            <p class="small text-muted mb-1"><?php echo e($review->user->email); ?></p>
                                            <p class="small text-muted mb-0">Member since <?php echo e($review->user->created_at->format('M Y')); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5 class="fw-bold">Rating</h5>
                                        <div class="star-rating fs-4">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-warning' : 'text-secondary'); ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2 fw-bold"><?php echo e($review->rating); ?>/5</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5 class="fw-bold">Review Date</h5>
                                        <p><?php echo e($review->created_at->format('F j, Y \a\t g:i A')); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5 class="fw-bold">Customer Feedback</h5>
                                <div class="p-3 bg-light rounded">
                                    <?php echo e($review->comment ?? 'No comment provided'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Actions & Meta -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h6 mb-0">Review Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?php if(!$review->is_approved): ?>
                                <form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                        <i class="fas fa-check-circle me-2"></i> Approve Review
                                    </button>
                                </form>
                                <?php else: ?>
                                <form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-warning w-100 mb-2">
                                        <i class="fas fa-times-circle me-2"></i> Reject Review
                                    </button>
                                </form>
                                <?php endif; ?>

                                <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt me-2"></i> Delete Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h3 class="h6 mb-0">Order Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="fw-bold">Order #<?php echo e($review->order->id); ?></h5>
                                <p class="mb-1">
                                    <span class="badge bg-<?php echo e($review->order->status === 'accepted' ? 'success' : 'warning'); ?>">
                                        <?php echo e(ucfirst($review->order->status)); ?>

                                    </span>
                                </p>
                                <p class="small text-muted mb-0">
                                    Placed on <?php echo e($review->order->created_at->format('M j, Y')); ?>

                                </p>
                            </div>
                            <div class="mb-3">
                                <h5 class="fw-bold">Total Amount</h5>
                                <p>$<?php echo e(number_format($review->order->total_amount, 2)); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Reviews -->
            <?php if($similarReviews->count() > 0): ?>
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h3 class="h6 mb-0">Other Reviews for This Product</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $similarReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($similar->user->name); ?></td>
                                    <td>
                                        <div class="star-rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo e($i <= $similar->rating ? 'text-warning' : 'text-secondary'); ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td><?php echo e(Str::limit($similar->comment, 30)); ?></td>
                                    <td><?php echo e($similar->created_at->format('M d, Y')); ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?php echo e($similar->is_approved ? 'success' : 'warning'); ?>">
                                            <?php echo e($similar->is_approved ? 'Approved' : 'Pending'); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .star-rating {
        color: #ffc107;
    }
    .avatar {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .card-header {
        background-color: #f8f9fa;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/reviews/show.blade.php ENDPATH**/ ?>