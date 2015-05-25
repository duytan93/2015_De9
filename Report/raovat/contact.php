<?php $title=''; include('includes/header.php');?>
 <?php include('includes/mysqli_connect.php');?>
 <?php include('includes/functions.php');?>
 <?php include('includes/sidebar-a.php');?>
 <div id="content">
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //tao bien de bao loi neu co
        $errors = array();
        // giam spam cho email
        $clean = array_map('clean_email',$_POST);
        if(empty($clean['name'])){
            $errors[] = 'name';
        }
        if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/',$clean['email'])){
            $errors[] = 'email';
        }
        if(empty($clean['comment'])){
            $errors[] = 'comment';
        }
        if(isset($_POST['captcha']) && trim($_POST['captcha'] != $_SESSION['q']['answer'])){
        $errors[] = 'wrong';
    }
    
    if(!empty($_POST['url'])){
        redirect_to('thankyou.html');
    }
        if(empty($errors)){
            $body = "Name: {$clean['name']} \n\n Comment:\n".strip_tags($clean['comment']);
            $body = wordwrap($body,70);
            if(mail('baochinh0392@gmail.com', 'contact form submission', $body, 'FROM: localhost@localhost')){
                echo "<p class='success'>Thank you for contact me.I will get back to you ASAP</p>";
                $_POST = array();
            }else{
                echo "<p class='warning'>Sorry, your email could not be sent.</p>";
            }
        }else{
            echo "<p class='warning'>Please fill in all the required feilds</p>";
        }
    }
    ?>
 <form id="contact" action="" method="post">
    <fieldset>
    	<legend>Contact</legend>
            <div>
                <label for="Name">Your Name: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('name',$errors)) {
                            echo "<span class='warning'>Please enter your name.</span>";
                            }
                    ?>
                </label>
                <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) {echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="1" />
            </div>
        	<div>
                <label for="email">Email: <span class="required">*</span>
                <?php 
                        if(isset($errors) && in_array('email',$errors)) {
                            echo "<span class='warning'>Please enter your email.</span>";
                            }
                    ?>
                </label>
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="2" />
            </div>
            <div>
                <label for="comment">Your Message: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('comment',$errors)) {
                            echo "<span class='warning'>Please enter your message.</span>";
                            }
                    ?>
                </label>
                <div id="comment">
                <textarea name="comment" rows="10" cols="45" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');} ?></textarea></div>
            </div>
            
            <div>
            <label for="captcha">Phien ban dien vao gia tri so cho cau hoi sau: <?php echo captcha();?><span class="required">*</span>
            <?php if(isset($errors) && in_array('wrong',$errors)){ echo "<span class='warning'>Please give a correct answer.</span>";}?>
            </label>
            <input type="text" name="captcha" id="captcha" value="" size="20" maxlength="4" tabindex="4" />
            </div>
            
            <div class="website">
            <label for="website">Neu ban nhin thay truong nay thi DUNG dien gi vao het: </label>
            <input type="text" name="url" id="url" value="" size="10" maxlength="20" />
            </div>
            
    </fieldset>
    <div><input type="submit" name="submit" value="Send Email" tabindex="3"/></div>
</form>
 </div><!--end content-->
 <?php include('includes/sidebar-b.php');?>
 <?php include('includes/footer.php');?>
    