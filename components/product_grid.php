<?php
include "./db.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['sort'])) $_SESSION['sort'] = 'newest';
if (!isset($_SESSION['category'])) $_SESSION['category'] = 'all';

$search = $_GET['q'] ?? '';
$sort_choice = $_SESSION['sort'];
$filter_category = $_SESSION['category'];

switch ($sort_choice) {
    case 'asc':
        $order_by = "p.price ASC";
        break;
    case 'desc':
        $order_by = "p.price DESC";
        break;
    default:
        $order_by = "p.id DESC";
        break;
}

if ($filter_category === 'all') {
    $sql = "SELECT p.*, c.name as c_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE ? 
            ORDER BY $order_by";
    $stmt = $conn->prepare($sql);
    $search_param = "%$search%";
    $stmt->bind_param("s", $search_param);
} else {
    $sql = "SELECT p.*, c.name as c_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE ? AND c.id = ? 
            ORDER BY $order_by";
    $stmt = $conn->prepare($sql);
    $search_param = "%$search%";
    $category_id = (int)$filter_category;
    $stmt->bind_param("si", $search_param, $category_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<div class="max-w-5xl mx-auto py-4 px-4 font-['Prompt',_sans-serif]">
    <div class="text-left mb-8">
        <h2 class="text-3xl font-bold text-slate-800 uppercase">Home Theater</h2>
        <span class="text-lg text-slate-500">เครื่องเสียงภายในบ้าน</span>
    </div>

    <div class="flex flex-row justify-between item-center w-full mb-8 gap-6">
        <form method="GET" action="index.php" class="flex gap-4 items-center">
            <!-- <span>ค้นหา:</span> -->
            <div class="flex">
                <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" class="w-[300px] border-1 border-slate-300 shadow-sm rounded-l px-3 py-2" placeholder="ค้นหาสินค้า...">
                <button type="submit" class=" border-y-1 border-r-1 border-slate-300 shadow-sm rounded-r! px-3 py-2 ">ค้นหา</button>
            </div>
        </form>
        <div class="flex gap-4 items-center">
            <span>ยี่ห้อ:</span>
            <select name="sort" id="sort" class="border-1 border-slate-300 shadow-sm rounded w-fit px-3 py-2"
                onchange="fetch('set_category_filter.php?category=' + this.value).then(() => location.reload())">
                <option value="all">ทั้งหมด</option>
                <?php
                $sql_categories = "SELECT * FROM categories ORDER BY name ASC";
                $result_cate = $conn->query($sql_categories);
                while ($row_cate = $result_cate->fetch_assoc()):
                ?>
                    <option value="<?php echo $row_cate['id']; ?>" <?php echo ($_SESSION['category'] == $row_cate['id']) ? 'selected' : ''; ?>><?php echo $row_cate['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="flex gap-4 items-center">
            <span>จัดเรียงตาม:</span>
            <select name="sort" id="sort" class="border-1 border-slate-300 shadow-sm rounded w-fit px-3 py-2"
                onchange="fetch('set_sort.php?sort=' + this.value).then(() => location.reload())">
                <option value="newest" <?php echo ($_SESSION['sort'] == 'newest') ? 'selected' : ''; ?>>สินค้าใหม่</option>
                <option value="asc" <?php echo ($_SESSION['sort'] == 'asc') ? 'selected' : ''; ?>>ราคา ต่ำ-สูง</option>
                <option value="desc" <?php echo ($_SESSION['sort'] == 'desc') ? 'selected' : ''; ?>>ราคา สูง-ต่ำ</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <?php
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
                <a href="product_detail.php?id=<?php echo $row['id'] ?>"
                    class="group col-span-4 rounded border-1 border-slate-100 shadow-sm p-4 flex flex-col no-underline! text-slate-800! hover:scale-105 hover:z-0 bg-white transition duration-300 justify-between">

                    <span class="text-sm text-lg uppercase font-semibold"><?php echo $row['name'] ?></span>
                    <span class="text-slate-500 text-xs"><?php echo $row['c_name'] ?></span>

                    <div class="flex justify-center py-4 overflow-hidden">
                        <?php
                        if (!empty($row['img_files'])) {
                            $images = explode(',', $row['img_files']);
                            $first_image = $images[0];
                            echo '<img src="./prod_images/' . $first_image . '" class="w-[200px] h-[150px] rounded object-contain transition-transform duration-500 group-hover:scale-115">';
                        } else {
                            echo '<div class="w-[200px] h-[150px] rounded bg-slate-200 flex items-center justify-center text-[10px] text-slate-400">No Img</div>';
                        }
                        ?>
                    </div>

                    <div class="flex justify-between items-end">
                        <span class="pt-4 text-sm">฿<?php echo number_format($row['price'], 2); ?></span>
                        <span class="pt-4 text-slate-500 text-xs">
                            <?php if ($row['remain'] <= 0) {
                                echo "สินค้าหมด";
                            } else {
                                echo $row['remain'] . " คงเหลือ";
                            }
                            ?>
                        </span>
                    </div>
                </a>
            <?php
            endwhile;
        else:
            ?>
            <div class="col-span-12 py-20 text-center">
                <h3 class="text-xl text-slate-400">ไม่พบสินค้าที่ตรงกับ "<?php echo htmlspecialchars($search); ?>"</h3>
                <a href="index.php" class="text-blue-500 hover:underline text-sm mt-4 inline-block">ดูสินค้าทั้งหมด</a>
            </div>
        <?php endif; ?>
    </div>
</div>