<?php
include 'includes/header.php';
include 'includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = (int) $_GET['id'];

/* FETCH ORDER */
$orderQ = mysqli_query($conn, "
  SELECT * FROM orders WHERE id = $order_id LIMIT 1
");
$order = mysqli_fetch_assoc($orderQ);

if (!$order) {
    echo "<p>Order not found</p>";
    exit;
}

/* FETCH ITEMS */
$itemsQ = mysqli_query($conn, "
  SELECT * FROM order_items WHERE order_id = $order_id
");
?>

<div class="admin-content ">

<div class="">

    <h1>Order #<?= $order['id'] ?></h1>

    <!-- CUSTOMER INFO -->
    <div class="order-box">
        <h3>Customer Details</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
    </div>

    <!-- ITEMS -->
    <div class="order-box">
        <h3>Order Items</h3>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($itemsQ)): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>₹<?= number_format($item['price']) ?></td>
                        <td>₹<?= number_format($item['price'] * $item['qty']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- SUMMARY -->
    <div class="order-box order-summary">
        <h3>Order Summary</h3>
        <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_amount']) ?></p>
        <p><strong>Status:</strong> <?= $order['status'] ?></p>
        <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
    </div>

</div>

<!-- STATUS UPDATE FORM -->
<div>
<form method="post" action="order-status-update.php" class="status-form">
    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

    <label>Update Status</label>
    <select name="status" required>
        <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
        <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
        <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <button type="submit" class="btn-primary mt-2">
        Update Status
    </button>
</form>
</div>

</div>


<?php include 'includes/footer.php'; ?>