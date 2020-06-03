<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

if (!isset($_GET['page'])) {
    $_SESSION['couchSearch'] = "";
    $_SESSION['option'] = "";
}

if (isset($_POST["couchSearch"])) {
    $_SESSION['option'] = $_POST['option'];
    $_SESSION['couchSearch'] = $_POST['couchSearch'];
}

$sqlcouchSearch = "SELECT 
`couch`.`c_id`, `couch`.`c_username`,`couch`.`c_pwd`, `couch`.`c_name`,`couch`.`c_img`,`couch`.`c_email`, `couch`.`c_gender`, `couch`.`c_phoneNumber`,`couch`.`c_birthday`, `couch`.`c_address`,`couch`.`created_at`, `couch`.`updated_at`
FROM `couch`";
$sqlTotal = "SELECT count(1) FROM `couch` ";



if (isset($_SESSION['option'])) {
    switch ($_SESSION['option']) {
        case "options1":
            $sqlcouchSearch .= " WHERE `couch`.`c_username`
                                   LIKE '%{$_SESSION['couchSearch']}%' ";


            $sqlTotal .= " WHERE `couch`.`c_username`
                                   LIKE '%{$_SESSION['couchSearch']}%' ";


            $UsernameCheck = 'checked="true"';
            break;
        case 'options2':
            $sqlcouchSearch .= " WHERE `couch`.`c_name`
                                    LIKE '%{$_SESSION['couchSearch']}%' ";


            $sqlTotal .= " WHERE `couch`.`c_name`
                                    LIKE '%{$_SESSION['couchSearch']}%' ";


            $NameCheck = 'checked="true"';
            break;
        case 'options3':
            $sqlcouchSearch .= " WHERE `couch`.`c_email`
                                   LIKE '%{$_SESSION['couchSearch']}%' ";


            $sqlTotal .= " WHERE `couch`.`c_email`
                                   LIKE '%{$_SESSION['couchSearch']}%' ";

            $Emailcheck = 'checked="true"';
            break;
    }
} else {

    $sqlTotal .= "  WHERE `couch`.`c_name`
                                LIKE '%{$_SESSION['couchSearch']}%' ";
    $sqlcouchSearch .= " WHERE `couch`.`c_name` 
                               LIKE '%{$_SESSION['couchSearch']}%' ";
    $NameCheck = 'checked="true"';
}

