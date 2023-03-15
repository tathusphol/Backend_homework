<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Backend</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php
$topicFile = file_get_contents('topic.json', false);
$topicArray = json_decode($topicFile, true);
$topicvalidation = "";
$contentvalidation = "";
$text = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['topic'])) {
        $topicvalidation = "กรุณากรอกหัวข้อ *";
    }
    if (empty($_POST['content'])) {
        $contentvalidation = "กรุณากรอกเนื้อหา *";
    }
    if (!empty($_POST['topic']) && !empty($_POST['topic'])) {
        $topic = $_POST['topic'];
        $content = $_POST['content'];
        if (strlen($topic) < 4 || strlen($topic) > 140) {
            $topicvalidation = "ชื่อกระทู้ต้องยาว 4-140 ตัวอักษร *";
        }
        if (strip_tags($topic) != $topic) {
            $topicvalidation = "ชื่อกระทู้จะไม่อนุญาตใส่ HTML *";
        }
        if ((strlen($content) < 6 || strlen($content) > 20000) && !empty($content)) {
            $contentvalidation = "เนื้อหากระทู้ต้องยาว 6-2000 ตัวอักษร *";
        }
        if (empty($topicvalidation) && empty($contentvalidation)) {
            $count = count($topicArray);
            $filename = 'topic.json';
            $data = array(
                'topic' => $topic,
                'content' => $content
            );
            $topicArray[] = $data;
            $finalData = json_encode($topicArray);
            file_put_contents("topic.json", $finalData);
        }
    }
}
if (isset($_GET['id'])) {
    $deleteArray;
    $id = $_GET['id'];
    foreach ($topicArray as $key => $value) {
        if ($key != $id) {
            $deleteArray[] = $value;
        }
    }
    file_put_contents("topic.json", json_encode($deleteArray));
    header("Refresh:0; url=index.php");
}
?>

<body class="bg-white">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-2/3">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="background-color: #F3F3F3;" class="rounded-lg space-y-8 pt-4 pb-6 px-4 shadow-lg">
                <p style="color: #F27A01;" class="text-center px-8 text-3xl font-bold">สร้างกระทู้</p>
                <div class="px-12">
                    <label style="color : #F27A01;" for="topic" class="mb-2 text-xl font-medium dark:text-white flex">หัวข้อ <?php echo "<p class='text-red-400 text-xl px-4'>$topicvalidation</p>" ?></label>
                    <input type="text" name="topic" id="topic" class="border border-gray-300 placeholder-gray-500 text-gray-500 text-lg rounded-lg focus:ring-amber-500 focus:border-amber-500 w-full p-2.5" placeholder="Topic Name">
                </div>
                <div class="px-12">
                    <label style="color: #F27A01;" for="content" class="mb-2 text-xl font-medium dark:text-white flex">เนื้อหา <?php echo "<p class='text-red-400 text-xl px-4'>$contentvalidation</p>" ?></label>
                    <textarea id="content" name="content" rows="4" class="p-2.5 w-full placeholder-gray-500 text-lg text-gray-500 rounded-lg border border-gray-300 focus:ring-amber-500 focus:border-amber-500" placeholder="Write your thoughts here..."></textarea>
                </div>
                <div class="flex justify-end items-center px-12">
                    <input type="submit" class="text-white bg-green-500 hover:bg-green-700 duration-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                </div>
            </form>
            <div style="background-color: #F3F3F3;" class="rounded-lg space-y-8 pt-4 pb-6 px-4 mt-12 space-y-4 shadow-lg">
                <?php
                foreach ($topicArray as $key => $value) {
                    $valuetopic = $value['topic'];
                    $valuecontent = $value['content'];

                    echo "<div class='px-12 py-4 bg-gray-200 rounded-lg relative'>
                    <a class='absolute top-1 right-2 text-red-600' href='index.php?id=$key'>x</a></>
                    <span style='color : #F27A01;' class='text-xl'>หัวข้อ : </span><span class='text-gray-500'>$valuetopic</span>
                    <p style='color : #F27A01;' class='text-xl'>เนื้อหา :</p>
                    <p class='text-xl text-gray-500 break-words'>$valuecontent</p>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>