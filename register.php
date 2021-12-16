<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<div class="container">
    <div id="login-row" class="row justify-content-center align-items-center">
        <div id="login-column" class="col-md-6">
            <div id="login-box" class="col-md-12">
                <form id="login-form" class="form" method="post" action='index.php?site=register'>
                    <div class="form-group">
                        <label for="email" class="">Email:</label><br>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="">Hasło:</label><br>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="rpassword" class="">Powtórz hasło:</label><br>
                        <input type="password" name="rpassword" id="rpassword" class="form-control">
</div>
                    <div class="form-group">
                         <input type='hidden' name='mode' value='2'>
                        <input type="submit" name="submit" class="btn btn-info btn-md" value="załóż konto">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>