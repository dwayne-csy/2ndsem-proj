<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="text-center mb-4">Welcome to StyleSphere</h1>

    <!-- Navigation and Cart Section -->
    <div class="d-flex justify-content-between mb-4">
        <!-- Hamburger Profile Menu -->
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-secondary" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>"><i class="fas fa-user-edit me-2"></i>Edit Profile</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('orders.history')); ?>"><i class="fas fa-history me-2"></i>Order History</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Shopping Cart -->
        <div>
            <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-info position-relative">
                <i class="fas fa-shopping-bag"></i>
                <?php if(auth()->user()->cart->count() > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo e(auth()->user()->cart->count()); ?>

                </span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <div class="row">
        <?php if($product->total() > 0): ?>
            <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <!-- Product Image Carousel -->
                        <?php if($p->images->count() > 0): ?>
                        <div id="productCarousel-<?php echo e($p->product_id); ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $p->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                         class="d-block w-100 card-img-top" 
                                         style="height: 300px; object-fit: cover;" 
                                         alt="<?php echo e($p->product_name); ?>">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if($p->images->count() > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel-<?php echo e($p->product_id); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel-<?php echo e($p->product_id); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($p->product_name); ?></h5>
                            <p class="card-text"><strong>Supplier:</strong> <?php echo e($p->supplier->brand_name ?? 'No Supplier'); ?></p>
                            <p class="card-text"><strong>Price:</strong> $<?php echo e(number_format($p->sell_price, 2)); ?></p>
                            <p class="card-text"><strong>Stock:</strong> <?php echo e($p->stock); ?></p>
                            
                            <!-- Product Rating Display -->
                            <?php if($p->reviews->count() > 0): ?>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <?php
                                            $avgRating = $p->reviews->avg('rating');
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                        ?>
                                        
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $fullStars): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="ms-2"><?php echo e(number_format($avgRating, 1)); ?> (<?php echo e($p->reviews->count()); ?> reviews)</span>
                                    </div>
                                    
                                    <!-- Show user's review if exists -->
                                    <?php if(auth()->check() && $p->userReview(auth()->id())): ?>
                                        <?php $userReview = $p->userReview(auth()->id()); ?>
                                        <div class="bg-light p-2 rounded">
                                            <div class="d-flex justify-content-between">
                                                <strong>Your Review:</strong>
                                                <div>
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo e($i <= $userReview->rating ? 'text-warning' : 'text-secondary'); ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <?php if($userReview->comment): ?>
                                                <p class="mb-0 mt-1">"<?php echo e($userReview->comment); ?>"</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="mb-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($p->product_id); ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                            
                            <!-- Review Button (only for authenticated users) -->
                            <?php if(auth()->guard()->check()): ?>
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal-<?php echo e($p->product_id); ?>">
                                    <i class="fas fa-star me-1"></i>
                                    <?php echo e($p->userReview(auth()->id()) ? 'Edit Review' : 'Add Review'); ?>

                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Review Modal for each product -->
                <div class="modal fade" id="reviewModal-<?php echo e($p->product_id); ?>" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">Review <?php echo e($p->product_name); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo e(route('reviews.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($p->product_id); ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-stars">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="far fa-star star" data-rating="<?php echo e($i); ?>" style="cursor: pointer; font-size: 1.5rem; margin-right: 5px;"></i>
                                            <?php endfor; ?>
                                            <input type="hidden" name="rating" id="rating-<?php echo e($p->product_id); ?>" value="<?php echo e($p->userReview(auth()->id())->rating ?? 0); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment-<?php echo e($p->product_id); ?>" class="form-label">Review (optional)</label>
                                        <textarea class="form-control" id="comment-<?php echo e($p->product_id); ?>" name="comment" rows="3"><?php echo e($p->userReview(auth()->id())->comment ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">No products available.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($product->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($product->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize star rating functionality for each product
        document.querySelectorAll('.rating-stars .star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                const modalId = this.closest('.modal').id;
                const productId = modalId.split('-')[1];
                const ratingInput = document.getElementById(`rating-${productId}`);
                
                // Update selected rating
                ratingInput.value = rating;
                
                // Update star display
                const stars = this.parentElement.querySelectorAll('.star');
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('far');
                        s.classList.add('fas', 'text-warning');
                    } else {
                        s.classList.remove('fas', 'text-warning');
                        s.classList.add('far');
                    }
                });
            });
            
            // Initialize stars if editing existing review
            const modalId = star.closest('.modal').id;
            const productId = modalId.split('-')[1];
            const ratingInput = document.getElementById(`rating-${productId}`);
            if (ratingInput.value > 0) {
                const rating = parseInt(ratingInput.value);
                if (parseInt(star.getAttribute('data-rating')) <= rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'text-warning');
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/home.blade.php ENDPATH**/ ?>