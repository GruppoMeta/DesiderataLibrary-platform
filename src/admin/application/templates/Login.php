<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php print($doctitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php print($head); ?>
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body id="LoginPage">
    <div id="outer">
        <div id="loginbox">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <form id="login" method="post" action="">
                            <img id="logo" src="img/logo/logo-login.png" alt="<?php echo __Config::get('APP_NAME')?>" />
                            <hr class="separator" />
                            <div id="step-login">
                            <?php if ($error) {?>
                              <div class="alert alert-error"><?php print($error) ?></div>
                            <?php } else {?>
                              <span class="text">Inserisci username e password.</span>
                            <?php }?>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input class="span11" name="loginform_LoginId" type="text" placeholder="Username" />
                            </div>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                <input class="span11" name="loginform_Password" type="password" placeholder="Password" />
                            </div>
                            <br />
                                <a class="pull-left js-askPassword" href="#">Hai dimenticato la password?</a>
                            <input type="hidden" name="action" value="login">
                            <input type="submit" id="submit" class="pull-right btn btn-inverse" value="Login" />
                            </div>
                            <div id="step-password" class="hidden">
                                <span class="text">Inserisci la tua email.</span>
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-envelope"></i></span>
                                    <input class="span11" id="email" type="email" placeholder="Email" autocomplete="off" />
                                </div>
                                <br />
                                <a class="pull-left js-backLogin" href="#">Torna al login</a>
                                <input type="button" id="btn-askPassword" class="pull-right btn btn-inverse" value="Invia" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <footer>
        <p><?php echo __Config::get('APP_NAME').' v'.__Config::get('APP_VERSION') ?></p>
    </footer>
     <script>
$(function(){
    $('#step-password').hide().removeClass('hidden');

    $('a.js-askPassword').click(function(e){
        e.preventDefault();
        $('#step-login').hide();
        $('#step-password').show();
    });

    $('a.js-backLogin').click(function(e){
        e.preventDefault();
        $('#step-login').show();
        $('#step-password').hide();
    });

    $('#email').bind("keyup keypress", function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('#btn-askPassword').click(function(e){
        if ($('#email')[0].checkValidity()) {
            var email = $('#email').val();
            $.ajax({
                    url: Glizy.baseUrl+'/../rest/user/recoverPassword',
                    data: {email: email},
                    type: 'POST',
                    success: function(r, textStatus) {
                        alert('La nuova password è stata inviata per email');
                        $('#email').val('');
                        $('a.js-backLogin').trigger('click');
                    },
                    error: function(r, textStatus) {
                        alert('L\'email indicata non è presente nel database');
                    }
            });
        } else {
            alert('Inserire un\'email valida');
        }
    })
})
     </script>
 </body>
</html>