$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
$totalPages = ceil($total / $numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1




$sqlcouchSearch .= "LIMIT ?, ? ";
$arrParam = [($page - 1) * $numPerPage, $numPerPage];
$stmtCouch = $pdo->prepare($sqlcouchSearch);
$stmtCouch->execute($arrParam);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>教練列表</title>
    <style>
        img.itemImg {
            width: 250px;
        }

        p {
            width: 100px;
        }

        .border {
            border: 1px solid;
        }

        img.payment_type_icon {
            width: 50px;
        }

        .check {
            width: 30px;
            height: 30px;

        }

        .border {
            text-align: center;
            /* font-size: 18px; */

        }

        .table thead th {
            vertical-align: center;
            font-size: 22px;
        }

        .card-body {
            background: #454747 !important;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <?php require_once('./templates/title.php'); ?>
    <div class="mt-3">
        <div class="container title">
            <a href="./couch.php" class="my-3 btn btn-primary btn-lg active" role="button" aria-pressed="true">教練列表</a>
            <button class="btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"> 搜尋 </button>
        </div>
        <div class="collapse-fluid collapse" id="collapseExample">
            <div class="card card-body mb-4">
                <form name="myForm" method="POST" action="./couchSearch.php">
                    <div class="btn-group btn-group-toggle mb-4" data-toggle="buttons" name="titleMethod">
                        <label class="smbtn btn btn-outline-light">
                            <input type="radio" name="option" value="options1" id="option1" checked> 帳號
                        </label>
                        <label class="smbtn btn btn-outline-light">
                            <input type="radio" name="option" value="options2" id="option2"> 姓名
                        </label>
                        <label class="smbtn btn btn-outline-light">
                            <input type="radio" name="option" value="options3" id="option3"> 信箱
                        </label>
                    </div>
                    <br>
                    <input style="width:300px" class="form-control my-2" type="text" name="couchSearch" placeholder="請輸入關鍵字" required>
                    <tr>
                        <td class="border" colspan="2"><button class="smbtn btn btn-outline-light mt-4" type="submit" name="smb_add">搜尋</button>
                        </td>
                    </tr>
                </form>
            </div>
        </div>
        <form name="myForm" entype="multipart/form-data" method="POST" action="couchdelete.php">
            <table class="border table table-hover">
                <thead class="">
                    <tr>
                        <th class="border p-3 box">
                            <input type="checkbox" id="chk1" class="check" name="chk[]" value="" />
                        </th>
                        <th class="border p-2">帳號</th>
                        <th class="border p-2">姓名</th>
                        <th class="border p-2">照片</th>
                        <th class="border p-2">信箱</th>
                        <th class="border p-2">性別</th>
                        <th class="border p-2">手機號碼</th>
                        <th class="border p-2">生日</th>
                        <th class="border p-2">地址</th>
                        <th class="border p-2">新增時間</th>
                        <th class="border p-2">更新時間</th>
                        <th class="border p-2">功能</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmtCouch->rowCount() > 0) {
                        $arrCouch = $stmtCouch->fetchAll(PDO::FETCH_ASSOC);

                        for ($i = 0; $i < count($arrCouch); $i++) {
                    ?>
                            <tr>
                                <td class="border">
                                    <input type="checkbox" class="check" name="chk[]" value="<?php echo $arrCouch[$i]['c_id']; ?>" />
                                </td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['c_username']; ?></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['c_name']; ?></td>
                                <td class="border p-2"><img class="itemImg" src="../images/couch/<?php echo $arrCouch[$i]['c_img']; ?>" /></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['c_email']; ?></td>
                                <td class="border p-4"><?php echo $arrCouch[$i]['c_gender']; ?></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['c_phoneNumber']; ?></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['c_birthday']; ?></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['c_address']; ?></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['created_at']; ?></td>
                                <td class="border p-2"><?php echo $arrCouch[$i]['updated_at']; ?></td>
                                <td class="border p-2">
                                    <button class="smbtn btn btn-outline-light m-2" type="button" onclick="location.href=('./couchedit.php?c_id=<?php echo $arrCouch[$i]['c_id'] ?>')"><span class="update">修改</span></button>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td class="mt-3 container title btn-lg border" colspan="12">沒有資料</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="border" colspan="12">
                            <div class="float-left p-2">
                                <?php
                                for ($i = 1; $i <= $totalPages; $i++) { ?>
                                    <a class=px-2 href="?page=<?= $i ?>"><?= $i ?></a>
                                <?php } ?>
                        </td>
                    </tr>

                    <?php if ($total > 0) { ?>
                        <tr>
                            <td class="border" colspan="12"><button class="smbtn btn btn-outline-light float-left m-2" type="submit" name="smb">刪除</button></td>
                        </tr>
                    <?php } ?>
                </tfoot>
            </table>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        //抓第一個框框
        document.getElementById("chk1").onclick = function() {
            //抓所有框框(陣列)
            let chk = document.getElementsByName('chk[]');
            //this=第一個框框有勾選的話
            if (this.checked) {
                console.log(this);
                //陣列一個一個打勾
                for (let i = 0; i < chk.length; i++) {
                    //checked特性：只要有值就會勾選
                    //就算給他123、456，任何一個值都可行
                    chk[i].checked = true;
                    console.log(chk[i].checked);
                }
            } else {
                for (let i = 0; i < chk.length; i++) {
                    //checked不勾選：只能是null、""、false
                    //如果是"false"：因為會判定為字串，認為是一個值故也會當作要勾選
                    chk[i].checked = false;
                    console.log(chk[i].checked);
                }
            }

        }
    </script>
</body>

</html>