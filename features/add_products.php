<?php
session_start();
include "../conn/connection.php";

$query = "SELECT p.*, CASE WHEN ap.prod_id IS NOT NULL THEN 1 ELSE 0 END as is_archived 
         FROM products p 
         LEFT JOIN archive_products ap ON p.prod_id = ap.prod_id";
$result = mysqli_query($con, $query);

if (!$result) {
    die('Query Failed' . mysqli_error($con));
}

$categories_query = "SELECT * FROM categories ORDER BY category_name ASC";
$categories_result = mysqli_query($con, $categories_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-[#FFF0DC]">
    <!-- Topbar -->
    <div class="relative z-50">
        <?php include '../features/component/topbar.php'; ?>
    </div>
    <!-- Sidebar -->
    <div class="relative z-70">
        <?php include '../features/component/sidebar.php'; ?>
    </div>
    <!-- Main content -->
    <main class="ml-[230px] mt-[171px] p-6">
        <div class="flex flex-col  justify-between items-start mb-6">
            <h1 class="text-2xl font-bold mb-4">Products</h1>
            <button onclick="window.location.href='../endpoint/add_product_button.php'" class="bg-[#F0BB78] hover:bg-[#C2A47E] text-white py-2 px-4 rounded">
                Add Product
            </button>
        </div>

        <!-- Search bar -->
        <div class="mb-6">
            <input type="text" placeholder="Search products..."
                class="min-w-full max-w-xs px-4 py-2 rounded border border-gray-300 focus:outline-none focus:border-[#C2A47E]">
        </div>

        <!-- table -->
        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border-4 border-black rounded-md">
                <thead class="bg-[#C2A47E] text-black">
                    <tr>
                        <th class="py-3 px-6 text-left border-r border-[#A88B68]">Name</th>
                        <th class="py-3 px-6 text-left border-r border-[#A88B68]">Code</th>
                        <th class="py-3 px-6 text-left border-r border-[#A88B68]">Category</th>
                        <th class="py-3 px-6 text-left border-r border-[#A88B68]">Price</th>
                        <th class="py-3 px-6 text-left border-r border-[#A88B68]">Status</th>
                        <th class="py-3 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rowClass = $row['is_archived'] ? 'bg-gray-200 text-gray-600' : 'hover:bg-gray-50';
                    ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td class="py-4 px-6 border-r border-black"><?php echo $row['prod_name']; ?></td>
                                <td class="py-4 px-6 border-r border-black"><?php echo $row['prod_id']; ?></td>
                                <td class="py-4 px-6 border-r border-black"><?php echo $row['category']; ?></td>
                                <td class="py-4 px-6 border-r border-black">₱<?php echo number_format($row['price'], 2); ?></td>
                                <td class="py-4 px-6 border-r border-black">
                                    <?php 
                                        if ($row['is_archived']) {
                                            echo 'Archived';
                                        } else {
                                            echo ($row['status'] == 'Available') ? 'Yes' : 'No';
                                        }
                                    ?>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex justify-center gap-2">
                                        <?php if (!$row['is_archived']) { ?>
                                            <button onclick="editProduct(<?php echo $row['prod_id']; ?>)"
                                                class="bg-[#F0BB78] hover:bg-[#C2A47E] text-white py-1 px-3 rounded">
                                                Edit
                                            </button>
                                            <button onclick="archiveProduct(<?php echo $row['prod_id']; ?>)"
                                                class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded">
                                                Archive
                                            </button>
                                        <?php } else { ?>
                                            <button onclick="unarchiveProduct(<?php echo $row['prod_id']; ?>)"
                                                class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded">
                                                Unarchive
                                            </button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='py-4 px-6 text-center'>No products found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="editProductModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Edit Product</h2>
            <form id="editProductForm" class="space-y-4">
                <input type="hidden" id="edit_prod_id" name="prod_id">

                <div>
                    <label class="block text-sm font-medium">Product Name</label>
                    <input type="text" id="edit_prod_name" name="prod_name" required
                        class="w-full p-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select id="edit_category" name="category" required class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled selected class="text-gray-400">Select Category</option>
                        <?php 
                        mysqli_data_seek($categories_result, 0);
                        while($cat = mysqli_fetch_assoc($categories_result)) { 
                        ?>
                            <option value="<?php echo $cat['category_name']; ?>">
                                <?php echo $cat['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input type="number" id="edit_price" name="price" step="0.01" required
                        class="w-full p-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select id="edit_status" name="status" class="w-full p-2 border border-gray-300 rounded">
                        <option value="Available">Yes</option>
                        <option value="Unavailable">No</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Cancel</button>
                    <button type="submit"
                        class="bg-[#F0BB78] hover:bg-[#C2A47E] text-white px-4 py-2 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editProduct(prodId) {
            console.log('Editing product:', prodId);
            fetch(`../endpoint/get_product.php?id=${prodId}`)
                .then(response => {
                    console.log('Response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    if(data.error) {
                        throw new Error(data.error);
                    }
                    document.getElementById('edit_prod_id').value = data.prod_id;
                    document.getElementById('edit_prod_name').value = data.prod_name;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_price').value = data.price;
                    document.getElementById('edit_status').value = data.status;
                    document.getElementById('editProductModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading product data');
                });
        }

        function closeEditModal() {
            document.getElementById('editProductModal').classList.add('hidden');
        }

        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../endpoint/update_product.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeEditModal();
                        location.reload();
                    } else {
                        alert('Error updating product');
                    }
                });
        });

        function archiveProduct(prodId) {
            if (confirm('Are you sure you want to archive this product?')) {
                fetch('../endpoint/archive_product.php', {
                    method: 'POST',
                    body: JSON.stringify({ prod_id: prodId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error archiving product');
                    }
                });
            }
        }

        function unarchiveProduct(prodId) {
            if (confirm('Are you sure you want to unarchive this product?')) {
                fetch('../endpoint/unarchive_product.php', {
                    method: 'POST',
                    body: JSON.stringify({ prod_id: prodId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error unarchiving product');
                    }
                });
            }
        }
    </script>
</body>

</html>