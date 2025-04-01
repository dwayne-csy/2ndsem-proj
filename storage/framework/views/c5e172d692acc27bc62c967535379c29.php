<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('User Management')); ?></div>

                <div class="card-body">
                    <!-- Display any success messages -->
                    <?php if(session('status')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Users Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($user->name); ?></td>
                                    <td><?php echo e($user->email); ?></td>

                                    <!-- Status Dropdown -->
                                    <td>
                                        <form action="<?php echo e(route('admin.users.updateStatus', $user->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('POST'); ?>
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="active" <?php echo e($user->status === 'active' ? 'selected' : ''); ?>>Active</option>
                                                <option value="inactive" <?php echo e($user->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                            </select>
                                        </form>
                                    </td>

                                    <!-- Role Dropdown -->
                                    <td>
                                        <form action="<?php echo e(route('admin.users.updateRole', $user->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('POST'); ?>
                                            <select name="role" class="form-control" onchange="this.form.submit()">
                                                <option value="user" <?php echo e($user->role === 'user' ? 'selected' : ''); ?>>User</option>
                                                <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/users/index.blade.php ENDPATH**/ ?>