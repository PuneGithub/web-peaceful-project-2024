<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income and Expenses JavaScript</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="test_style.css">
</head>

<body>
    <i class="fa-solid fa-building-columns fa-2xl" style="color: #63E6BE;"></i>
    <div class="container">
        <h4>ยอดคงเหลือ (บาท)</h4>
        <h1 id="balance">฿0.00</h1>
        <div class="income-expense-container">
            <div class="border-right">
                <h4>รายรับ</h4>
                <p id="money-plus" class="money plus">+ ฿5000</p>
            </div>
            <div>
                <h4>รายจ่าย</h4>
                <p id="money-minus" class="money minus">- ฿1000</p>
            </div>
        </div>
        <h3>ประวัติธุระกรรม</h3>
        <ul id="list" class="list"></ul>
        <h3>เพิ่มธุระกรรม</h3>
        <form id="form">
            <div class="form-control">
                <label for="text">ชื่อธุระกรรม</label>
                <input type="text" id="text" placeholder="ธุระกรรม">
            </div>
            <div class="form-control">
                <label for="amount">จำนวนเงิน</label>
                <input type="number" id="amount" placeholder="ระบุจำนวนเงิน">
            </div>
            <button class="btn">Add</button>
        </form>
    </div>

    <script src="test_javascript.js"></script>
</body>

</html>