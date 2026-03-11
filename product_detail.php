<?php
include './db.php';
session_start();
$id = $_GET['id'];
$sql = "SELECT p.*, c.name as c_name FROM products as p, categories as c WHERE p.category_id = c.id AND p.id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$images = explode(',', $row['img_files']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>รายละเอียดสินค้า</title>
</head>

<?php include './components/navbar.php' ?>

<body>
    <div class="font-['Prompt',_sans-serif] my-16">
        <div class="">
            <!-- <div class="flex flex-col items-left gap-4 max-w-5xl mx-auto ">
                <span class="text-xl text-slate-500">
                    <?php echo $row['c_name'] ?>
                </span>
                <span class="text-center-mb-8 text-5xl text-slate-800 uppercase">
                    <?php echo $row['name'] ?>
                </span>
            </div> -->

            <!-- Slider Container -->
            <div x-data="{ 
                    activeSlide: 0, 
                    images: <?php echo htmlspecialchars(json_encode($images)); ?>,
                    path: './prod_images/' 
                }"
                class="relative w-full max-w-4xl mx-auto overflow-hidden rounded-lg my-10">

                <div class="relative h-96">
                    <template x-for="(image, index) in images" :key="index">
                        <div x-show="activeSlide === index"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="absolute inset-0 w-full h-full">
                            <img :src="`${path}${image}`" class="w-full h-full object-contain">
                        </div>
                    </template>
                </div>

                <button @click="activeSlide = activeSlide === 0 ? images.length - 1 : activeSlide - 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-3 rounded-full! shadow-md w-[50px] h-[50px] border border-slate-200 z-10 transition-colors flex justify-center items-center">
                    &#8592;
                </button>
                <button @click="activeSlide = activeSlide === images.length - 1 ? 0 : activeSlide + 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-3 rounded-full! shadow-md w-[50px] h-[50px] border border-slate-200 z-10 transition-colors flex justify-center  items-center">
                    &#8594;
                </button>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="activeSlide = index"
                            :class="activeSlide === index ? 'bg-slate-800 w-4' : 'bg-gray-200 w-2'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-4"
                x-data="{ 
                    count: 1, 
                    stock: <?php echo (int)$row['remain']; ?>, 
                    p_id: <?php echo (int)$row['id']; ?>,
                    isAdding: false,
                    message: ''
                }">

                <div class="flex flex-col items-left gap-4">
                    <span class="text-xl text-slate-500"><?php echo $row['c_name'] ?></span>
                    <span class="text-5xl text-slate-800 uppercase font-bold"><?php echo $row['name'] ?></span>
                </div>

                <div class="flex flex-wrap items-center gap-6 py-8 border-y border-slate-100 mt-10">
                    <div class="flex items-center gap-4">
                        <span class="text-slate-600">จำนวน:</span>
                        <div class="flex items-center border border-slate-200 rounded overflow-hidden bg-white">
                            <button @click="if(count > 1) count--"
                                class="px-4 py-2 text-xl hover:bg-slate-50 disabled:opacity-30" :disabled="count <= 1">&minus;</button>
                            <input type="number" x-model.number="count"
                                @input="if(count > stock) count = stock; if(count < 1) count = 1;"
                                class="w-14 text-center border-x border-slate-100 outline-none py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <button @click="if(count < stock) count++"
                                class="px-4 py-2 text-xl hover:bg-slate-50 disabled:opacity-30" :disabled="count >= stock">&plus;</button>
                        </div>
                    </div>

                    <button @click="
                            isAdding = true;
                            fetch('add_to_cart_action.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ product_id: p_id, qty: count })
                            })
                            .then(res => res.json())
                            .then(data => {
                                isAdding = false;
                                message = data.message;
                                if(document.getElementById('cart-badge')) {
                                    document.getElementById('cart-badge').innerText = data.cart_count;
                                }
                                setTimeout(() => message = '', 3000);
                            })
                        "
                        :disabled="isAdding || stock <= 0"
                        class="rounded px-10 py-3 bg-slate-800 text-white font-medium hover:bg-blue-600 transition duration-300 disabled:bg-slate-400 flex items-center gap-2">
                        <span x-show="!isAdding">เพิ่มลงตะกร้า</span>
                        <span x-show="isAdding" class="animate-pulse">กำลังบันทึก...</span>
                    </button>

                    <div x-show="message" x-transition.duration.500ms
                        class="text-green-600 font-medium text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span x-text="message"></span>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-y-6 mt-12 text-sm md:text-base">
                    <span class="text-slate-400 col-span-3 uppercase">ราคา</span>
                    <span class="text-slate-900 col-span-9 text-2xl">฿<?php echo number_format($row['price'], 2); ?></span>

                    <span class="text-slate-400 col-span-3 uppercase">โมเดล</span>
                    <span class="col-span-9 text-slate-800 font-medium"><?php echo htmlspecialchars($row['name'] ?? ''); ?></span>

                    <span class="text-slate-400 col-span-3 uppercase">แบรนด์</span>
                    <span class="text-slate-800 col-span-9"><?php echo htmlspecialchars($row['c_name'] ?? ''); ?></span>

                    <span class="text-slate-400 col-span-3 uppercase">คำอธิบาย</span>
                    <p class="text-slate-600 col-span-9 leading-relaxed"><?php echo nl2br(htmlspecialchars($row['detail'] ?? '')); ?></p>

                    
                </div>
            </div>
        </div>

    </div>
</body>

</html>