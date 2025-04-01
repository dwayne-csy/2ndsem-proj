

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Order History</h1>
        <div class="bg-primary p-2 rounded">
            <span class="text-white fw-bold">Total Orders: <?php echo e($orders->total()); ?></span>
        </div>
    </div>

    <?php if($orders->isEmpty()): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No Orders Yet</h3>
                <p class="text-muted">Your order history will appear here once you make purchases</p>
                <a href="<?php echo e(route('home')); ?>" class="btn btn-primary mt-3">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="align-middle">
                                <td class="ps-4 fw-bold">#<?php echo e(str_pad($order->id, 6, '0', STR_PAD_LEFT)); ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium"><?php echo e($order->created_at->format('M d, Y')); ?></span>
                                        <small class="text-muted"><?php echo e($order->created_at->format('h:i A')); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary rounded-pill me-2"><?php echo e($order->orderLines->sum('quantity')); ?></span>
                                        <span>items</span>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                                <td>
                                    <span class="badge rounded-pill py-2 px-3 
                                        <?php if($order->status === 'pending'): ?> bg-warning text-dark
                                        <?php elseif($order->status === 'accepted'): ?> bg-success
                                        <?php else: ?> bg-danger
                                        <?php endif; ?>">
                                        <i class="fas 
                                            <?php if($order->status === 'pending'): ?> fa-clock
                                            <?php elseif($order->status === 'accepted'): ?> fa-check-circle
                                            <?php else: ?> fa-times-circle
                                            <?php endif; ?> me-1"></i>
                                        <?php echo e(ucfirst($order->status)); ?>

                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </a>
                                        <?php if($order->status === 'accepted'): ?>
                                            <?php if($order->review): ?>
                                                <button class="btn btn-sm btn-info rounded-pill px-3" disabled>
                                                    <i class="fas fa-check me-1"></i> Reviewed
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-success rounded-pill px-3 review-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal"
                                                        data-order-id="<?php echo e($order->id); ?>">
                                                    <i class="fas fa-star me-1"></i> Review
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing <?php echo e($orders->firstItem()); ?> to <?php echo e($orders->lastItem()); ?> of <?php echo e($orders->total()); ?> orders
            </div>
            <div>
                <?php echo e($orders->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="reviewModalLabel">Rate Your Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm" method="POST" action="<?php echo e(route('reviews.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="order_id" id="order_id">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h6 class="mb-3">How was your experience?</h6>
                        <div class="rating-stars mb-2">
                            <i class="far fa-star fa-2x star" data-rating="1"></i>
                            <i class="far fa-star fa-2x star" data-rating="2"></i>
                            <i class="far fa-star fa-2x star" data-rating="3"></i>
                            <i class="far fa-star fa-2x star" data-rating="4"></i>
                            <i class="far fa-star fa-2x star" data-rating="5"></i>
                        </div>
                        <small class="text-muted" id="rating-text">Tap to rate</small>
                        <input type="hidden" name="rating" id="rating" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Your Review (Optional)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="What did you like or dislike?"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transition: background-color 0.2s ease;
    }
    .badge {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .rating-stars {
        font-size: 2rem;
        display: inline-block;
    }
    .rating-stars .star {
        cursor: pointer;
        margin: 0 5px;
        transition: all 0.2s;
        color: #e4e5e9;
    }
    .rating-stars .star.selected {
        color: #ffc107;
    }
    .rating-stars .star.hovered {
        color: #ffc107;
        transform: scale(1.1);
    }
    #rating-text.text-danger {
        color: #dc3545 !important;
    }
</style>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set order ID when review button is clicked
        const reviewButtons = document.querySelectorAll('.review-btn');
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        const ratingText = document.getElementById('rating-text');
        
        // Rating messages
        const ratingMessages = [
            "Tap to rate",
            "Poor",
            "Fair",
            "Good",
            "Very Good",
            "Excellent"
        ];

        // Initialize stars
        function resetStars() {
            stars.forEach(star => {
                star.classList.remove('selected', 'hovered');
                star.classList.add('far');
                star.classList.remove('fas');
            });
            ratingInput.value = "0";
            ratingText.textContent = ratingMessages[0];
            ratingText.classList.remove('text-danger');
        }

        // Handle review button click
        reviewButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('order_id').value = this.getAttribute('data-order-id');
                resetStars();
            });
        });

        // Handle star clicks
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                
                // Update all stars
                stars.forEach((s, index) => {
                    const starNum = parseInt(s.getAttribute('data-rating'));
                    if (starNum <= rating) {
                        s.classList.add('selected', 'fas');
                        s.classList.remove('far');
                    } else {
                        s.classList.remove('selected', 'fas');
                        s.classList.add('far');
                    }
                });
                
                // Update hidden input and text
                ratingInput.value = rating;
                ratingText.textContent = ratingMessages[rating];
            });

            // Hover effects
            star.addEventListener('mouseover', function() {
                const hoverRating = parseInt(this.getAttribute('data-rating'));
                stars.forEach(s => {
                    const starNum = parseInt(s.getAttribute('data-rating'));
                    s.classList.toggle('hovered', starNum <= hoverRating);
                });
            });

            star.addEventListener('mouseout', function() {
                stars.forEach(s => s.classList.remove('hovered'));
            });
        });

        // Reset stars when modal closes
        document.getElementById('reviewModal').addEventListener('hidden.bs.modal', resetStars);

        // Form validation
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            if (ratingInput.value === "0") {
                e.preventDefault();
                ratingText.textContent = "Please select a rating";
                ratingText.classList.add('text-danger');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/orders/history.blade.php ENDPATH**/ ?>