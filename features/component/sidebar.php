<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="fixed top-[171px] left-0 w-[230px] h-[calc(100vh-171px)] bg-[#F0BB78] py-5">
    <a href="../features/admin_dashboard.php" 
        class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all
        <?php echo ($current_page == 'admin_dashboard.php') ? 'bg-[#C2A47E] outline outline-2 outline-black' : ''; ?>">
        Dashboard</a>
    <a href="#" class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all">Order</a>

    <div class="bg-[#F2DBBE] px-6 pt-5 pb-2 text-gray-400 text-xs font-bold tracking-wider">LISTS</div>
    <a href="../features/inventory.php" 
        class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all
        <?php echo ($current_page == 'inventory.php') ? 'bg-[#C2A47E] outline outline-2 outline-black' : ''; ?>">
        Inventory</a>
    <a href="../features/categories.php" 
        class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all
        <?php echo ($current_page == 'categories.php') ? 'bg-[#C2A47E] outline outline-2 outline-black' : ''; ?>">
        Categories</a>
    <a href="../features/add_products.php" 
        class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all
        <?php echo ($current_page == 'add_products.php') ? 'bg-[#C2A47E] outline outline-2 outline-black' : ''; ?>">
        Products</a>

    <div class="bg-[#F2DBBE] px-6 pt-5 pb-2 text-gray-400 text-xs font-bold tracking-wider">REPORTS</div>
    <a href="#" class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all">Orders</a>
    <a href="#" class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all">Sold Items</a>

    <div class="bg-[#F2DBBE] px-6 pt-5 pb-2 text-gray-400 text-xs font-bold tracking-wider">PEOPLE</div>
    <a href="#" class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all">Customers</a>
    <a href="#" class="block px-6 py-4 text-black hover:bg-[#C2A47E] hover:pl-8 transition-all">Users</a>
</div>