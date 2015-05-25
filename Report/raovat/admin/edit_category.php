 <?php include('../includes/header.php');?>
  <?php include('../includes/mysqli_connect.php');?>
  <?php include('../includes/functions.php');?>
 <?php include('../includes/sidebar-admin.php');?>
    
    <?php
    //xac nhan bien get ton tai va thuoc loai du lieu cho phep.
    if(isset($_GET['cid']) && filter_var($_GET['cid'],FILTER_VALIDATE_INT,array('min_rage' => 1))){
        $cid = $_GET['cid'];
    }else{
        redirect_to('admin/admin.php');
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){ //gia tri ton tai, xu ly form
        $errors = array();
        //kiem tra ten cua category
        if(empty($_POST['category'])){
            $errors[] = "category";
        } else{
        $cat_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category']));
        }
        //kiem tra position cua category
        if(isset($_POST['position']) && filter_var($_POST['position'],FILTER_VALIDATE_INT,array('min_range' => 1))){
        $position = $_POST['position'];
        }else{
            $errors[] = "position";
        }
        if(empty($errors)){
        $q = "UPDATE categories SET cat_name = '{$cat_name}',position = $position WHERE cat_id={$cid} LIMIT 1";
        $r = mysqli_query($dbc,$q);
        confirm_query($r,$q);
        
        if(mysqli_affected_rows($dbc) == 1){
            $message = "<p class='success'>The category was edited successfully.</p>";
        } else{
            $message = "<p class='warning'>Could not edit to the database due to a system error.</p>";
        }
        }else{
            $message = "<p class='warning'>Please fill all the required feilds</p>";
        }
        
    } //end Main IF condition
    ?>
    <div id="content">
    <?php 
       $q = "SELECT cat_name, position FROM categories WHERE cat_id={$cid}";
       $r = mysqli_query($dbc,$q);
       confirm_query($r,$q);
       if(mysqli_num_rows($r) == 1){
        //neu category ton tai trong DB, dua vao cid, xuat du lieu ra ngoai trinh duyet
        list($cat_name,$position) = mysqli_fetch_array($r, MYSQLI_NUM);
       }else{
        //neu CID khong hop le thi khong the hien thi category
        $message = "<p class='warning'>The category does not exist</p>";
       }
    ?>
    <h2>Edit a category: <?php if(isset($cat_name)) echo $cat_name; ?></h2>
    <?php
    if(!empty($message)){
    echo $message;
    }
    ?>
    <form id="edit_cat" action="" method="post">
    <fieldset>
    <legend>Edit category</legend>
    <div>
    <label for="category">Category name: <span class="required">*</span>
    <?php
    if(isset($errors) && in_array('category',$errors)){
        echo "<p class='warning'>Please fill in the category name</p>";
    }
    ?>
    </label>
    <input type="text" name="category" id="category" value="<?php if(isset($cat_name)) echo $cat_name; ?>" size="20" maxlength="160" tabindex="1" />
    </div>
    <div>
    <label for="position">Position: <span class="required">*</span>
    <?php
    if(isset($errors) && in_array('position',$errors)){
        echo "<p class='warning'>Please pick a position</p>";
    }
    ?>
    </label>
    <select name="position" tabindex="2">
    <?php
       $q = "SELECT count(cat_id) AS count FROM categories";
       $r = mysqli_query($dbc, $q) or die("Query {$q} \n <br/> MySQL error: " .mysqli_error($dbc));
       if(mysqli_num_rows($r) == 1){
        list($num) = mysqli_fetch_array($r,MYSQLI_NUM);
        for($i = 1; $i <= $num + 1; $i++){
            echo "<option value='{$i}'";
                if(isset($position) && $position ==  $i){
                    echo "selected='selected'";
                }
            echo ">".$i."</option>";
            
        }
       }
    ?>
    </select>
    </div>
    </fieldset>
    <p><input type="submit" name="submit" value="Add Category"/></p>
    </form>
    </div><!--end content-->
<?php include('../includes/sidebar-b.php');?>
 <?php include('../includes/footer.php');?>
    