<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<div class="container">
    <div id="login-row" class="row justify-content-center align-items-center">
        <div id="login-column" class="col-md-6">
            <div id="login-box" class="col-md-12">
                <form id="login-form" class="form" method="post" action='login-init.php'>
                    <div class="form-group">
                        <label for="email" class="">Email:</label><br>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="">Hasło:</label><br>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <?php
                        if(isset($_SESSION['alert'])){
                            foreach($_SESSION['alert'] as $z){
                                ?>
                                <div class='row alert alert-<?=$z['type']?> alert-dismissible my-2'>
                                                       <span><?=$z['value']?></span> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                                </div>
                                <?php
                            }
                        }
                    ?>
                    
                    <div class="form-group">
                        <label for="remember-me" class=""><span>Zapamiętaj mnie</span> <span><input id="remember-me"
                                    name="remember-me" type="checkbox"></span></label><br>
                        <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                    </div>
                    <div id="register-link" class="text-right">
                        <a href="?site=register" class="">Załóż swoje konto</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>