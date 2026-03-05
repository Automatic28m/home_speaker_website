<?php
include "./db.php";
$sql = "SELECT p.*, c.name as c_name FROM products as p, categories as c WHERE p.category_id = c.id";
$result = $conn->query($sql);
?>

<div class="max-w-5xl mx-auto mt-16">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-slate-800">สินค้า</h2>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <?php
        while ($row = $result->fetch_assoc()):
        ?>
            <a href="product_detail.php?id=<?php echo $row['id'] ?>" class="col-span-4 rounded border-1 border-slate-200 shadow p-4 flex flex-col no-underline! text-slate-800! hover:scale-105 transition duration-300">
                <div class="flex justify-center">
                    <?php
                    if (!empty($row['img_files'])) {
                        $images = explode(',', $row['img_files']);
                        $first_image = $images[0];
                        echo '<img src="./prod_images/' . $first_image . '" class="w-[200px] h-[150px] rounded object-contain">';
                    } else {
                        echo '<div class="w-[200px] h-[150px] rounded bg-slate-200 flex items-center justify-center text-[10px] text-slate-400">No Img</div>';
                    }
                    ?>
                </div>
                <span class="font-bold pt-4 text-lg uppercase"><?php echo $row['name'] ?></span>
                <span class="text-slate-500 text-md"><?php echo $row['c_name'] ?></span>
                <div class="flex justify-between items-end">
                    <span class="pt-4"><?php echo number_format($row['price'], 2); ?>฿</span>
                    <span class="pt-4 text-slate-500 text-sm"><?php echo $row['remain'] ?> คงเหลือ</span>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</div>